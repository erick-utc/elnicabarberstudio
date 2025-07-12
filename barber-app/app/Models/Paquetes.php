<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use NumberFormatter;

class Paquetes extends Model
{
    protected $fillable = [
        'nombre',
        'precio',
        'descripcion',
        'desactivado'
    ];

    public function getPrecioColonesAttribute()
    {
        $formatter = new NumberFormatter('es_CR', NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($this->precio, 'CRC');
    }
}
