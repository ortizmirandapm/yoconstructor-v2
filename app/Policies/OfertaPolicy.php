<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Oferta;
use App\Models\User;

final class OfertaPolicy
{
    public function update(User $user, Oferta $oferta): bool
    {
        return $user->empresa?->id === $oferta->empresa_id;
    }

    public function delete(User $user, Oferta $oferta): bool
    {
        return $user->empresa?->id === $oferta->empresa_id;
    }
}
