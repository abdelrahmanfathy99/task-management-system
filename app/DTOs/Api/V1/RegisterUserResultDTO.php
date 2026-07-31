<?php

namespace App\DTOs\Api\V1;

final readonly class RegisterUserResultDTO
{
    public function __construct(
        public int $userId,
        public string $name,
        public string $email,
        public string $token
    ) {}
}
