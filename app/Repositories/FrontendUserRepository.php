<?php

namespace App\Repositories;

use App\Models\FrontendUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

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

    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return FrontendUser::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function get(): Collection
    {
        return FrontendUser::query()->select('uuid', 'firstname', 'lastname', 'email', 'phone')->get();
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
