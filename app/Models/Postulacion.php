<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PostulacionEstado;
use Database\Factories\PostulacionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Postulacion extends Model
{
    /** @use HasFactory<PostulacionFactory> */
    use HasFactory;

    protected $table = 'postulaciones';

    protected $fillable = [
        'oferta_id',
        'trabajador_id',
        'mensaje',
        'cv_adjunto',
        'notas_empresa',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'estado' => PostulacionEstado::class,
        ];
    }

    public function oferta(): BelongsTo
    {
        return $this->belongsTo(Oferta::class);
    }

    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class);
    }
}
