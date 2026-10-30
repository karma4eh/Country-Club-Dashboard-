<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    use HasFactory;

    protected $table = 'visitas';
    public $timestamps = false; // Manejado por fecha_entrada

    protected $fillable = [
        'socios_id',
        'usuarios_id',
        'fecha_entrada',
        'invitados',
        'observaciones',
    ];

    protected $casts = [
        'fecha_entrada' => 'datetime',
        'invitados' => 'integer',
    ];

    public function socio()
    {
        return $this->belongsTo(Socio::class, 'socios_id');
    }

    public function vigilante()
    {
        return $this->belongsTo(User::class, 'usuarios_id');
    }
}
