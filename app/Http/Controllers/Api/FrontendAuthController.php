<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\FrontendUserRepository;
use App\Services\JwtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class FrontendAuthController extends Controller
{
    public function __construct(
        private readonly JwtService $jwtService,
        private readonly FrontendUserRepository $frontendUserRepository
    ) {}

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('frontend_users', 'email')],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = $this->frontendUserRepository->create($data);

        return response()->json([
            'user' => $user,
            'token' => $this->jwtService->generateToken($user),
            'token_type' => 'Bearer',
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = $this->frontendUserRepository->findByEmail($data['email']);

        if ($user === null || ! Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'user' => $user,
            'token' => $this->jwtService->generateToken($user),
            'token_type' => 'Bearer',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->attributes->get('frontend_user');

        return response()->json(['user' => $user]);
    }
}
