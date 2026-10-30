<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (empty($roles) || in_array($request->user()->rol, $roles)) {
            return $next($request);
        }

        // Si un vigilante intenta entrar a módulos administrativos o de caja
        if ($request->user()->esVigilante()) {
            return redirect()->route('visitas.index')
                ->with('error', 'Acceso denegado: Su rol de Vigilante no tiene permisos para módulos administrativos o financieros.');
        }

        abort(403, 'No tiene permisos para acceder a esta sección.');
    }
}
