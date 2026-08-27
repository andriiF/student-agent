<?php

namespace Tests\Feature\Api;

use App\Models\FrontendUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithFrontendApi;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use InteractsWithFrontendApi;
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'email' => 'student@example.com',
            'password' => 'password123',
        ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'user' => ['uuid', 'email'],
                'token',
                'token_type',
            ])
            ->assertJsonPath('token_type', 'Bearer')
            ->assertJsonPath('user.email', 'student@example.com');

        $this->assertDatabaseHas('frontend_users', [
            'email' => 'student@example.com',
        ]);
    }

    public function test_register_requires_unique_email(): void
    {
        FrontendUser::factory()->create(['email' => 'taken@example.com']);

        $this->postJson('/api/auth/register', [
            'email' => 'taken@example.com',
            'password' => 'password123',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_can_login(): void
    {
        FrontendUser::factory()->create([
            'email' => 'student@example.com',
            'password' => 'password123',
        ]);

        $this->postJson('/api/auth/login', [
            'email' => 'student@example.com',
            'password' => 'password123',
        ])->assertOk()
            ->assertJsonStructure(['user', 'token', 'token_type'])
            ->assertJsonPath('token_type', 'Bearer');
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        FrontendUser::factory()->create([
            'email' => 'student@example.com',
            'password' => 'password123',
        ]);

        $this->postJson('/api/auth/login', [
            'email' => 'student@example.com',
            'password' => 'wrong-password',
        ])->assertUnauthorized()
            ->assertJsonPath('errors.email.0', 'Invalid credentials.');
    }

    public function test_me_requires_authentication(): void
    {
        $this->getJson('/api/auth/me')
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Missing bearer token.');
    }

    public function test_authenticated_user_can_fetch_me(): void
    {
        $user = $this->actingAsFrontend();

        $this->getJson('/api/auth/me')
            ->assertOk()
            ->assertJsonPath('user.uuid', $user->uuid)
            ->assertJsonPath('user.email', $user->email);
    }

    public function test_authenticated_user_can_fetch_progress(): void
    {
        $this->actingAsFrontend();

        $this->getJson('/api/auth/getProgress')
            ->assertOk()
            ->assertJson([]);
    }
}
