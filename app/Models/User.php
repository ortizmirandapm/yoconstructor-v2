<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserTipo;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

final class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo',
        'estado',
        'visible_busqueda',
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
            'visible_busqueda' => 'boolean',
            'estado' => 'boolean',
            'tipo' => UserTipo::class,
        ];
    }

    public function empresa(): HasOne
    {
        return $this->hasOne(Empresa::class);
    }

    public function trabajador(): HasOne
    {
        return $this->hasOne(Trabajador::class);
    }
}
