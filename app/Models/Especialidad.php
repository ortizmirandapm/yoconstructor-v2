<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\EspecialidadFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Especialidad extends Model
{
    /** @use HasFactory<EspecialidadFactory> */
    use HasFactory;

    protected $table = 'especialidades';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'estado' => 'boolean',
        ];
    }

    public function trabajadores(): BelongsToMany
    {
        return $this->belongsToMany(Trabajador::class, 'trabajador_especialidad')
            ->withPivot('nivel_experiencia', 'es_principal')
            ->withTimestamps();
    }

    public function ofertas(): BelongsToMany
    {
        return $this->belongsToMany(Oferta::class, 'oferta_especialidad')
            ->withPivot('es_principal');
    }
}
