<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use App\Models\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        ]);

        $validated['saldo'] = 0.00;
        $validated['socio_desde'] = Carbon::today();

        $socio = Socio::create($validated);

        Movimiento::create([
            'usuarios_id' => Auth::id() ?? 1,
            'tipo' => 'Registro de Socio: ' . $socio->accion,
            'fecha' => Carbon::now(),
        ]);

        return redirect()->route('socios.show', $socio)
            ->with('success', '¡Socio registrado exitosamente!');
    }

    public function show(Socio $socio)
    {
        $socio->load(['pagos', 'visitas']);
        return view('socios.show', compact('socio'));
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
