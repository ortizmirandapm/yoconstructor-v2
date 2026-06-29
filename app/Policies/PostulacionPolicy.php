<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Postulacion;
use App\Models\User;

final class PostulacionPolicy
{
    public function view(User $user, Postulacion $postulacion): bool
    {
        return $user->empresa?->id === $postulacion->oferta->empresa_id;
    }

    public function update(User $user, Postulacion $postulacion): bool
    {
        return $user->empresa?->id === $postulacion->oferta->empresa_id;
    }
}
