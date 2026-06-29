<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Modalidad;
use App\Enums\OfertaEstado;
use App\Enums\TipoContrato;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Oferta extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ofertas';

    protected $fillable = [
        'empresa_id',
        'rubro_id',
        'titulo',
        'descripcion',
        'requisitos',
        'salario_min',
        'salario_max',
        'tipo_contrato',
        'modalidad',
        'provincia_id',
        'localidad_id',
        'experiencia_requerida',
        'fecha_vencimiento',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_vencimiento' => 'date',
            'salario_min' => 'decimal:2',
            'salario_max' => 'decimal:2',
            'estado' => OfertaEstado::class,
            'tipo_contrato' => TipoContrato::class,
            'modalidad' => Modalidad::class,
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function rubro(): BelongsTo
    {
        return $this->belongsTo(Rubro::class);
    }

    public function especialidades(): BelongsToMany
    {
        return $this->belongsToMany(Especialidad::class, 'oferta_especialidad')
            ->withPivot('es_principal');
    }

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class);
    }

    public function localidad(): BelongsTo
    {
        return $this->belongsTo(Localidad::class);
    }

    public function postulaciones(): HasMany
    {
        return $this->hasMany(Postulacion::class);
    }
}
