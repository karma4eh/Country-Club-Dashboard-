<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Socio extends Model
{
    use HasFactory;

    protected $table = 'socios';

    protected $fillable = [
        'nombre',
        'apellido',
        'direccion',
        'cedula',
        'numero',
        'correo',
        'accion',
        'estado',
        'saldo',
        'ultimo_mes_pagado',
        'fecha_nacimiento',
        'socio_desde',
        'vencimiento',
        'imagen_perfil',
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
        'ultimo_mes_pagado' => 'datetime',
        'fecha_nacimiento' => 'date',
        'socio_desde' => 'date',
        'vencimiento' => 'date',
    ];

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'socios_id')->orderBy('fecha_pago', 'desc');
    }

    public function visitas()
    {
        return $this->hasMany(Visita::class, 'socios_id')->orderBy('fecha_entrada', 'desc');
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function getEsMorosoAttribute(): bool
    {
        return $this->saldo < 0;
    }

    public function getCondicionAttribute(): string
    {
        return $this->saldo < 0 ? 'MOROSO' : 'SOLVENTE';
    }

    public function getMesesDeudaAttribute(): int
    {
        if ($this->saldo >= 0) {
            return 0;
        }

        $cuota = (float) config('club.cuota_mensual', 10.00);
        if ($cuota <= 0) $cuota = 10.00;

        return (int) ceil(abs($this->saldo) / $cuota);
    }

    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->imagen_perfil)) {
            return asset($this->imagen_perfil);
        }

        $nombre = urlencode($this->nombre_completo);
        return "https://ui-avatars.com/api/?name={$nombre}&background=1f2937&color=fbbf24";
    }
}
