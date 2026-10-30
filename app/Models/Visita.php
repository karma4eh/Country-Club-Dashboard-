<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Visita extends Model
{
    use HasFactory;

    protected $table = 'visitas';
    public $timestamps = false; // Manejado por fecha_entrada y fecha_salida

    protected $fillable = [
        'socios_id',
        'usuarios_id',
        'fecha_entrada',
        'fecha_salida',
        'invitados',
        'observaciones',
    ];

    protected $casts = [
        'fecha_entrada' => 'datetime',
        'fecha_salida' => 'datetime',
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

    public function getEnClubAttribute(): bool
    {
        return is_null($this->fecha_salida) && $this->fecha_entrada && $this->fecha_entrada->isToday();
    }

    public function getTiempoPermanenciaAttribute(): string
    {
        if (!$this->fecha_entrada) {
            return 'N/A';
        }

        $fin = $this->fecha_salida ?? Carbon::now();
        $minutos = $this->fecha_entrada->diffInMinutes($fin);

        $horas = intdiv($minutos, 60);
        $minsRestantes = $minutos % 60;

        if ($horas > 0) {
            return "{$horas}h {$minsRestantes}m";
        }
        return "{$minsRestantes} min";
    }
}
