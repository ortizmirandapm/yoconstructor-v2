<?php

declare(strict_types=1);

namespace App\Enums;

enum TipoContrato: string
{
    case TiempoCompleto = 'Tiempo completo';
    case MedioTiempo = 'Medio tiempo';
    case PorProyecto = 'Por proyecto';
    case Pasantia = 'Pasantía';

    public function label(): string
    {
        return $this->value;
    }

    /** @return array<int, string> */
    public static function values(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }
}
