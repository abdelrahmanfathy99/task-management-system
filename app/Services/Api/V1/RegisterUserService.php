<?php

namespace App\Services\Api\V1;

use App\DTOs\Api\V1\RegisterUserDTO;
use App\DTOs\Api\V1\RegisterUserResultDTO;
use App\Repositories\Contracts\AuthTokenGenerator;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class RegisterUserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly AuthTokenGenerator $tokenGenerator
    ) {}

    public function execute(RegisterUserDTO $dto): RegisterUserResultDTO
    {
        DB::beginTransaction();

        $user = $this->userRepository->save([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => bcrypt($dto->password),
        ]);

        $token = $this->tokenGenerator->generateUserToken($user);

        DB::commit();

        return new RegisterUserResultDTO(
            userId: (int) $user->id,
            name: $user->name,
            email: $user->email,
            token: $token
        );
    }
}
