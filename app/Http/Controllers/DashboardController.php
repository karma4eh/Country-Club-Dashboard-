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
        $totalActivos = Socio::where('estado', 'activo')->count();
        $totalInactivos = Socio::where('estado', 'inactivo')->count();
        $totalMorosos = Socio::where('saldo', '<', 0)->count();

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
