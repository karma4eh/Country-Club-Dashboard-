<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->esVigilante()) {
                return redirect()->route('visitas.index');
            }
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $request->session()->forget('url.intended');

            try {
                Movimiento::create([
                    'usuarios_id' => Auth::id(),
                    'tipo' => 'Inicio de Sesión',
                    'fecha' => Carbon::now(),
                ]);
            } catch (\Throwable $e) {
                // No bloquear el acceso si falla el registro secundario de bitácora
            }

            if (Auth::user()->esVigilante()) {
                return redirect()->route('visitas.index');
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
