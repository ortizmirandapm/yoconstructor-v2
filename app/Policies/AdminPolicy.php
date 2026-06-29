<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserTipo;
use App\Models\User;

final class AdminPolicy
{
    public function before(User $user): ?bool
    {
        return $user->tipo === UserTipo::Admin ? null : false;
    }

    public function update(User $user, User $target): bool
    {
        return $user->id !== $target->id;
    }

    public function delete(User $user, User $target): bool
    {
        return $user->id !== $target->id;
    }
}
