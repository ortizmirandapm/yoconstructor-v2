<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

final readonly class ConfiguracionService
{
    public function toggleVisibilidad(User $user, bool $visible): void
    {
        $user->update(['visible_busqueda' => $visible]);
    }

    public function eliminarCuenta(User $user): void
    {
        $user->update(['estado' => false]);
        Auth::logout();
    }

    public function eliminarCuentaEmpresa(User $user): void
    {
        $empresa = $user->empresa;
        if ($empresa) {
            $empresa->ofertas()->update(['estado' => 'Cerrada']);
            $empresa->update(['estado' => 'inactivo']);
        }
        $user->update(['estado' => false]);
        Auth::logout();
    }

    public function cambiarPassword(User $user, string $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }
}
