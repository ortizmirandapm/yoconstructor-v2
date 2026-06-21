<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';

    protected $fillable = [
        'user_id',
        'nombre_empresa',
        'razon_social',
        'descripcion',
        'cuit',
        'rubro_id',
        'provincia_id',
        'telefono',
        'email_contacto',
        'logo',
        'domicilio',
        'estado',
    ];

    public function ofertas()
    {
        return $this->hasMany(Oferta::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rubro()
    {
        return $this->belongsTo(Rubro::class);
    }
}
