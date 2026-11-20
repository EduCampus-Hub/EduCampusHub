<?php

namespace App\Application\UseCase;

use App\Domain\Model\Certificate;
use App\Domain\Repository\CertificateRepositoryInterface;
use RuntimeException;

class GenerateCertificateUseCase
{
    public function __construct(
        private readonly CertificateRepositoryInterface $certificateRepository
    ) {}

    public function execute(string $enrollmentId, string $userId, string $coursedId): Certificate
    {
        // Check if a certificate already exists for the given enrollment ID
        $existingCertificate = $this->certificateRepository->findByEnrollment($enrollmentId);
        if ($existingCertificate) {
            throw new RuntimeException('Certificate already exists for this enrollment.');
        }

        // Creo la nuova instanza
        $certificate = Certificate::create(
            id: 0,
            enrollmentId: $enrollmentId,
            userId: $userId,
            coursedId: $coursedId,
            issuedAt: (new \DateTime())->format('Y-m-d H:i:s')
        );

        // Salva il certificato nella repo
        $this->certificateRepository->save($certificate);

        return $certificate;
    }
}