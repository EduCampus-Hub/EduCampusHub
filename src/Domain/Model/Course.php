<?php

namespace App\Domain\Model;

use App\Domain\Enum\CourseStatusEnum;
use App\Domain\Enum\TargetAudienceEnum;

// maxCapacity null = nessun limite di capienza
readonly class Course
{
    private function __construct(
        public string $id,
        public string $title,
        public string $description,
        public TargetAudienceEnum $target,
        public CourseStatusEnum $status,
        public bool $isMandatory,
        public int $durationInHours,
        public int $maxCapacity
    ) {}

    public static function create(
        string $title,
        string $description,
        TargetAudienceEnum $target,
        CourseStatusEnum $status,
        bool $isMandatory,
        int $durationInHours,
        int $maxCapacity
    ) {
        return new self(
            id: self::generateId(),
            title: $title,
            description: $description,
            target: $target,
            status: $status,
            isMandatory: $isMandatory,
            durationInHours: $durationInHours,
            maxCapacity: $maxCapacity
        );
    }

    public static function restore(
        string $id,
        string $title,
        string $description,
        TargetAudienceEnum $target,
        CourseStatusEnum $status,
        bool $isMandatory,
        int $durationInHours,
        int $maxCapacity
    ) {
        return new self(
            id: $id,
            title: $title,
            description: $description,
            target: $target,
            status: $status,
            isMandatory: $isMandatory,
            durationInHours: $durationInHours,
            maxCapacity: $maxCapacity
        );
    }

    /**
     * Genera un UUID versione 4 (identificatore casuale univoco).
     * Formato: xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx
     */
    private static function generateId(): string
    {
        // 16 byte (128 bit) casuali
        $bytes = random_bytes(16);
         // Imposta i 4 bit della "versione" a 0100 (= versione 4)
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        // Imposta i 2 bit della "variante" a 10 (standard RFC 4122)
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);

        // bin2hex: converte i byte in 32 caratteri esadecimali
        // str_split: li divide in 8 gruppi da 4 caratteri
        // vsprintf: li ricompone nel formato 8-4-4-4-12 con i trattini
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }
}