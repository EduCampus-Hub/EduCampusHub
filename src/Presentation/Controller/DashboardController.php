<?php

namespace App\Presentation\Controller;

use App\Application\DTO\UserAgendaDTO;
use App\Application\UseCase\GetUserAgendaUseCase;

class DashboardController
{
    public function __construct(
        private readonly GetUserAgendaUseCase $useCase
    ) {}

    public function handle(string $userId): UserAgendaDTO
    {
        return $this->useCase->execute($userId);
    }
}