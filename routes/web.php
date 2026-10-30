<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\MovimientoController;

// Autenticación
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/api/bcv/sync', [DashboardController::class, 'syncBcv'])->name('bcv.sync');

    // Socios
    Route::get('/socios', [SocioController::class, 'index'])->name('socios.index');
    Route::get('/socios/create', [SocioController::class, 'create'])->name('socios.create');
    Route::post('/socios', [SocioController::class, 'store'])->name('socios.store');
    Route::get('/socios/{socio}', [SocioController::class, 'show'])->name('socios.show');
    Route::put('/socios/{socio}', [SocioController::class, 'update'])->name('socios.update');
    Route::get('/api/socios/search', [SocioController::class, 'searchJson'])->name('socios.search.json');

    // Pagos
    Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::get('/pagos/create', [PagoController::class, 'create'])->name('pagos.create');
    Route::post('/pagos', [PagoController::class, 'store'])->name('pagos.store');
    Route::get('/pagos/calcular-deuda', [PagoController::class, 'calcularDeuda'])->name('pagos.calcular');
    Route::get('/pagos/{pago}/recibo', [PagoController::class, 'receipt'])->name('pagos.receipt');

    // Movimientos / Auditoría
    Route::get('/movimientos', [MovimientoController::class, 'index'])->name('movimientos.index');
});
