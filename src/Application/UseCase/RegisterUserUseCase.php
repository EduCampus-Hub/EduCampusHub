<?php

use App\Application\DTO\RegisterUserDTO;
use App\Domain\User;    

class RegisterUserUseCase
{
    private $userRepository;
    private $passwordHasher;

    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function execute(RegisterUserDTO $dto)
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if ($user) {
            throw new Exception('User already exists');
        }

        
        $user = new User(
            $dto->id,
            $dto->name,
            $dto->email,
            $dto->password_hash,
            $dto->role,
            $dto->user_class
        );
        $this->userRepository->save($user);

        return $user;
    }
}