<?php

declare(strict_types=1);

namespace Tests\Helpers;

use App\Models\Empresa;
use App\Models\Trabajador;
use App\Models\User;

trait CreatesUsers
{
    protected function createUser(string $tipo, array $userData = []): User
    {
        return User::factory()->create(array_merge(['tipo' => $tipo], $userData));
    }

    protected function createAdmin(array $userData = []): User
    {
        return $this->createUser('admin', $userData);
    }

    protected function createAdminUser(array $userData = []): User
    {
        return $this->createUser('admin', $userData);
    }

    /** @return array{0: User, 1: Empresa} */
    protected function createEmpresa(array $userData = []): array
    {
        $user = User::factory()->create(array_merge(['tipo' => 'empresa'], $userData));
        $empresa = Empresa::factory()->create(['user_id' => $user->id]);

        return [$user, $empresa];
    }

    /** @return array{0: User, 1: Trabajador} */
    protected function createTrabajador(array $userData = []): array
    {
        $user = User::factory()->create(array_merge(['tipo' => 'trabajador'], $userData));
        $trabajador = Trabajador::factory()->create(['user_id' => $user->id]);

        return [$user, $trabajador];
    }
}
