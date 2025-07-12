<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Casts\TimeCast;

class Descansos extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'dias',
        'inicio',
        'fin'
    ];

     protected function casts(): array
    {
        return [
            'dias'=>'array',
            'inicio' => TimeCast::class,
            'fin' => TimeCast::class,
        ];
    }

    public function getFormattedDaysAttribute(): string
    {
        return implode(', ', array_map('ucfirst', $this->dias ?? []));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
