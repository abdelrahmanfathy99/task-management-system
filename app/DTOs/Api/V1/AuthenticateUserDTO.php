<?php

namespace App\DTOs\Api\V1;

class AuthenticateUserDTO
{
    public function __construct(
        public string $email,
        public string $password
    ) {}
}
