<?php
namespace App\Application\DTO;
readonly class RegisterUserDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $password_hash,
        public UserRoleEnum $role,
        public ?string $user_class
    ){}
}