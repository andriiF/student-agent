<?php

namespace App\Services;

use App\Models\FrontendUser;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Throwable;

class JwtService
{
    public function generateToken(FrontendUser $user): string
    {
        $issuedAt = time();
        $ttl = (int) env('JWT_TTL', 3600);

        $payload = [
            'iss' => config('app.url'),
            'iat' => $issuedAt,
            'exp' => $issuedAt + $ttl,
            'sub' => (string) $user->getKey(),
            'email' => $user->email,
        ];

        return JWT::encode($payload, $this->secret(), 'HS256');
    }

    public function getUserIdFromToken(string $token): ?string
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret(), 'HS256'));

            return isset($decoded->sub) ? (string) $decoded->sub : null;
        } catch (ExpiredException) {
            return null;
        } catch (Throwable) {
            return null;
        }
    }

    private function secret(): string
    {
        return (string) env('JWT_SECRET', config('app.key'));
    }
}
