<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(protected UserRepository $userRepository)
    {
    }

    public function getPaginatedUsers(?string $search, int $perPage = 15): LengthAwarePaginator
    {
        return User::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function createUser(array $data): User
    {
        return $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function updateUser(User $user, array $data): void
    {
        $attributes = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $attributes['password'] = Hash::make($data['password']);
        }

        $this->userRepository->update($user, $attributes);
    }

    public function deleteUser(User $user): void
    {
        $this->userRepository->delete($user);
    }
}
