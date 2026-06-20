<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function especialidades()
    {
        return $this->belongsToMany(Especialidad::class, 'trabajador_especialidad')
                    ->withPivot('nivel_experiencia', 'es_principal');
    }

    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class);
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_preferencia_id');
    }
}
