<?php

namespace App\Http\Controllers;

use App\Models\Visita;
use App\Models\Socio;
use App\Models\Movimiento;
use App\Models\NovedadGarita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VisitaController extends Controller
{
    /**
     * Centro de Control de Visitas, Aforo y Garita de Vigilancia
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

        $hoy = Carbon::today();

        // Visitas activas en este instante (personas actualmente dentro del club hoy sin marcar salida)
        $visitasActivasHoy = Visita::with(['socio', 'vigilante'])
            ->whereDate('fecha_entrada', $hoy)
            ->whereNull('fecha_salida')
            ->orderBy('fecha_entrada', 'desc')
            ->get();

        $totalSociosDentro = $visitasActivasHoy->count();
        $totalAforoHoy = $visitasActivasHoy->sum(function ($v) {
            return 1 + (int) ($v->invitados ?? 0);
        });

        // Métricas generales
        $visitasHoy = Visita::whereDate('fecha_entrada', $hoy)->count();

        $queryMes = Visita::whereYear('fecha_entrada', $year)
                          ->whereMonth('fecha_entrada', $month);

        $totalVisitasMes = (clone $queryMes)->count();
        $totalInvitadosMes = (clone $queryMes)->sum('invitados');
        $sociosUnicosMes = (clone $queryMes)->distinct('socios_id')->count('socios_id');

        // Historial paginado de visitas para la tabla del mes
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

        // Libro de Novedades de Guardia (últimos 10 registros)
        $novedadesRecientes = NovedadGarita::with('vigilante')
            ->orderBy('fecha', 'desc')
            ->take(15)
            ->get();

        return view('visitas.index', compact(
            'visitas',
            'year',
            'month',
            'fechaSeleccionada',
            'mesAnterior',
            'mesSiguiente',
            'visitasHoy',
            'visitasActivasHoy',
            'totalSociosDentro',
            'totalAforoHoy',
            'totalVisitasMes',
            'totalInvitadosMes',
            'sociosUnicosMes',
            'diasConVisitas',
            'novedadesRecientes'
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

        // Verificar si el socio se encuentra actualmente en las instalaciones
        $visitaActiva = Visita::where('socios_id', $socio->id)
            ->whereDate('fecha_entrada', Carbon::today())
            ->whereNull('fecha_salida')
            ->first();

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
            'esta_en_club' => !is_null($visitaActiva),
            'visita_activa_id' => $visitaActiva ? $visitaActiva->id : null,
            'hora_ingreso_hoy' => $visitaActiva ? $visitaActiva->fecha_entrada->format('h:i A') : null,
            'ultima_visita' => $ultimaVisita ? $ultimaVisita->fecha_entrada->format('d/m/Y h:i A') : 'Primera visita registrada',
            'qr_data' => $socio->qr_code_data,
            'vigilante_actual' => Auth::user()->nombre_completo ?? 'Personal de Seguridad',
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
        $vigilante = Auth::user();
        $vigilanteNombre = $vigilante ? $vigilante->nombre_completo : 'Personal de Seguridad';

        // Prevención de doble clic: evitar registrar la misma visita en menos de 20 segundos
        $visitaReciente = Visita::where('socios_id', $socio->id)
            ->where('fecha_entrada', '>=', Carbon::now()->subSeconds(20))
            ->first();

        if ($visitaReciente) {
            $mensaje = "¡Ingreso autorizado previamente para {$socio->nombre_completo}! (Acción: {$socio->accion}) — Registrado por el Oficial: {$vigilanteNombre}";
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'mensaje' => $mensaje,
                    'visita_id' => $visitaReciente->id,
                    'fecha' => $visitaReciente->fecha_entrada ? $visitaReciente->fecha_entrada->format('d/m/Y h:i A') : '',
                    'vigilante' => $vigilanteNombre,
                ]);
            }
            return redirect()->route('visitas.index')->with('success', $mensaje);
        }

        $visita = Visita::create([
            'socios_id' => $socio->id,
            'usuarios_id' => Auth::id(),
            'fecha_entrada' => Carbon::now(),
            'fecha_salida' => null,
            'invitados' => $validated['invitados'] ?? 0,
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        Movimiento::create([
            'usuarios_id' => Auth::id() ?? 1,
            'tipo' => substr("Ingreso Garita: {$socio->nombre_completo} (Acción {$socio->accion}) +{$visita->invitados} inv. — Registrado por: {$vigilanteNombre}", 0, 250),
            'fecha' => Carbon::now(),
        ]);

        $mensaje = "¡Ingreso autorizado para {$socio->nombre_completo}! (Acción: {$socio->accion}) — Registrado por el Oficial: {$vigilanteNombre}";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'mensaje' => $mensaje,
                'visita_id' => $visita->id,
                'fecha' => $visita->fecha_entrada->format('d/m/Y h:i A'),
                'vigilante' => $vigilanteNombre,
            ]);
        }

        return redirect()->route('visitas.index')->with('success', $mensaje);
    }

    /**
     * Marcar salida del socio (Check-out de Garita)
     */
    public function marcarSalida(Request $request, Visita $visita)
    {
        if ($visita->fecha_salida) {
            $msg = 'Esta visita ya tenía registrada su salida a las ' . $visita->fecha_salida->format('h:i A');
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'mensaje' => $msg], 400);
            }
            return back()->with('warning', $msg);
        }

        $visita->update([
            'fecha_salida' => Carbon::now(),
        ]);

        $vigilante = Auth::user();
        $vigilanteNombre = $vigilante ? $vigilante->nombre_completo : 'Personal de Seguridad';
        $socioNombre = $visita->socio ? $visita->socio->nombre_completo : 'Socio';
        $accion = $visita->socio ? $visita->socio->accion : '';

        Movimiento::create([
            'usuarios_id' => Auth::id() ?? 1,
            'tipo' => substr("Salida Garita: {$socioNombre} (Acción {$accion}) tras {$visita->tiempo_permanencia} — Verificado por: {$vigilanteNombre}", 0, 250),
            'fecha' => Carbon::now(),
        ]);

        $msg = "🚪 Salida registrada para {$socioNombre}. Tiempo en el club: {$visita->tiempo_permanencia} — Verificado por el Oficial: {$vigilanteNombre}";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'mensaje' => $msg,
                'fecha_salida' => $visita->fecha_salida->format('d/m/Y h:i A'),
                'tiempo_permanencia' => $visita->tiempo_permanencia,
                'vigilante' => $vigilanteNombre,
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Asentar novedad en el Libro Digital de Minutas de Garita
     */
    public function storeNovedad(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|string|in:Ronda Perimetral,Incidente,Acceso Vehicular,Entrega / Correspondencia,Mantenimiento,Cambio de Guardia,General',
            'titulo' => 'required|string|max:100',
            'descripcion' => 'required|string|max:1000',
        ]);

        $vigilanteNombre = Auth::user()->nombre_completo ?? 'Personal de Seguridad';

        // Prevención de doble clic / registros duplicados idénticos en menos de 20 segundos
        $reciente = NovedadGarita::where('usuarios_id', Auth::id())
            ->where('titulo', $validated['titulo'])
            ->where('tipo', $validated['tipo'])
            ->where('fecha', '>=', Carbon::now()->subSeconds(20))
            ->first();

        if ($reciente) {
            $msg = "🛡️ Novedad ya registrada previamente en la minuta por el Oficial {$vigilanteNombre}.";
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'mensaje' => $msg,
                    'novedad' => [
                        'id' => $reciente->id,
                        'tipo' => $reciente->tipo,
                        'titulo' => $reciente->titulo,
                        'descripcion' => $reciente->descripcion,
                        'fecha' => $reciente->fecha ? $reciente->fecha->format('d/m/Y h:i A') : '',
                        'vigilante' => $vigilanteNombre,
                    ],
                ]);
            }
            return back()->with('success', $msg);
        }

        $novedad = NovedadGarita::create([
            'usuarios_id' => Auth::id(),
            'tipo' => $validated['tipo'],
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'fecha' => Carbon::now(),
        ]);

        $vigilanteNombre = Auth::user()->nombre_completo ?? 'Personal de Seguridad';

        Movimiento::create([
            'usuarios_id' => Auth::id() ?? 1,
            'tipo' => substr("Minuta Garita: [{$novedad->tipo}] {$novedad->titulo} — Oficial: {$vigilanteNombre}", 0, 250),
            'fecha' => Carbon::now(),
        ]);

        $msg = "🛡️ Novedad asentada en el libro de garita con éxito por el Oficial {$vigilanteNombre}.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'mensaje' => $msg,
                'novedad' => [
                    'id' => $novedad->id,
                    'tipo' => $novedad->tipo,
                    'titulo' => $novedad->titulo,
                    'descripcion' => $novedad->descripcion,
                    'fecha' => $novedad->fecha->format('d/m/Y h:i A'),
                    'vigilante' => $vigilanteNombre,
                ],
            ]);
        }

        return back()->with('success', $msg);
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
