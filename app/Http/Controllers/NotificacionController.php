<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    /**
     * Obtener notificaciones del usuario autenticado (API JSON)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $notificaciones = $user->notificaciones()
            ->take(15)
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'tipo' => $n->tipo,
                    'titulo' => $n->titulo,
                    'mensaje' => $n->mensaje,
                    'icono' => $n->icono,
                    'color' => $n->color,
                    'enlace' => $n->enlace,
                    'leida' => $n->leida,
                    'hace_tiempo' => $n->created_at ? $n->created_at->diffForHumans() : '',
                    'fecha' => $n->created_at ? $n->created_at->format('d/m/Y h:i A') : '',
                ];
            });

        $noLeidas = $user->notificacionesNoLeidas()->count();

        return response()->json([
            'success' => true,
            'no_leidas_count' => $noLeidas,
            'notificaciones' => $notificaciones,
        ]);
    }

    /**
     * Marcar una notificación individual como leída
     */
    public function marcarLeida(Request $request, Notificacion $notificacion)
    {
        if ($notificacion->usuarios_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $notificacion->update(['leida' => true]);

        return response()->json([
            'success' => true,
            'no_leidas_count' => Auth::user()->notificacionesNoLeidas()->count(),
        ]);
    }

    /**
     * Marcar todas las notificaciones del usuario como leídas
     */
    public function marcarTodasLeidas(Request $request)
    {
        Notificacion::where('usuarios_id', Auth::id())
            ->where('leida', false)
            ->update(['leida' => true]);

        return response()->json([
            'success' => true,
            'no_leidas_count' => 0,
        ]);
    }
}
