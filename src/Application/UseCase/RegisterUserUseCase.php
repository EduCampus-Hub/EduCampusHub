<?php

declare(strict_types=1);

namespace app\Application\UseCase;

use app\Application\DTO\RegisterUserDTO;
use app\Domain\Model\User;
use app\Domain\Repository\UserRepositoryInterface;
use InvalidArgumentException;
use RuntimeException;

class RegisterUserUseCase
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function execute(RegisterUserDTO $dto): User
    {
        $name = trim($dto->name);
        $email = strtolower(trim($dto->email));
        $userClass = trim($dto->user_class ?? '');

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Inserisci un nome e un indirizzo email valido.');
        }

        $password = $dto->password_hash;
        if ($password === '' || strlen($password) > 72 || str_contains($password, "\0")) {
            throw new InvalidArgumentException('Inserisci una password valida lunga al massimo 72 byte.');
        }

        if ($this->userRepository->findByEmail($email) !== null) {
            throw new InvalidArgumentException('Esiste già un utente con questa email.');
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        if ($passwordHash === false) {
            throw new RuntimeException('Impossibile generare l’hash della password.');
        }

        $user = new User(
            $dto->id === '' ? 'U' . bin2hex(random_bytes(8)) : $dto->id,
            $name,
            $email,
            $passwordHash,
            $dto->role,
            $userClass === '' ? null : $userClass
        );

        if (!$this->userRepository->save($user)) {
            throw new RuntimeException('Impossibile salvare il nuovo utente.');
        }

        return $user;
    }
}