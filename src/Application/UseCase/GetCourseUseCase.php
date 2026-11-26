<?php

namespace App\Application\UseCase;

use App\Domain\Model\Course;
use App\Domain\Repository\CourseRepositoryInterface;

class GetCourseUseCase
{
    public function __construct(private CourseRepositoryInterface $courseRepository) {}

    public function execute(string $id): ?Course
    {
        return $this->courseRepository->findById($id);
    }
}
