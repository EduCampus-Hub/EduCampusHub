<?php
namespace App\Application\UseCase;
use App\Domain\Model\Course;
use InvalidArgumentException;
use App\Domain\Repository\CourseRepositoryInterface;
/*
        public string $id,
        public string $title,
        public string $description,
        public TargetAudienceEnum $target,
        public CourseStatusEnum $status,
        public bool $isMandatory,
        public int $durationInHours,
        public int $maxCapacity
*/
class  CreateCourseUseCase
{
    public function __construct(
        private CourseRepositoryInterface $courseRepository
    ) {}

    public function execute(Course $input): void
    {
        if(trim(empty($input->title)) || empty($input->description)) {
            throw new InvalidArgumentException('Title and description are required.');
        }

        if($input->maxCapacity <= 0 && $input->durationInHours <= 0) {
            throw new InvalidArgumentException('Max capacity and duration must be greater than zero.');
        }

        $newCourse = Course::create(
            title: $input->title,
            description: $input->description,
            target: $input->target,
            status: $input->status,
            isMandatory: $input->isMandatory,
            durationInHours: $input->durationInHours,
            maxCapacity: $input->maxCapacity
        );
        
        $this->courseRepository->save($newCourse);
    }
}