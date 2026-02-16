<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * 
     * Estos son los campos que se pueden asignar masivamente (create, update).
     * Añadimos 'is_admin' para poder crear/actualizar administradores.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'foto_perfil',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     * 
     * Los casts convierten automáticamente los valores de la BD.
     * 'is_admin' => 'boolean' convierte 0/1 a false/true en PHP.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Relación con direcciones
     */
    public function direcciones()
    {
        return $this->hasMany(Direccion::class);
    }

    /**
     * Método helper para verificar si el usuario es admin.
     * Uso: $user->isAdmin() o auth()->user()->isAdmin()
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }
}
