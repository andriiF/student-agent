<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\FrontendUser;
use App\Repositories\FrontendUserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class FrontendUserController extends Controller
{
    public function __construct(private readonly FrontendUserRepository $frontendUserRepository) {}

    public function index(): Response
    {
        return Inertia::render('settings/FrontendUsers', [
            'frontendUsers' => $this->frontendUserRepository->paginate(20)->through(fn (FrontendUser $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at?->toISOString(),
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('frontend_users', 'email')],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $this->frontendUserRepository->create($data);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Frontend user created.')]);

        return back();
    }

    public function update(Request $request, FrontendUser $frontendUser): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('frontend_users', 'email')->ignore($frontendUser->id, 'id'),
            ],
        ]);

        $this->frontendUserRepository->update($frontendUser, $data);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Frontend user updated.')]);

        return back();
    }

    public function destroy(FrontendUser $frontendUser): RedirectResponse
    {
        $this->frontendUserRepository->delete($frontendUser);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Frontend user deleted.')]);

        return back();
    }
}
