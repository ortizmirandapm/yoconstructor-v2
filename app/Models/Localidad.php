<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\LocalidadFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Localidad extends Model
{
    /** @use HasFactory<LocalidadFactory> */
    use HasFactory;

    protected $table = 'localidades';

    protected $fillable = [
        'nombre',
        'provincia_id',
        'codigo_postal',
    ];

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class);
    }
}
