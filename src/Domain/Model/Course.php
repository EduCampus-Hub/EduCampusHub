<?php 
namespace App\Domain\Model;
use App\Domain\Enum\TargetAudienceEnum;
use App\Domain\Enum\CourseStatusEnum;

//parametri course: i due enum, un vaslore che se messo null vuol dire che non ce una capienza massima del corso
readonly class Course
{
    public function __construct(
        public int $maxCapacity,
        public TargetAudienceEnum $target,
        public CourseStatusEnum $status,
        public string $id, 
        public string $title,
        public string $description,
        public bool $isMandatory,
        public int $durationInHours
    ) {}
}