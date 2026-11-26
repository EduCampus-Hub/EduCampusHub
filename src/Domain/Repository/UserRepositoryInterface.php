<?php

namespace app\Domain\Repository;

use app\Domain\Model\User;

interface UserRepositoryInterface
{
    public function findById(string $id): ?User;
    public function findByEmail(string $email): ?User;
    public function findAll(): array;
    public function save(User $user): bool;
}