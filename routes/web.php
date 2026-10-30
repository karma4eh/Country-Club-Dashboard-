<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\VisitaController;

// Autenticación & Redirección según rol
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->esVigilante()) {
            return redirect()->route('visitas.index');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware('auth')->group(function () {

    // 1. Dashboard Ejecutivo & BCV (Admin y Secretaria)
    Route::middleware('role:admin,secretaria')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/api/bcv/sync', [DashboardController::class, 'syncBcv'])->name('bcv.sync');
    });

    // 2. Afiliación & Edición de Socios (Admin y Secretaria)
    Route::middleware('role:admin,secretaria')->group(function () {
        Route::get('/socios/create', [SocioController::class, 'create'])->name('socios.create');
        Route::post('/socios', [SocioController::class, 'store'])->name('socios.store');
        Route::put('/socios/{socio}', [SocioController::class, 'update'])->name('socios.update');
    });

    // 3. Directorio & Consulta de Carnets de Socios (Todos: Admin, Secretaria, Vigilante)
    Route::get('/socios', [SocioController::class, 'index'])->name('socios.index');
    Route::get('/socios/{socio}', [SocioController::class, 'show'])->name('socios.show');
    Route::get('/api/socios/search', [SocioController::class, 'searchJson'])->name('socios.search.json');

    // 4. Caja, Cobros & Finanzas (Exclusivo Admin y Secretaria - Vigilante bloqueado)
    Route::middleware('role:admin,secretaria')->group(function () {
        Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
        Route::get('/pagos/create', [PagoController::class, 'create'])->name('pagos.create');
        Route::post('/pagos', [PagoController::class, 'store'])->name('pagos.store');
        Route::get('/pagos/calcular-deuda', [PagoController::class, 'calcularDeuda'])->name('pagos.calcular');
        Route::get('/pagos/{pago}/recibo', [PagoController::class, 'receipt'])->name('pagos.receipt');
    });

    // 5. Bitácora de Auditoría Interna (Exclusivo Admin y Secretaria)
    Route::middleware('role:admin,secretaria')->group(function () {
        Route::get('/movimientos', [MovimientoController::class, 'index'])->name('movimientos.index');
    });

    // 6. Control de Acceso, Garita, Salidas y Minutas (Todos los roles tienen acceso a garita)
    Route::get('/visitas', [VisitaController::class, 'index'])->name('visitas.index');
    Route::post('/visitas', [VisitaController::class, 'store'])->name('visitas.store');
    Route::post('/visitas/{visita}/salida', [VisitaController::class, 'marcarSalida'])->name('visitas.salida');
    Route::post('/visitas/novedades', [VisitaController::class, 'storeNovedad'])->name('visitas.novedades.store');
    Route::get('/api/visitas/buscar-socio', [VisitaController::class, 'buscarSocio'])->name('visitas.buscar');
    Route::delete('/visitas/{visita}', [VisitaController::class, 'destroy'])->name('visitas.destroy');
});
