<?php

namespace App\Application\UseCase;

use App\Application\DTO\UserAgendaDTO;
use App\Domain\Model\Agenda;
use App\Domain\Model\Certificate;
use App\Domain\Repository\CertificateRepositoryInterface;

class GetUserAgendaUseCase
{
    public function __construct(
        private readonly CertificateRepositoryInterface $certificateRepository
    ) {}

    public function execute(string $userId): UserAgendaDTO
    {
        $certificates = $this->certificateRepository->findByUser($userId);
        $courseIds = array_map(
            static fn (Certificate $certificate): string => $certificate->coursedId,
            $certificates
        );

        $agenda = Agenda::create($courseIds);

        return new UserAgendaDTO(
            userId: $userId,
            listaCorsi: $agenda->listaCorsi
        );
    }
}