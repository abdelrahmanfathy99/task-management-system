<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface AuthTokenGenerator
{
    public function generateUserToken(User $user, string $name = 'user-auth'): string;

    public function revokeUserTokens(User $user): void;
}
