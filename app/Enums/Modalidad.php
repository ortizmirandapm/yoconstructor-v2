<?php

declare(strict_types=1);

namespace App\Enums;

enum Modalidad: string
{
    case Presencial = 'Presencial';
    case Remoto = 'Remoto';
    case Hibrido = 'Híbrido';

    public function label(): string
    {
        return $this->value;
    }
}
