<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BitacoraAcceso;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BitacoraAccesosController extends Controller implements HasMiddleware
{
    public static function middleware():array {
        return [
            new Middleware('permission:ver bitacoras', only: ['index']),
        ];
    }

    public function index() {
        $bitacora = BitacoraAcceso::with('user')->latest('fecha')->paginate(10);
        return view('bitacoras.accesos', compact('bitacora'));
    }
}
