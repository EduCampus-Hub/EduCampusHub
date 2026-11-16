<?php
namespace App\Application\DTO;
/*
        public int $maxCapacity,
        public TargetAudienceEnum $target,
        public CourseStatusEnum $status,
        public string $id, 
        public string $title,
        public string $description,
        public bool $isMandatory,
        public int $durationInHours
*/
readonly class CreateCourseDTO{
    public function __construct(
        public int $maxCapacity,
        public string $targetAudience,
        public string $status,
        public string $id, 
        public string $title,
        public string $description,
        public bool $isMandatory,
        public int $durationInHours
    ){}
}