<?php

namespace App\Policies;

use App\Models\Car;
use App\Models\User;

class CarPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Car $car): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isClient();
    }

    public function update(User $user, Car $car): bool
    {
        return $user->id === $car->user_id;
    }

    public function delete(User $user, Car $car): bool
    {
        return $user->id === $car->user_id;
    }

    public function approve(User $user, Car $car): bool
    {
        return $user->isAdmin();
    }

    public function reject(User $user, Car $car): bool
    {
        return $user->isAdmin();
    }

    public function feature(User $user, Car $car): bool
    {
        return $user->isAdmin() || ($user->id === $car->user_id && $car->isApproved());
    }
}
