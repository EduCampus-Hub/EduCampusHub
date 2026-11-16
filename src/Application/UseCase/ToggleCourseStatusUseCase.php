<?php

namespace App\Application\UseCase;
use App\Domain\Enum\CourseStatusEnum;
use App\Domain\Model\Course;
use App\Domain\Repository\CourseRepository;
class ToggleCourseStatusUseCase{
    public function __construct(
        private CourseRepository $_repo
    )
    {}
    public function toggleStatus(string $id, CourseStatusEnum $statusEnum){
        $course = _repo ->  findById($id);
        $course -> $status = (string)$statusEnum;
    }
}