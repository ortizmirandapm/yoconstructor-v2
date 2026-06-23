<?php

declare(strict_types=1);

namespace App\Enums;

enum OfertaEstado: string
{
    case Activa = 'Activa';
    case Pausada = 'Pausada';
    case Cerrada = 'Cerrada';
    case Borrador = 'Borrador';

    public function label(): string
    {
        return match ($this) {
            self::Activa => 'Activa',
            self::Pausada => 'Pausada',
            self::Cerrada => 'Cerrada',
            self::Borrador => 'Borrador',
        };
    }

    public function esActiva(): bool
    {
        return $this === self::Activa;
    }
}
