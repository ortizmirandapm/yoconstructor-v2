<?php

declare(strict_types=1);

namespace App\Enums;

enum PostulacionEstado: string
{
    case Pendiente = 'Pendiente';
    case Revisada = 'Revisada';
    case Entrevista = 'Entrevista';
    case Aceptada = 'Aceptada';
    case Rechazada = 'Rechazada';

    public function label(): string
    {
        return match ($this) {
            self::Pendiente => 'Pendiente',
            self::Revisada => 'Revisada',
            self::Entrevista => 'Entrevista',
            self::Aceptada => 'Aceptada',
            self::Rechazada => 'Rechazada',
        };
    }

    public function notificable(): bool
    {
        return in_array($this, [self::Aceptada, self::Rechazada, self::Entrevista, self::Revisada], true);
    }
}
