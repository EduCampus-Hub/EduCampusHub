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

    public static function create(int $id, string $certificateCode): self
    {
    /*    
    return new self(
            id: null, 
            certificateCode
        );
        */
    }

}
