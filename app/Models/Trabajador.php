<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TrabajadorFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Trabajador extends Model
{
    /** @use HasFactory<TrabajadorFactory> */
    use HasFactory;

    protected $table = 'trabajadores';

    protected $fillable = [
        'user_id',
        'dni',
        'nombre',
        'apellido',
        'descripcion',
        'telefono',
        'anios_experiencia',
        'localidad_preferencia_id',
        'provincia_preferencia_id',
        'imagen_perfil',
        'curriculum_pdf',
        'domicilio',
        'fecha_nacimiento',
        'nombre_titulo',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'anios_experiencia' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function especialidades(): BelongsToMany
    {
        return $this->belongsToMany(Especialidad::class, 'trabajador_especialidad')
            ->withPivot('nivel_experiencia', 'es_principal')
            ->withTimestamps();
    }

    public function postulaciones(): HasMany
    {
        return $this->hasMany(Postulacion::class);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('nombre', 'like', "%{$term}%")
                ->orWhere('apellido', 'like', "%{$term}%")
                ->orWhereHas('user', fn ($u) => $u->where('email', 'like', "%{$term}%")
                    ->orWhere('name', 'like', "%{$term}%"));
        });
    }

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class, 'provincia_preferencia_id');
    }

    public function localidad(): BelongsTo
    {
        return $this->belongsTo(Localidad::class, 'localidad_preferencia_id');
    }
}
