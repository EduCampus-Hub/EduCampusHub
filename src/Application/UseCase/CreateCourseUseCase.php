<?php

namespace App\Application\UseCase;

use App\Application\DTO\CreateCourseDTO;
use App\Domain\Model\Course;
use App\Domain\Repository\CourseRepositoryInterface;
use RuntimeException;

class CreateCourseUseCase
{
    public function __construct(
        private CourseRepositoryInterface $courseRepository
    ) {}

    public function execute(CreateCourseDTO $input): void
    {
        $input->validate();

        $newCourse = Course::create(
            title: $input->title,
            description: $input->description,
            target: $input->targetEnum(),
            status: $input->statusEnum(),
            isMandatory: $input->isMandatory,
            durationInHours: $input->durationInHours,
            maxCapacity: $input->maxCapacity
        );

        if (!$this->courseRepository->save($newCourse)) {
            throw new RuntimeException('Unable to save the course.');
        }
    }
}
