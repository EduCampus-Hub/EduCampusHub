<?php

namespace App\Domain\Model;

class Certificate
{
    private function __construct(
        public readonly int $id,
        public readonly string $enrollmentId,
        public readonly string $userId,
        public readonly string $coursedId,
        public readonly string $issuedAt,
        public readonly string $certificateCode //dobbia crearlo noi, facendoci passare
    ) {}

    public static function create(string $enrollmentId, string $userId,  string $coursedId, string $issuedAt): self
    {
        
        return new self(
                id: null, 
                enrollmentId: trim($enrollmentId),
                userId: trim($userId),
                coursedId: trim($coursedId),
                issuedAt: trim($issuedAt)
                certificateCode: null
            );

    }
    public static function restore(int $id, string $enrollmentId, string $userId, string $coursedId, string $issuedAt, string $certificateCode ): self
    {
        return new self($id, $enrollmentId, $userId, $coursedId, $issuedAt, $certificateCode);
    }

}
