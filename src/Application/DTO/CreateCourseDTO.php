<?php

namespace App\Application\DTO;

readonly class CreateCourseDTO extends CourseInput
{
    public function __construct(
        int $maxCapacity,
        string $targetAudience,
        string $status,
        public string $id,
        string $title,
        string $description,
        bool $isMandatory,
        int $durationInHours
    ) {
        parent::__construct($maxCapacity, $targetAudience, $status, $title, $description, $isMandatory, $durationInHours);
    }
}
