<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';
    public $timestamps = false; // Maneja fecha_pago directamente

    protected $fillable = [
        'socios_id',
        'descripcion',
        'monto',
        'fecha_pago',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'datetime',
    ];

    public function socio()
    {
        return $this->belongsTo(Socio::class, 'socios_id');
    }
}
