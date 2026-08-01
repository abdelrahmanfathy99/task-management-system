<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use PHPUnit\Framework\Attributes\Test;

class LogoutTest extends AuthTestCase
{
    #[Test]
    public function it_logs_out_an_authenticated_user_successfully(): void
    {
        // Arrange
        $user = User::factory()->create();
        $plainTextToken = $user->createToken('user-auth')->plainTextToken;

        $this->assertDatabaseCount('personal_access_tokens', 1);

        // Act
        $response = $this
            ->withToken($plainTextToken)
            ->postJson(self::LOGOUT_URL);

        // Assert
        $response
            ->assertOk()
            ->assertExactJson([
                'message' => 'Logout successful',
            ]);

        $this->assertDatabaseCount('personal_access_tokens', 0);
        $this->assertFalse(
            PersonalAccessToken::query()->where('tokenable_id', $user->id)->exists()
        );
    }

    #[Test]
    public function it_revokes_all_tokens_for_the_user_on_logout(): void
    {
        // Arrange
        $user = User::factory()->create();
        $firstToken = $user->createToken('user-auth')->plainTextToken;
        $user->createToken('another-device');

        $this->assertDatabaseCount('personal_access_tokens', 2);

        // Act
        $response = $this
            ->withToken($firstToken)
            ->postJson(self::LOGOUT_URL);

        // Assert
        $response->assertOk();
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    #[Test]
    public function it_fails_to_logout_when_unauthenticated(): void
    {
        // Arrange
        // no auth token

        // Act
        $response = $this->postJson(self::LOGOUT_URL);

        // Assert
        $response
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    #[Test]
    public function it_fails_to_logout_with_an_invalid_token(): void
    {
        // Arrange
        $invalidToken = 'invalid-plain-text-token';

        // Act
        $response = $this
            ->withToken($invalidToken)
            ->postJson(self::LOGOUT_URL);

        // Assert
        $response
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');
    }
}
