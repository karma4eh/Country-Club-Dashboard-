<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use Illuminate\Http\Request;

class MovimientoController extends Controller
{
    public function index()
    {
        $movimientos = Movimiento::with('usuario')->orderBy('fecha', 'desc')->paginate(25);
        return view('movimientos.index', compact('movimientos'));
    }
}
