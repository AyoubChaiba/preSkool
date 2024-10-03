<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user)
    {
        return $user->role === "admin";
    }

    public function viewTeacher(User $user)
    {
        return $user->role === "teacher";
    }

    public function viewStudent(User $user){
        return $user->role === "student";
    }

    public function viewParent(User $user){
        return $user->role === "parent";
    }

    public function view(User $user, User $model)
    {
        return $user->role === "admin" || $user->role === "teacher";
    }

    public function update(User $user, User $model)
    {
        return $user->role === "admin";
    }

    public function delete(User $user, User $model)
    {
        return $user->role === "admin";
    }
}

