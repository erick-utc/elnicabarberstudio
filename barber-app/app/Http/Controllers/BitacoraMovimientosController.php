<?php

namespace App\Http\Controllers;

use App\Models\BitacoraMovimiento;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BitacoraMovimientosController extends Controller implements HasMiddleware
{
    public static function middleware():array {
        return [
            new Middleware('permission:ver bitacoras', only: ['index']),
        ];
    }

    public function index() {
        $bitacora = BitacoraMovimiento::with('user')->orderByDesc('fecha')->paginate(10);
        return view('bitacoras.movimientos', compact('bitacora'));
    }
}
