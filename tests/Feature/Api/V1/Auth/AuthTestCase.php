<?php

namespace Tests\Feature\Api\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class AuthTestCase extends TestCase
{
    use RefreshDatabase;

    protected const REGISTER_URL = '/api/v1/auth/register';

    protected const LOGIN_URL = '/api/v1/auth/login';

    protected const LOGOUT_URL = '/api/v1/auth/logout';

    /**
     * @return array{name: string, email: string, password: string, password_confirmation: string}
     */
    protected function validRegistrationPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ], $overrides);
    }

    /**
     * @return array{email: string, password: string}
     */
    protected function validLoginPayload(array $overrides = []): array
    {
        return array_merge([
            'email' => 'john@example.com',
            'password' => 'password123',
        ], $overrides);
    }
}
