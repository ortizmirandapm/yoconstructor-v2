<?php

declare(strict_types=1);

namespace App\Enums;

enum UserTipo: string
{
    case Admin = 'admin';
    case Empresa = 'empresa';
    case Trabajador = 'trabajador';
    case Reclutador = 'reclutador';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrador',
            self::Empresa => 'Empresa',
            self::Trabajador => 'Trabajador',
            self::Reclutador => 'Reclutador',
        };
    }
}
