<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use App\Models\Pago;
use App\Models\Movimiento;
use App\Services\BcvScraperService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with('socio')->orderBy('fecha_pago', 'desc')->paginate(20);
        return view('pagos.index', compact('pagos'));
    }

    public function create(BcvScraperService $bcvService)
    {
        $tasaBcv = $bcvService->obtenerTasa();
        return view('pagos.create', compact('tasaBcv'));
    }

    public function calcularDeuda(Request $request, BcvScraperService $bcvService)
    {
        $cedula = trim($request->input('cedula', ''));
        $socio = Socio::where('cedula', $cedula)->first();

        if (!$socio) {
            return response()->json(['error' => 'No se encontró ningún socio con la cédula indicada.'], 404);
        }

        $tasa = $bcvService->obtenerTasa();

        return response()->json([
            'id' => $socio->id,
            'nombre' => $socio->nombre_completo,
            'cedula' => $socio->cedula,
            'accion' => $socio->accion,
            'saldo' => (float) $socio->saldo,
            'condicion' => $socio->condicion,
            'meses_deuda' => $socio->meses_deuda,
            'tasa_bcv' => $tasa,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cedula' => 'required|string',
            'monto' => 'required|numeric|min:0.01',
            'descripcion' => 'required|string|max:255',
        ]);

        $socio = Socio::where('cedula', $validated['cedula'])->first();

        if (!$socio) {
            return back()->withErrors(['cedula' => 'Socio no encontrado.'])->withInput();
        }

        // Prevención de doble clic / cobro duplicado en menos de 20 segundos
        $pagoReciente = Pago::where('socios_id', $socio->id)
            ->where('monto', $validated['monto'])
            ->where('fecha_pago', '>=', Carbon::now()->subSeconds(20))
            ->first();

        if ($pagoReciente) {
            return redirect()->route('pagos.receipt', $pagoReciente)
                ->with('info', 'Este cobro ya había sido procesado previamente.');
        }

        $pago = DB::transaction(function () use ($socio, $validated) {
            // 1. Crear el pago
            $nuevoPago = Pago::create([
                'socios_id' => $socio->id,
                'descripcion' => $validated['descripcion'],
                'monto' => $validated['monto'],
                'fecha_pago' => Carbon::now(),
            ]);

            // 2. Actualizar saldo del socio y último mes pagado
            $socio->saldo += $validated['monto'];
            $socio->ultimo_mes_pagado = Carbon::now();
            $socio->save();

            // 3. Registrar auditoría
            Movimiento::create([
                'usuarios_id' => Auth::id() ?? 1,
                'tipo' => "Pago Registrado: {$socio->accion} - \${$validated['monto']} USD",
                'fecha' => Carbon::now(),
            ]);

            // 4. Notificar a Administración
            \App\Models\Notificacion::enviarARoles(['admin'], [
                'tipo' => 'pago_recibido',
                'titulo' => "💵 Cobro Registrado (\${$validated['monto']} USD)",
                'mensaje' => "Recibido pago de {$socio->nombre_completo} (Acción {$socio->accion}).",
                'icono' => 'payments',
                'color' => 'emerald',
                'enlace' => route('pagos.receipt', $nuevoPago),
            ]);

            return $nuevoPago;
        });

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'pago_id' => $pago->id,
                'nuevo_saldo' => $socio->saldo,
            ]);
        }

        return redirect()->route('pagos.receipt', $pago)
            ->with('success', '¡Pago registrado exitosamente!');
    }

    public function receipt(Pago $pago, BcvScraperService $bcvService)
    {
        $pago->load('socio');
        $tasa = $bcvService->obtenerTasa();
        $montoBs = $pago->monto * $tasa;

        return view('pagos.receipt', compact('pago', 'tasa', 'montoBs'));
    }
}
