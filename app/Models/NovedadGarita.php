<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NovedadGarita extends Model
{
    use HasFactory;

    protected $table = 'novedades_garita';

    protected $fillable = [
        'usuarios_id',
        'tipo',
        'titulo',
        'descripcion',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function vigilante()
    {
        return $this->belongsTo(User::class, 'usuarios_id');
    }
}
