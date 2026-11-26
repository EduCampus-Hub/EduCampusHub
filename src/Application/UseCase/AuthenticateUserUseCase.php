<?php

declare(strict_types=1);

namespace app\Application\UseCase;


use app\Application\DTO\LoginDTO;
use app\Application\Service\SessionManagerInterface;
use app\Domain\Model\User;
use app\Domain\Repository\UserRepositoryInterface;

class AuthenticateUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private SessionManagerInterface $session
    ) {
    }

    public function execute(LoginDTO $dto): ?User
    {
        $email = strtolower(trim($dto->email));
        $password = $dto->password_hash;

        if ($email === '' || $password === '' || strlen($password) > 72 || str_contains($password, "\0")) {
            return null;
        }

        $user = $this->userRepository->findByEmail($email);
        if ($user === null || !password_verify($password, $user->getPasswordHash())) {
            return null;
        }

        $this->session->setAuthenticatedUser($user);

        return $user;
    }
}