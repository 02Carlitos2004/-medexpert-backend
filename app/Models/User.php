<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'role_id',
        'learning_mode', 'age', 'gender', 'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'age' => 'integer',
            'activo' => 'boolean',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function perfilPaciente()
    {
        return $this->hasOne(PerfilPaciente::class);
    }

    public function perfilMedico()
    {
        return $this->hasOne(PerfilMedico::class);
    }

    public function perfilEnfermera()
    {
        return $this->hasOne(PerfilEnfermera::class);
    }

    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }

    public function logs()
    {
        return $this->hasMany(LogIA::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role?->slug === 'admin';
    }

    public function isProfessional(): bool
    {
        return $this->role === 'professional' || $this->role?->slug === 'medico';
    }

    public function hasRole(string $slug): bool
    {
        return $this->role?->slug === $slug;
    }
}
