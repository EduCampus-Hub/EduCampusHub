<?php

namespace app\Application\UseCase;

use app\Application\DTO\RegisterUserDTO;
use app\Domain\Model\User;
use app\Domain\Repository\UserRepositoryInterface;
use Exception;

class RegisterUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private object $passwordHasher
    ) {}

    public function execute(RegisterUserDTO $dto): User
    {
        if ($this->userRepository->findByEmail($dto->email)) {
            throw new Exception('User already exists');
        }

        $passwordHash = $this->passwordHasher->hash($dto->password_hash);
        $user = new User(
            $dto->id,
            $dto->name,
            $dto->email,
            $passwordHash,
            $dto->role,
            $dto->user_class
        );

        $this->userRepository->save($user);

        return $user;
    }
}