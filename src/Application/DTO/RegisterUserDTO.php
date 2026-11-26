<?php

namespace app\Application\DTO;

use app\Domain\Enum\UserRoleEnum;

readonly class RegisterUserDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public string $password_hash,
        public UserRoleEnum $role,
        public ?string $user_class
    ) {}
}