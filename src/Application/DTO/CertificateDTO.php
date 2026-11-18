<?php

namespace App\Application\UseCase;

use App\Domain\Model\Certificate;

readonly class CertificateDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $enrollmentId,
        public readonly string $userId,
        public readonly string $coursedId,
        public readonly string $issuedAt,
        public readonly string $certificateCode
    ) {}
}