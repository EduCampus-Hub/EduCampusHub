<?php

namespace App\Application\UseCase;
use App\Domain\Enum\CourseStatusEnum;
use App\Domain\Model\Course;
use App\Domain\Repository\CourseRepositoryInterface;
class ToggleCourseStatusUseCase{
    public function __construct(
        private CourseRepositoryInterface $_repo
    )
    {}
    public function execute(string $id, CourseStatusEnum $statusEnum){
        $course = $this->_repo ->  findById($id);
        $courseObj = Course::restore($course ->id, $course -> title,$course -> description,$course -> target, $statusEnum, $course -> isMandatory,$course -> durationInHours, $course -> maxCapacity);
        $this->_repo -> save($courseObj);
    }
}