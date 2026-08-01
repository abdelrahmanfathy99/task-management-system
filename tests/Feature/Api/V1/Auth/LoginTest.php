<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use PHPUnit\Framework\Attributes\Test;

class LoginTest extends AuthTestCase
{
    #[Test]
    public function it_logs_in_a_user_successfully(): void
    {
        // Arrange
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $payload = $this->validLoginPayload();

        // Act
        $response = $this->postJson(self::LOGIN_URL, $payload);

        // Assert
        $response
            ->assertOk()
            ->assertJsonPath('message', 'Login successful')
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'token',
                ],
            ])
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.name', $user->name)
            ->assertJsonPath('data.email', $user->email)
            ->assertJsonMissingPath('data.password');

        $this->assertNotEmpty($response->json('data.token'));
        $this->assertDatabaseCount('personal_access_tokens', 1);
        $this->assertTrue(
            PersonalAccessToken::query()->where('tokenable_id', $user->id)->exists()
        );
    }

    #[Test]
    public function it_replaces_existing_token_on_login(): void
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $user->createToken('user-auth');
        $this->assertDatabaseCount('personal_access_tokens', 1);

        // Act
        $response = $this->postJson(self::LOGIN_URL, $this->validLoginPayload());

        // Assert
        $response->assertOk();
        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    #[Test]
    public function it_fails_to_login_with_missing_fields(): void
    {
        // Arrange
        $payload = [];

        // Act
        $response = $this->postJson(self::LOGIN_URL, $payload);

        // Assert
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'password']);
    }

    #[Test]
    public function it_fails_to_login_with_invalid_credentials(): void
    {
        // Arrange
        User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $payload = $this->validLoginPayload([
            'password' => 'wrong-password',
        ]);

        // Act
        $response = $this->postJson(self::LOGIN_URL, $payload);

        // Assert
        $response
            ->assertStatus(500)
            ->assertJsonStructure([
                'error' => [
                    'message',
                    'code',
                ],
            ])
            ->assertJsonPath('error.message', 'Invalid credentials.');

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    #[Test]
    public function it_fails_to_login_when_user_does_not_exist(): void
    {
        // Arrange
        $payload = $this->validLoginPayload([
            'email' => 'missing@example.com',
        ]);

        // Act
        $response = $this->postJson(self::LOGIN_URL, $payload);

        // Assert
        $response
            ->assertStatus(500)
            ->assertJsonPath('error.message', 'Invalid credentials.');

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
