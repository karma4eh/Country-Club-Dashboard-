<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use App\Models\Pago;
use App\Models\Movimiento;
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

        return view('dashboard', compact(
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
