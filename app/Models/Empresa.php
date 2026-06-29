<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\EmpresaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Empresa extends Model
{
    /** @use HasFactory<EmpresaFactory> */
    use HasFactory;

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
        'perfil_publico',
    ];

    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class);
    }

    public function getLogoAttribute(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return 'storage/uploads/logos/'.$value;
    }

    protected function casts(): array
    {
        return [
            'perfil_publico' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rubro(): BelongsTo
    {
        return $this->belongsTo(Rubro::class);
    }

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class);
    }
}
