<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\UserRole;

class UserPolicy
{
    public function viewAny(User $user)
    {
        return $user->role === UserRole::Admin->value;
    }

    public function view(User $user, User $model)
    {
        return $user->role === UserRole::Admin->value || $user->role === UserRole::Teacher->value;
    }

    public function update(User $user, User $model)
    {
        return $user->role === UserRole::Admin->value;
    }

    public function delete(User $user, User $model)
    {
        return $user->role === UserRole::Admin->value;
    }
}

