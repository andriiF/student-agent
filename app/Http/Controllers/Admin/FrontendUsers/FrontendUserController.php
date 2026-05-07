<?php

namespace App\Http\Controllers\Admin\FrontendUsers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FrontendUserRequest;
use App\Http\Resources\FrontendUserResource;
use App\Models\FrontendUser;
use App\Services\FrontendUserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FrontendUserController extends Controller
{
    public function __construct(protected FrontendUserService $frontendUserService)
    {
    }

    public function index(Request $request): Response
    {
        $frontendUsers = $this->frontendUserService->getPaginatedFrontendUsers($request->input('search'));

        return Inertia::render('frontend-users/Index', [
            'frontendUsers' => FrontendUserResource::collection($frontendUsers),
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('frontend-users/Create');
    }

    public function store(FrontendUserRequest $request): RedirectResponse
    {
        $this->frontendUserService->createFrontendUser($request->validated());

        return redirect()->route('frontendUsers.index')
            ->with('success', 'Frontend user created successfully.');
    }

    public function edit(FrontendUser $frontendUser): Response
    {
        return Inertia::render('frontend-users/Edit', [
            'frontendUser' => new FrontendUserResource($frontendUser),
        ]);
    }

    public function update(FrontendUserRequest $request, FrontendUser $frontendUser): RedirectResponse
    {
        $this->frontendUserService->updateFrontendUser($frontendUser, $request->validated());

        return redirect()->route('frontendUsers.index')
            ->with('success', 'Frontend user updated successfully.');
    }

    public function destroy(FrontendUser $frontendUser): RedirectResponse
    {
        $this->frontendUserService->deleteFrontendUser($frontendUser);

        return redirect()->route('frontendUsers.index')
            ->with('success', 'Frontend user deleted successfully.');
    }
}
