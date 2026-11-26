<?php
namespace app\Application\DTO;
readonly class LoginDTO
{
    public function __construct(
        public string $email,
        public string $password_hash 
    )
}