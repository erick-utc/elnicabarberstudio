<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BitacoraAcceso extends Model
{
    protected $fillable = ['user_id', 'tipo', 'ip', 'navegador', 'fecha'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
