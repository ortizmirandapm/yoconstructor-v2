<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\RubroFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Rubro extends Model
{
    /** @use HasFactory<RubroFactory> */
    use HasFactory;

    protected $table = 'rubros';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'estado',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'estado' => 'boolean',
        ];
    }

    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class, 'rubro_id');
    }
}
