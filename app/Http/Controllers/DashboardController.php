<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use App\Models\Pago;
use App\Models\Movimiento;
use App\Models\Visita;
use App\Services\BcvScraperService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(BcvScraperService $bcvService)
    {
        $stats = Socio::selectRaw("
            COALESCE(SUM(CASE WHEN estado = 'activo' THEN 1 ELSE 0 END), 0) as activos,
            COALESCE(SUM(CASE WHEN estado = 'inactivo' THEN 1 ELSE 0 END), 0) as inactivos,
            COALESCE(SUM(CASE WHEN saldo < 0 THEN 1 ELSE 0 END), 0) as morosos
        ")->first();

        $totalActivos = (int) ($stats->activos ?? 0);
        $totalInactivos = (int) ($stats->inactivos ?? 0);
        $totalMorosos = (int) ($stats->morosos ?? 0);

        $tasaBcv = $bcvService->obtenerTasa();

        $sociosRecientes = Socio::orderBy('id', 'desc')->take(10)->get();
        $pagosRecientes = Pago::with('socio')->orderBy('fecha_pago', 'desc')->take(10)->get();

        $inicioMes = now()->startOfMonth();
        $ingresosMes = (float) Pago::where('fecha_pago', '>=', $inicioMes)->sum('monto');
        $pagosMesCount = Pago::where('fecha_pago', '>=', $inicioMes)->count();
        $visitasHoy = Visita::whereDate('fecha_entrada', today())->count();
        $dentroAhora = Visita::whereDate('fecha_entrada', today())->whereNull('fecha_salida')->count();
        $totalSocios = $totalActivos + $totalInactivos;
        $solventes = max(0, $totalSocios - $totalMorosos);
        $pctSolventes = $totalSocios > 0 ? round(($solventes / $totalSocios) * 100) : 0;

        return view('dashboard', compact(
            'ingresosMes',
            'pagosMesCount',
            'visitasHoy',
            'dentroAhora',
            'totalSocios',
            'solventes',
            'pctSolventes',
            'totalActivos',
            'totalInactivos',
            'totalMorosos',
            'tasaBcv',
            'sociosRecientes',
            'pagosRecientes'
        ));
    }

    public function syncBcv(BcvScraperService $bcvService)
    {
        $nuevaTasa = $bcvService->obtenerTasa(true);
        return response()->json([
            'success' => true,
            'tasa' => $nuevaTasa,
            'formatted' => number_format($nuevaTasa, 2, ',', '.') . ' Bs'
        ]);
    }
}
