<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre_completo',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'usuarios_id');
    }

    public function visitas()
    {
        return $this->hasMany(Visita::class, 'usuarios_id');
    }

    public function novedadesGarita()
    {
        return $this->hasMany(NovedadGarita::class, 'usuarios_id');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'usuarios_id')->orderBy('created_at', 'desc');
    }

    public function notificacionesNoLeidas()
    {
        return $this->hasMany(Notificacion::class, 'usuarios_id')->where('leida', false)->orderBy('created_at', 'desc');
    }

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function esSecretaria(): bool
    {
        return $this->rol === 'secretaria';
    }

    public function esVigilante(): bool
    {
        return $this->rol === 'vigilante';
    }
}
