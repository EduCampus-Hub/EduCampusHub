<?php

namespace App\Application\UseCase;

use App\Application\DTO\UserAgendaDTO;
use App\Domain\Model\Agenda;
use App\Domain\Repository\AgendaRepositoryInterface;

class GetUserAgendaUseCase
{
    public function __construct(
        private readonly AgendaRepositoryInterface $agendaRepository
    ) {}

    public function execute(string $userId): UserAgendaDTO
    {
        $agenda = $this->agendaRepository->findByUser($userId) ?? Agenda::create([]);

        return new UserAgendaDTO(
            userId: $userId,
            listaCorsi: $agenda->listaCorsi
        );
    }
}