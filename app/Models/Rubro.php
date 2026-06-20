<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'estado',
        'orden',
    ];

    public function ofertas()
    {
        return $this->hasMany(Oferta::class, 'rubro_id');
    }
}
