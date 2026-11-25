<?php

namespace App\Domain\Repository;

use App\Domain\Model\Certificate;

interface CertificateRepositoryInterface
{
    public function findById(string $id): ?Certificate;
    public function findByEnrollment(string $EnrollmentId): ?Certificate;
    /**
     * @return Certificate[]
     */
    public function findByUser(string $userId): array;
    public function save(Certificate $certificate): bool;
    
}
