<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Paquetes;
use App\Casts\TimeCast;

class Citas extends Model
{
    protected $fillable =[
        'cliente_id', 
        'barbero_id', 
        'paquete_id',
        'dia', 
        'fecha',
        'hora'
    ];

    public function cliente() {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function barbero() {
        return $this->belongsTo(User::class, 'barbero_id');
    }

    public function paquete() {
        return $this->belongsTo(Paquetes::class, 'paquete_id');
    }

    protected function casts(): array
    {
        return [
            'hora' => TimeCast::class,
        ];
    }
}
