<?php

namespace App\Services\Api\V1;

use App\DTOs\Api\V1\AuthenticateUserDTO;
use App\DTOs\Api\V1\AuthenticateUserResultDTO;
use App\Repositories\Contracts\AuthTokenGenerator;
use App\Repositories\Contracts\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Hash;

final class AuthenticateUserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly AuthTokenGenerator $tokenGenerator
    ) {}

    public function execute(AuthenticateUserDTO $dto): AuthenticateUserResultDTO
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if (! $user || ! Hash::check($dto->password, $user->password)) {
            throw new Exception('Invalid credentials.');
        }

        $token = $this->tokenGenerator->generateUserToken($user);

        return new AuthenticateUserResultDTO(
            userId: (int) $user->id,
            name: $user->name,
            email: $user->email,
            token: $token
        );
    }
}
