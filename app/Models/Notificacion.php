<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    protected $fillable = [
        'usuarios_id',
        'tipo',
        'titulo',
        'mensaje',
        'icono',
        'color',
        'enlace',
        'leida',
    ];

    protected $casts = [
        'leida' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuarios_id');
    }

    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }

    public function scopeRecientes($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Enviar notificación a usuarios según sus roles
     */
    public static function enviarARoles(array $roles, array $data): int
    {
        $usuarios = User::whereIn('rol', $roles)->get();
        $enviados = 0;

        foreach ($usuarios as $u) {
            self::create([
                'usuarios_id' => $u->id,
                'tipo' => $data['tipo'] ?? 'general',
                'titulo' => $data['titulo'] ?? 'Notificación',
                'mensaje' => $data['mensaje'] ?? '',
                'icono' => $data['icono'] ?? 'notifications',
                'color' => $data['color'] ?? 'amber',
                'enlace' => $data['enlace'] ?? null,
                'leida' => false,
            ]);
            $enviados++;
        }

        return $enviados;
    }

    /**
     * Enviar notificación a un usuario específico
     */
    public static function enviarAUsuario(int $usuarioId, array $data): self
    {
        return self::create([
            'usuarios_id' => $usuarioId,
            'tipo' => $data['tipo'] ?? 'general',
            'titulo' => $data['titulo'] ?? 'Notificación',
            'mensaje' => $data['mensaje'] ?? '',
            'icono' => $data['icono'] ?? 'notifications',
            'color' => $data['color'] ?? 'amber',
            'enlace' => $data['enlace'] ?? null,
            'leida' => false,
        ]);
    }
}
