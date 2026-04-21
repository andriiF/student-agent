<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function create(array $attributes): User
    {
        return User::query()->create($attributes);
    }

    public function save(User $user): bool
    {
        return $user->save();
    }

    public function update(User $user, array $attributes): bool
    {
        return $user->update($attributes);
    }

    public function delete(User $user): ?bool
    {
        return $user->delete();
    }
}
