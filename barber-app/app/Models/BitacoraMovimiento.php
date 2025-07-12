<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BitacoraMovimiento extends Model
{
    protected $fillable = ['user_id', 'accion', 'permiso', 'modelo', 'modelo_id', 'fecha'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
