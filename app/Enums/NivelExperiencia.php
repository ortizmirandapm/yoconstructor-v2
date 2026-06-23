<?php

declare(strict_types=1);

namespace App\Enums;

enum NivelExperiencia: string
{
    case Basico = 'Basico';
    case Intermedio = 'Intermedio';
    case Avanzado = 'Avanzado';
    case Experto = 'Experto';

    public function label(): string
    {
        return match ($this) {
            self::Basico => 'Básico',
            self::Intermedio => 'Intermedio',
            self::Avanzado => 'Avanzado',
            self::Experto => 'Experto',
        };
    }
}
