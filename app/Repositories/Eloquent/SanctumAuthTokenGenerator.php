<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\AuthTokenGenerator;

class SanctumAuthTokenGenerator implements AuthTokenGenerator
{
    public function generateUserToken(User $user, string $name = 'user-auth'): string
    {
        $user->tokens()->delete();

        return $user->createToken($name)->plainTextToken;
    }

    public function revokeUserTokens(User $user): void
    {
        $user->tokens()->delete();
    }
}
