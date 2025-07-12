<?php

namespace App\Traits;

use App\Models\BitacoraMovimiento;
use Illuminate\Support\Facades\Auth;

trait RegistraBitacora
{
    public function registrarMovimiento(string $accion, string $permiso, $modelo = null)
    {
        BitacoraMovimiento::create([
            'user_id'   => Auth::id(),
            'accion'    => $accion,
            'permiso'   => $permiso,
            'modelo'    => $modelo ? get_class($modelo) : null,
            'modelo_id' => $modelo->id ?? null,
        ]);
    }
}
