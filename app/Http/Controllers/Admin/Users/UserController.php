<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }

    public function index(Request $request): Response
    {
        $users = $this->userService->getPaginatedUsers($request->input('search'));

        return Inertia::render('users/Index', [
            'users' => $users,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('users/Create');
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $this->userService->createUser($request->validated());

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user): Response
    {
        return Inertia::render('users/Show', [
            'user' => $user,
        ]);
    }

    public function edit(User $user): Response
    {
        return Inertia::render('users/Edit', [
            'user' => $user,
        ]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $this->userService->updateUser($user, $request->validated());

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->userService->deleteUser($user);

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
