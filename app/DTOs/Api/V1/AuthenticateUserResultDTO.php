<?php

namespace App\DTOs\Api\V1;

class AuthenticateUserResultDTO
{
    public function __construct(
        public int $userId,
        public string $name,
        public string $email,
        public string $token
    ) {}
}
