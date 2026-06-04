<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Postulacion extends Model
{
    protected $table = 'postulaciones';

    protected $fillable = [
        'oferta_id',
        'trabajador_id',
        'mensaje',
        'estado',
    ];

    public function oferta()
    {
        return $this->belongsTo(Oferta::class);
    }

    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class);
    }
}