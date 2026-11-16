<?php
namespace App\Application\UseCase;
use App\Domain\Repository\CourseRepositoryInterface;
class ViewAllCoursesUseCase{
    public function __construct(private CourseRepositoryInterface $_repo ){
    }
    public function execute():array{
        return $this -> _repo -> findAll();
    }
}