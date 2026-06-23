<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ProvinciaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Provincia extends Model
{
    /** @use HasFactory<ProvinciaFactory> */
    use HasFactory;

    protected $table = 'provincias';

    protected $fillable = [
        'nombre',
        'codigo',
        'region',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'estado' => 'boolean',
        ];
    }

    public function localidades(): HasMany
    {
        return $this->hasMany(Localidad::class);
    }
}
