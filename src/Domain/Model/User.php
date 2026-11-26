<?php

declare(strict_types=1);

namespace app\Domain\Model;

use app\Domain\Enum\UserRoleEnum;

class User
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public string $password_hash,
        public UserRoleEnum $role,
        public ?string $user_class
    ) {
    }

    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }
}