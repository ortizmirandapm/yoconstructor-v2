<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Enums\OfertaEstado;
use App\Models\Oferta;

final readonly class AdminOfertaService
{
    public function cambiarEstado(Oferta $oferta): string
    {
        $nuevoEstado = $oferta->estado === OfertaEstado::Activa
            ? OfertaEstado::Pausada
            : OfertaEstado::Activa;

        $oferta->update(['estado' => $nuevoEstado]);

        return $nuevoEstado === OfertaEstado::Activa
            ? 'Oferta activada correctamente.'
            : 'Oferta pausada correctamente.';
    }

    public function eliminar(Oferta $oferta): void
    {
        $oferta->delete();
    }
}
