<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasaDolar extends Model
{
    use HasFactory;

    protected $table = 'tasa_dolar';

    protected $fillable = [
        'tasa',
        'fecha_actualizacion',
        'scraped_today',
    ];

    protected $casts = [
        'tasa' => 'decimal:4',
        'fecha_actualizacion' => 'datetime',
        'scraped_today' => 'boolean',
    ];

    public static function tasaActual(): float
    {
        $ultima = static::orderBy('fecha_actualizacion', 'desc')->first();
        return $ultima ? (float) $ultima->tasa : 889.2553;
    }
}
