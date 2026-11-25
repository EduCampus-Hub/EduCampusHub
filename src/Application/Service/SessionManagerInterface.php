<?php

namespace app\Application\Service;

use app\Domain\Model\User;

interface SessionManagerInterface
{
    public function setAuthenticatedUser(User $user): void;

    public function getAuthenticatedUser(): ?User;

    public function isAuthenticated(): bool;

    public function logout(): void;
}