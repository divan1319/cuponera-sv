<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'id_rol', 'estado', 'birth_date'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function empresa()
    {
        return $this->hasOne(Empresa::class, 'user_id');
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'user_id');
    }

    /**
     * Usuarios con rol Admin y cuenta activa (puede haber más de uno).
     */
    public function scopeAdministradores(Builder $query): Builder
    {
        return $query
            ->whereHas('rol', fn (Builder $q) => $q->where('nombre', 'Admin'))
            ->where('estado', 'Activo');
    }
}
