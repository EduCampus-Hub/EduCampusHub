<?php
namespace App\Domain;
use App\Domain\Enum\UserRoleEnum;
class User
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $password_hash,
        public UserRoleEnum $role,
        public ?string $user_class
    ) {}
}