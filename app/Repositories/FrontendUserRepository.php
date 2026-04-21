<?php

namespace App\Repositories;

use App\Models\FrontendUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FrontendUserRepository
{
    public function findById(string $id): ?FrontendUser
    {
        return FrontendUser::query()->find($id);
    }

    public function findByEmail(string $email): ?FrontendUser
    {
        return FrontendUser::query()
            ->where('email', $email)
            ->first();
    }

    public function create(array $attributes): FrontendUser
    {
        return FrontendUser::query()->create($attributes);
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return FrontendUser::query()
            ->latest()
            ->paginate($perPage);
    }

    public function update(FrontendUser $user, array $attributes): bool
    {
        return $user->update($attributes);
    }

    public function delete(FrontendUser $user): ?bool
    {
        return $user->delete();
    }
}
