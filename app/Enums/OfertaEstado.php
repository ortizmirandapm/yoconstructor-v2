<?php

declare(strict_types=1);

namespace App\Enums;

enum OfertaEstado: string
{
    case Activa = 'Activa';
    case Pausada = 'Pausada';
    case Cerrada = 'Cerrada';
    case Borrador = 'Borrador';
    case Inactiva = 'Inactiva';

    public function label(): string
    {
        return match ($this) {
            self::Activa => 'Activa',
            self::Pausada => 'Pausada',
            self::Cerrada => 'Cerrada',
            self::Borrador => 'Borrador',
            self::Inactiva => 'Inactiva',
        };
    }

    public function esActiva(): bool
    {
        return $this === self::Activa;
    }
}
