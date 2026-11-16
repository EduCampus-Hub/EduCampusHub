<?php
namespace App\Application\UseCase;
use App\Domain\Model\Course;
use InvalidArgumentException;
use App\Domain\Repository\CourseRepositoryInterface;

class  CreateCourseUseCase
{
    public function __construct(
        private CourseRepositoryInterface $courseRepository
    ) {}

    public function execute(Course $input): void
    {
        

        $this->courseRepository->save($course);
    }
}