<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'cedula',
        'idrol',
        'intentos_fallidos',
        'bloqueado_hasta',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'bloqueado_hasta' => 'datetime',
    ];

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idrol', 'idrol');
    }

    public function estaBloqueado()
    {
        if ($this->bloqueado_hasta && $this->bloqueado_hasta > now()) {
            return true;
        }
        return false;
    }

    public function minutosRestantesBloqueo()
    {
        if ($this->bloqueado_hasta) {
            return now()->diffInMinutes($this->bloqueado_hasta, false);
        }
        return 0;
    }
}