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
                    maxCapacity: (int)$_POST['maxCapacity'],
                    targetAudience: $_POST['targetAudience'],
                    status: $_POST['status'],
                    id: $_POST['id'],
                    title: $_POST['title'],
                    description: $_POST['description'],
                    isMandatory: isset($_POST['isMandatory']) ? (bool)$_POST['isMandatory'] : false,
                    durationInHours: (int)$_POST['durationInHours']
                );
            } catch (InvalidArgumentException $e) 
            {
                echo "Error: " . $e->getMessage();
            }
        }
    }
}