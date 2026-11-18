<?php

class AuthenticateUserUseCase
{
    private $userRepository;
    private $passwordHasher;

    public function __construct($userRepository, $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function execute($email, $password)
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