<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    use HasFactory;

    protected $table = 'visitas';
    public $timestamps = false; // Usa fecha_entrada

    protected $fillable = [
        'socios_id',
        'fecha_entrada',
    ];

    protected $casts = [
        'fecha_entrada' => 'datetime',
    ];

    public function socio()
    {
        return $this->belongsTo(Socio::class, 'socios_id');
    }
}
