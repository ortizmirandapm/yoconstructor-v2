<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'especialidades';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function trabajadores()
    {
        return $this->belongsToMany(Trabajador::class, 'trabajador_especialidad')
            ->withPivot('nivel_experiencia', 'es_principal');
    }

    public function ofertas()
    {
        return $this->belongsToMany(Oferta::class, 'oferta_especialidad')
            ->withPivot('es_principal');
    }
}
