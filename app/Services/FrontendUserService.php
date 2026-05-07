<?php

namespace App\Services;

use App\Models\FrontendUser;
use App\Repositories\FrontendUserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class FrontendUserService
{
    public function __construct(protected FrontendUserRepository $frontendUserRepository)
    {
    }

    public function getPaginatedFrontendUsers(?string $search, int $perPage = 15): LengthAwarePaginator
    {
        return $this->frontendUserRepository->paginate($search, $perPage);
    }

    public function get(): Collection
    {
        return $this->frontendUserRepository->get();
    }

    public function createFrontendUser(array $data): FrontendUser
    {
        return $this->frontendUserRepository->create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
        ]);
    }

    public function updateFrontendUser(FrontendUser $user, array $data): void
    {
        $attributes = [
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ];

        if (!empty($data['password'])) {
            $attributes['password'] = Hash::make($data['password']);
        }

        $this->frontendUserRepository->update($user, $attributes);
    }

    public function deleteFrontendUser(FrontendUser $user): void
    {
        $this->frontendUserRepository->delete($user);
    }
}
