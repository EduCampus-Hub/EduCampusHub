<?php

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
        $password = $dto->password;
        // bcrypt usa al massimo 72 byte. Verifichiamo la password intera.
        if ($password === '' || strlen($password) > 72 || str_contains($password, "\0")) {
            return null;
        }

        $user = $this->userRepository->findByEmail($dto->email);
        if ($user === null || !password_verify($password, $user->getPasswordHash())) {
            return null;
        }

        // La sessione viene aggiornata solo dopo aver verificato le credenziali.
        $this->session->setAuthenticatedUser($user);
        return $user;
    }
}