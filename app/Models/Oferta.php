<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Postulacion;

class Oferta extends Model
{
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

    protected $casts = [
        'fecha_vencimiento' => 'date',
        'salario_min'       => 'decimal:2',
        'salario_max'       => 'decimal:2',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function especialidades()
    {
        return $this->belongsToMany(Especialidad::class, 'oferta_especialidad')
            ->withPivot('es_principal');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }

    public function localidad()
    {
        return $this->belongsTo(Localidad::class);
    }

    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class);
    }
}
