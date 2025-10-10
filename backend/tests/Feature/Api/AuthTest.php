<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_register_login_me_logout_flow(): void
    {
        // Register
        $register = $this->postJson('/api/v1/auth/register', [
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertCreated()->json();

        $this->assertArrayHasKey('token', $register);

        // Login
        $login = $this->postJson('/api/v1/auth/login', [
            'email' => 'alice@example.com',
            'password' => 'password123',
        ])->assertOk()->json();

        $token = $login['token'];

        // Me
        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonStructure(['id', 'name', 'email']);

        // Logout
        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/auth/logout')
            ->assertOk();
    }
}
