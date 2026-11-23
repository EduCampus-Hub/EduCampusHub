<?php
declare(strict_types=1); 
namespace App\Application\Interfaces; 
use App\Domain\Entities\User; 

interface SessionManagerInterface {
    
public function setAuthenticatedUser(User $user): void;

public function getAuthenticatedUser(): ?User;

public function isAuthenticated(): bool;

public function logout(): void;
}