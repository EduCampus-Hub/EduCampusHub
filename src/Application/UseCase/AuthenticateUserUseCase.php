<?php

namespace app\Application\UseCase;

use app\Domain\Model\User;
use app\Domain\Repository\UserRepositoryInterface;
use Exception;

class AuthenticateUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private object $passwordHasher
    ) {}

    public function execute(string $email, string $password): User
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new Exception('User not found');
        }

        if (!$this->passwordHasher->verify($password, $user->getPasswordHash())) {
            throw new Exception('Invalid password');
        }

        return $user;
    }
}