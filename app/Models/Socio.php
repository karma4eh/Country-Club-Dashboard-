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

        $iniciales = strtoupper(
            mb_substr(trim((string) $this->nombre), 0, 1) .
            mb_substr(trim((string) $this->apellido), 0, 1)
        ) ?: 'CC';

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80">'
             . '<rect width="80" height="80" fill="#1f2937"/>'
             . '<text x="50%" y="52%" dominant-baseline="central" text-anchor="middle" fill="#fbbf24" font-family="sans-serif" font-weight="700" font-size="30">'
             . htmlspecialchars($iniciales, ENT_QUOTES, 'UTF-8')
             . '</text></svg>';

        return 'data:image/svg+xml;utf8,' . rawurlencode($svg);
    }

    public function getQrCodeDataAttribute(): string
    {
        return json_encode([
            'tipo' => 'SOCIO_COUNTRY_CLUB',
            'id' => $this->id,
            'cedula' => $this->cedula,
            'accion' => $this->accion,
            'nombre' => $this->nombre_completo,
            'estado' => $this->estado,
        ]);
    }

    public function visitasDelMes(int $year, int $month)
    {
        return $this->visitas()
            ->whereYear('fecha_entrada', $year)
            ->whereMonth('fecha_entrada', $month)
            ->orderBy('fecha_entrada', 'desc')
            ->get();
    }

    public function totalVisitasMes(int $year, int $month): int
    {
        return $this->visitas()
            ->whereYear('fecha_entrada', $year)
            ->whereMonth('fecha_entrada', $month)
            ->count();
    }
}
