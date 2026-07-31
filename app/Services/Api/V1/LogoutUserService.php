<?php

namespace App\Services\Api\V1;

use App\Repositories\Contracts\AuthTokenGenerator;
use App\Repositories\Eloquent\UserRepository;
use Exception;

final class LogoutUserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly AuthTokenGenerator $tokenGenerator
    ) {}

    public function execute(int $userId): void
    {
        $user = $this->userRepository->findById($userId);

        if (! $user) {
            throw new Exception('User not found.');
        }

        $this->tokenGenerator->revokeUserTokens($user);
    }
}
