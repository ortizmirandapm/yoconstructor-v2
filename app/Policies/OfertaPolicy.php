<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserTipo;
use App\Models\Oferta;
use App\Models\User;

final class OfertaPolicy
{
    public function before(User $user): ?bool
    {
        if ($user->tipo === UserTipo::Admin) {
            return true;
        }

        return null;
    }

    public function view(User $user, Oferta $oferta): bool
    {
        return $user->empresa?->id === $oferta->empresa_id;
    }

    public function create(User $user): bool
    {
        return $user->empresa !== null;
    }

    public function update(User $user, Oferta $oferta): bool
    {
        return $user->empresa?->id === $oferta->empresa_id;
    }

    public function delete(User $user, Oferta $oferta): bool
    {
        return $user->empresa?->id === $oferta->empresa_id;
    }
}
