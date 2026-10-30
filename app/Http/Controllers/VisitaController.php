<?php

namespace App\Http\Controllers;

use App\Models\Visita;
use App\Models\Socio;
use App\Models\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VisitaController extends Controller
{
    /**
     * Centro de Control de Visitas y Registro de Acceso en Garita
     */
    public function index(Request $request)
    {
        $year = (int) $request->input('year', Carbon::now()->year);
        $month = (int) $request->input('month', Carbon::now()->month);

        if ($month < 1 || $month > 12) {
            $month = Carbon::now()->month;
        }

        $fechaSeleccionada = Carbon::createFromDate($year, $month, 1);
        $mesAnterior = (clone $fechaSeleccionada)->subMonth();
        $mesSiguiente = (clone $fechaSeleccionada)->addMonth();

        // Métricas
        $hoy = Carbon::today();
        $visitasHoy = Visita::whereDate('fecha_entrada', $hoy)->count();

        $queryMes = Visita::whereYear('fecha_entrada', $year)
                          ->whereMonth('fecha_entrada', $month);

        $totalVisitasMes = (clone $queryMes)->count();
        $totalInvitadosMes = (clone $queryMes)->sum('invitados');
        $sociosUnicosMes = (clone $queryMes)->distinct('socios_id')->count('socios_id');

        // Historial de visitas para la tabla del mes
        $visitas = Visita::with(['socio', 'vigilante'])
            ->whereYear('fecha_entrada', $year)
            ->whereMonth('fecha_entrada', $month)
            ->orderBy('fecha_entrada', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Días del mes con visitas (para calendario / heatmap)
        $diasConVisitas = Visita::whereYear('fecha_entrada', $year)
            ->whereMonth('fecha_entrada', $month)
            ->selectRaw('DAY(fecha_entrada) as dia, count(*) as total')
            ->groupBy('dia')
            ->pluck('total', 'dia')
            ->toArray();

        return view('visitas.index', compact(
            'visitas',
            'year',
            'month',
            'fechaSeleccionada',
            'mesAnterior',
            'mesSiguiente',
            'visitasHoy',
            'totalVisitasMes',
            'totalInvitadosMes',
            'sociosUnicosMes',
            'diasConVisitas'
        ));
    }

    /**
     * Búsqueda en vivo de socio para la Garita (mediante QR, Cédula o Nombre)
     */
    public function buscarSocio(Request $request)
    {
        $input = trim($request->input('query', ''));

        if (empty($input)) {
            return response()->json(['error' => 'Ingrese una cédula, número de acción o escanee el código QR.'], 400);
        }

        // Si el QR viene en formato JSON o URL o texto plano
        $cedula = $input;
        $accion = null;

        if (str_starts_with($input, '{') && str_ends_with($input, '}')) {
            $data = json_decode($input, true);
            if (isset($data['cedula'])) $cedula = $data['cedula'];
            if (isset($data['accion'])) $accion = $data['accion'];
        } elseif (preg_match('/cedula=([0-9]+)/i', $input, $m)) {
            $cedula = $m[1];
        }

        $socio = Socio::where('cedula', $cedula)
            ->orWhere('accion', $input)
            ->orWhere('accion', $accion)
            ->orWhere(function ($q) use ($input) {
                $q->where('nombre', 'like', "%{$input}%")
                  ->orWhere('apellido', 'like', "%{$input}%");
            })
            ->first();

        if (!$socio) {
            return response()->json([
                'encontrado' => false,
                'mensaje' => 'No se encontró ningún socio con los datos suministrados.'
            ], 404);
        }

        $mesActual = Carbon::now()->month;
        $anoActual = Carbon::now()->year;
        $visitasEsteMes = $socio->totalVisitasMes($anoActual, $mesActual);
        $ultimaVisita = $socio->visitas()->first();

        return response()->json([
            'encontrado' => true,
            'id' => $socio->id,
            'nombre_completo' => $socio->nombre_completo,
            'cedula' => $socio->cedula,
            'accion' => $socio->accion,
            'estado' => $socio->estado,
            'activo' => $socio->estado === 'activo',
            'saldo' => (float) $socio->saldo,
            'condicion' => $socio->condicion,
            'es_moroso' => $socio->es_moroso,
            'meses_deuda' => $socio->meses_deuda,
            'avatar_url' => $socio->avatar_url,
            'visitas_este_mes' => $visitasEsteMes,
            'ultima_visita' => $ultimaVisita ? $ultimaVisita->fecha_entrada->format('d/m/Y h:i A') : 'Primera visita registrada',
            'qr_data' => $socio->qr_code_data,
        ]);
    }

    /**
     * Registro de ingreso de visita por Garita
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'socios_id' => 'required|exists:socios,id',
            'invitados' => 'nullable|integer|min:0|max:50',
            'observaciones' => 'nullable|string|max:255',
        ]);

        $socio = Socio::findOrFail($validated['socios_id']);

        $visita = Visita::create([
            'socios_id' => $socio->id,
            'usuarios_id' => Auth::id(),
            'fecha_entrada' => Carbon::now(),
            'invitados' => $validated['invitados'] ?? 0,
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        Movimiento::create([
            'usuarios_id' => Auth::id() ?? 1,
            'tipo' => substr("Ingreso Garita: {$socio->nombre_completo} (Acción {$socio->accion}) +{$visita->invitados} inv.", 0, 250),
            'fecha' => Carbon::now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'mensaje' => "¡Ingreso concedido para {$socio->nombre_completo}! (Acción: {$socio->accion})",
                'visita_id' => $visita->id,
                'fecha' => $visita->fecha_entrada->format('d/m/Y h:i A'),
            ]);
        }

        return redirect()->route('visitas.index')
            ->with('success', "¡Ingreso concedido para {$socio->nombre_completo}! (Acción: {$socio->accion})");
    }

    /**
     * Eliminar registro de visita en caso de error
     */
    public function destroy(Visita $visita)
    {
        $visita->delete();
        return back()->with('success', 'Registro de visita eliminado.');
    }
}
