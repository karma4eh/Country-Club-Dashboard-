<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use App\Models\Movimiento;
use App\Models\Visita;
use App\Mail\SocioBienvenidaMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SocioController extends Controller
{
    public function index(Request $request)
    {
        $query = Socio::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('cedula', 'like', "%{$search}%")
                  ->orWhere('accion', 'like', "%{$search}%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        $socios = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('socios.index', compact('socios'));
    }

    public function searchJson(Request $request)
    {
        $search = trim($request->input('query', ''));

        $query = Socio::query();
        if ($search !== '') {
            $query->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('cedula', 'like', "%{$search}%")
                  ->orWhere('accion', 'like', "%{$search}%");
        }

        $socios = $query->orderBy('id', 'desc')->take(20)->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'nombre_completo' => $s->nombre_completo,
                'cedula' => $s->cedula,
                'accion' => $s->accion,
                'estado' => $s->estado,
                'saldo' => (float) $s->saldo,
                'avatar' => $s->avatar_url,
            ];
        });

        return response()->json($socios);
    }

    public function create()
    {
        return view('socios.create');
    }

    public function store(Request $request)
    {
        // Prevención de doble clic en registro de socio
        $cedulaInput = $request->input('cedula');
        if ($cedulaInput) {
            $socioReciente = Socio::where('cedula', $cedulaInput)
                ->where('created_at', '>=', Carbon::now()->subSeconds(20))
                ->first();
            if ($socioReciente) {
                return redirect()->route('socios.show', $socioReciente)
                    ->with('info', "¡El socio {$socioReciente->nombre_completo} (Acción {$socioReciente->accion}) ya fue registrado exitosamente!");
            }
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'cedula' => 'required|string|max:20|unique:socios,cedula',
            'accion' => 'required|string|max:50|unique:socios,accion',
            'correo' => 'required|email|max:150|unique:socios,correo',
            'numero' => 'nullable|string|max:30',
            'direccion' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo',
            'fecha_nacimiento' => 'nullable|date',
        ], [
            'cedula.unique' => 'Ya existe un socio registrado con la cédula de identidad V-:input. Verifique en el directorio.',
            'cedula.required' => 'El número de cédula de identidad es obligatorio.',
            'accion.unique' => 'El número de acción :input ya se encuentra asignado a otro socio del club. Ingrese una acción disponible.',
            'accion.required' => 'Debe asignar un número de acción oficial al socio.',
            'correo.unique' => 'El correo electrónico (:input) ya está registrado con otra membresía.',
            'correo.required' => 'Debe ingresar un correo electrónico para el envío de credenciales y bienvenida.',
            'correo.email' => 'El correo electrónico ingresado no tiene un formato válido (ej: nombre@correo.com).',
            'nombre.required' => 'El nombre del socio titular es obligatorio.',
            'apellido.required' => 'El apellido del socio titular es obligatorio.',
            'estado.required' => 'Debe seleccionar el estado de la afiliación (Activo o Inactivo).',
        ]);

        $validated['saldo'] = 0.00;
        $validated['socio_desde'] = Carbon::today();

        $socio = Socio::create($validated);

        // Envío de correo de bienvenida con Número de Acción
        $emailEnviado = false;
        try {
            if (!empty($socio->correo)) {
                Mail::to($socio->correo)->send(new SocioBienvenidaMail($socio));
                $emailEnviado = true;
            }
        } catch (\Throwable $e) {
            Log::warning("No se pudo enviar el correo de bienvenida al socio {$socio->id}: " . $e->getMessage());
        }

        // Enlace para enviar mensaje de bienvenida por WhatsApp
        $waUrl = null;
        if (!empty($socio->numero)) {
            $numLimpio = preg_replace('/[^0-9]/', '', $socio->numero);
            if (str_starts_with($numLimpio, '0')) {
                $numLimpio = '58' . substr($numLimpio, 1); // Prefijo Venezuela
            }
            $textoWa = "¡Bienvenido a Country Club, {$socio->nombre}! Le confirmamos su afiliación exitosa. Su Número de Acción oficial es: *{$socio->accion}*. Puede ingresar a las instalaciones presentando su cédula V-{$socio->cedula} o su carnet digital.";
            $waUrl = "https://wa.me/{$numLimpio}?text=" . urlencode($textoWa);
        }

        Movimiento::create([
            'usuarios_id' => Auth::id() ?? 1,
            'tipo' => substr("Registro de Socio: Acción #{$socio->accion}" . ($emailEnviado ? " (Bienvenida enviada a {$socio->correo})" : ""), 0, 250),
            'fecha' => Carbon::now(),
        ]);

        // Notificar a Administración, Secretaría y Garita (para que el oficial reconozca al nuevo socio)
        \App\Models\Notificacion::enviarARoles(['admin', 'secretaria', 'vigilante'], [
            'tipo' => 'socio_nuevo',
            'titulo' => "👤 Nueva Afiliación: {$socio->nombre_completo}",
            'mensaje' => "Se ha emitido la Acción #{$socio->accion} (Cédula: V-{$socio->cedula}). Socio autorizado para acceso al club.",
            'icono' => 'person_add',
            'color' => 'emerald',
            'enlace' => route('socios.show', $socio),
        ]);

        return redirect()->route('socios.show', $socio)
            ->with('success', "¡Socio afiliado exitosamente con Acción #{$socio->accion}!" . ($emailEnviado ? " Se ha enviado el mensaje de bienvenida a {$socio->correo}." : ""))
            ->with('wa_url', $waUrl);
    }

    public function show(Request $request, Socio $socio)
    {
        $socio->load(['pagos']);

        // Control Mensual de Visitas Interactivo
        $year = (int) $request->input('year', Carbon::now()->year);
        $month = (int) $request->input('month', Carbon::now()->month);

        if ($month < 1 || $month > 12) {
            $month = Carbon::now()->month;
        }

        $fechaSeleccionada = Carbon::createFromDate($year, $month, 1);
        $mesAnterior = (clone $fechaSeleccionada)->subMonth();
        $mesSiguiente = (clone $fechaSeleccionada)->addMonth();

        // Visitas de este socio en el mes seleccionado
        $visitasMes = $socio->visitas()
            ->with('vigilante')
            ->whereYear('fecha_entrada', $year)
            ->whereMonth('fecha_entrada', $month)
            ->orderBy('fecha_entrada', 'desc')
            ->get();

        $totalVisitasMes = $visitasMes->count();
        $totalInvitadosMes = $visitasMes->sum('invitados');

        // Días en que el socio asistió al club en este mes (para calendario visual)
        $diasAsistidos = $visitasMes->pluck('fecha_entrada')->map(function ($date) {
            return (int) $date->format('j');
        })->unique()->values()->toArray();

        // Respuesta parcial vía AJAX para cambio de mes sin recargar la página
        if ($request->has('ajax_control_visitas') || $request->header('X-Partial-Visitas')) {
            return view('socios.partials.control_visitas', compact(
                'socio',
                'visitasMes',
                'year',
                'month',
                'fechaSeleccionada',
                'mesAnterior',
                'mesSiguiente',
                'totalVisitasMes',
                'totalInvitadosMes',
                'diasAsistidos'
            ));
        }

        return view('socios.show', compact(
            'socio',
            'visitasMes',
            'year',
            'month',
            'fechaSeleccionada',
            'mesAnterior',
            'mesSiguiente',
            'totalVisitasMes',
            'totalInvitadosMes',
            'diasAsistidos'
        ));
    }

    public function update(Request $request, Socio $socio)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'correo' => 'required|email|max:150|unique:socios,correo,' . $socio->id,
            'numero' => 'nullable|string|max:30',
            'direccion' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo',
        ], [
            'correo.unique' => 'El correo electrónico (:input) ya está asignado a otro socio.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'El formato del correo electrónico no es válido.',
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
        ]);

        $socio->update($validated);

        Movimiento::create([
            'usuarios_id' => Auth::id() ?? 1,
            'tipo' => 'Actualización de Datos: ' . $socio->accion,
            'fecha' => Carbon::now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('socios.show', $socio)
            ->with('success', 'Datos actualizados correctamente.');
    }
}
