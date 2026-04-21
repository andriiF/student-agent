<?php

namespace App\Http\Middleware;

use App\Repositories\FrontendUserRepository;
use App\Services\JwtService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateFrontendJwt
{
    public function __construct(
        private readonly JwtService $jwtService,
        private readonly FrontendUserRepository $frontendUserRepository
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if ($token === null) {
            return $this->unauthorized('Missing bearer token.');
        }

        $userId = $this->jwtService->getUserIdFromToken($token);

        if ($userId === null) {
            return $this->unauthorized('Invalid or expired token.');
        }

        $user = $this->frontendUserRepository->findById($userId);

        if ($user === null) {
            return $this->unauthorized('User not found.');
        }

        $request->attributes->set('frontend_user', $user);

        return $next($request);
    }

    private function unauthorized(string $message): JsonResponse
    {
        return response()->json(['message' => $message], Response::HTTP_UNAUTHORIZED);
    }
}
