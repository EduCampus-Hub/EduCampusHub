<?php

namespace App\Domain\Model;

class Certificate
{
    private function __construct(
        public readonly ?int $id,
        public readonly string $enrollmentId,
        public readonly string $userId,
        public readonly string $coursedId,
        public readonly string $issuedAt,
        public readonly ?string $certificateCode //dobbia crearlo noi, facendoci passare
    ) {}
        public readonly string $certificateCode
    ) {}

    public static function create(
        int $id,
        string $enrollmentId,
        string $userId,
        string $coursedId,
        string $issuedAt
    ): self
    {
        return new self(
            id: $id,
            enrollmentId: $enrollmentId,
            userId: $userId,
            coursedId: $coursedId,
            issuedAt: $issuedAt,
            certificateCode: self::generateCertificateCode($coursedId, $userId)
        );
    }

    public static function create(string $enrollmentId, string $userId,  string $coursedId, string $issuedAt): self
    public static function restore(int $id, string $enrollmentId, string $userId, string $coursedId, string $issuedAt, string $certificateCode): self
    {
        return new self(
            id: $id,
            enrollmentId: $enrollmentId,
            userId: $userId,
            coursedId: $coursedId,
            issuedAt: $issuedAt,
            certificateCode: $certificateCode
        );
    }

    private static function generateCertificateCode(string $coursedId, string $userId): string
    {
        // Compone EDU-corso-utente con un suffisso casuale di quattro cifre.
        return \sprintf('EDU-%s-%s-%04d', $coursedId, $userId, random_int(0, 9999));
    }
    /*
    
    */

}
