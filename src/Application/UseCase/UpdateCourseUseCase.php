<?php

namespace App\Application\UseCase;

use App\Application\DTO\CourseInput;
use App\Domain\Model\Course;
use App\Domain\Repository\CourseRepositoryInterface;
use RuntimeException;

class UpdateCourseUseCase
{
    public function __construct(private CourseRepositoryInterface $courseRepository) {}

    public function execute(string $id, CourseInput $input): bool
    {
        $existingCourse = $this->courseRepository->findById($id);
        if ($existingCourse === null) {
            return false;
        }

        $input->validate();

        $course = Course::restore(
            id: $existingCourse->id,
            title: $input->title,
            description: $input->description,
            target: $input->targetEnum(),
            status: $input->statusEnum(),
            isMandatory: $input->isMandatory,
            durationInHours: $input->durationInHours,
            maxCapacity: $input->maxCapacity
        );

        if (!$this->courseRepository->save($course)) {
            throw new RuntimeException('Unable to save the course.');
        }

        return true;
    }
}
