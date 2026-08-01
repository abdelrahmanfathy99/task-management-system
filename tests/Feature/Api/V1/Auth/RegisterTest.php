<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use PHPUnit\Framework\Attributes\Test;

class RegisterTest extends AuthTestCase
{
    #[Test]
    public function it_registers_a_user_successfully(): void
    {
        // Arrange
        $payload = $this->validRegistrationPayload();

        // Act
        $response = $this->postJson(self::REGISTER_URL, $payload);

        // Assert
        $response
            ->assertCreated()
            ->assertJsonPath('message', 'Registration successful')
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'token',
                ],
            ])
            ->assertJsonPath('data.name', $payload['name'])
            ->assertJsonPath('data.email', $payload['email'])
            ->assertJsonMissingPath('data.password');

        $this->assertNotEmpty($response->json('data.token'));
        $this->assertIsString($response->json('data.token'));

        $this->assertDatabaseHas('users', [
            'id' => $response->json('data.id'),
            'name' => $payload['name'],
            'email' => $payload['email'],
        ]);

        $user = User::query()->where('email', $payload['email'])->first();

        $this->assertNotNull($user);
        $this->assertTrue(Hash::check($payload['password'], $user->password));
        $this->assertDatabaseCount('personal_access_tokens', 1);
        $this->assertTrue(
            PersonalAccessToken::query()->where('tokenable_id', $user->id)->exists()
        );
    }

    #[Test]
    public function it_fails_to_register_with_missing_fields(): void
    {
        // Arrange
        $payload = [];

        // Act
        $response = $this->postJson(self::REGISTER_URL, $payload);

        // Assert
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'password', 'password_confirmation']);
    }

    #[Test]
    public function it_fails_to_register_with_invalid_email(): void
    {
        // Arrange
        $payload = $this->validRegistrationPayload([
            'email' => 'not-an-email',
        ]);

        // Act
        $response = $this->postJson(self::REGISTER_URL, $payload);

        // Assert
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);

        $this->assertDatabaseCount('users', 0);
    }

    #[Test]
    public function it_fails_to_register_when_password_confirmation_does_not_match(): void
    {
        // Arrange
        $payload = $this->validRegistrationPayload([
            'password_confirmation' => 'different-password',
        ]);

        // Act
        $response = $this->postJson(self::REGISTER_URL, $payload);

        // Assert
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);

        $this->assertDatabaseCount('users', 0);
    }

    #[Test]
    public function it_fails_to_register_with_duplicate_email(): void
    {
        // Arrange
        User::factory()->create([
            'email' => 'john@example.com',
        ]);

        $payload = $this->validRegistrationPayload([
            'email' => 'john@example.com',
        ]);

        // Act
        $response = $this->postJson(self::REGISTER_URL, $payload);

        // Assert
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);

        $this->assertDatabaseCount('users', 1);
    }

    #[Test]
    public function it_fails_to_register_with_short_password(): void
    {
        // Arrange
        $payload = $this->validRegistrationPayload([
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        // Act
        $response = $this->postJson(self::REGISTER_URL, $payload);

        // Assert
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);

        $this->assertDatabaseCount('users', 0);
    }
}
