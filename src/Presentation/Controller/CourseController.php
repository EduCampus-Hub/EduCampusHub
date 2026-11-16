<?php

namespace App\Presentation\Controller;

use App\Presentation\Controller\CreateCourseUseCase;
use App\Application\DTO\CreateCourseDTO;
use InvalidArgumentException;

class CourseController
{
    public function __construct
    (
        private CreateCourseUseCase $createCourseUseCase
    ) {}

    public function handle(): void 
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            try 
            {
                $input = new CreateCourseDTO(

                );
            } catch (InvalidArgumentException $e) 
            {
                echo "Error: " . $e->getMessage();
            }
        }
    }
}