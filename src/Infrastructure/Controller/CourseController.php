<?php

namespace App\Infrastructure\Controller;

use App\Application\DTO\CreateCourseDTO;
use App\Application\UseCase\CreateCourseUseCase;
use App\Application\UseCase\GetCourseUseCase;
use App\Application\UseCase\UpdateCourseUseCase;
use App\Application\UseCase\ViewAllCoursesUseCase;
use InvalidArgumentException;

class CourseController
{
    public function __construct
    (
        private CreateCourseUseCase $createCourseUseCase,
        private ViewAllCoursesUseCase $viewAllCoursesUseCase,
        private GetCourseUseCase $getCourseUseCase,
        private UpdateCourseUseCase $updateCourseUseCase
    ) {}

    // GET /corsi
    public function index(): void
    {
        $courses = $this->viewAllCoursesUseCase->execute();

        require_once dirname(__DIR__, 2) . '/Presentation/Views/Courses/catalog.php';
    }

    // GET e POST /corsi/nuovo
    public function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            try
            {
                $this->createCourseUseCase->execute($this->buildInput());

                header('Location: /corsi');
                exit;
            } catch (InvalidArgumentException $e)
            {
                $errorMessage = $e->getMessage();
            }
        }

        require_once dirname(__DIR__, 2) . '/Presentation/Views/Courses/create_course.php';
    }

    // GET e POST /corsi/modifica?id=...
    public function edit(): void
    {
        $id = $_GET['id'] ?? '';
        $course = $this->getCourseUseCase->execute($id);

        if ($course === null)
        {
            http_response_code(404);
            echo "<h1>404 - Corso non trovato</h1>";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            try
            {
                $this->updateCourseUseCase->execute($id, $this->buildInput($id));

                header('Location: /corsi');
                exit;
            } catch (InvalidArgumentException $e)
            {
                $errorMessage = $e->getMessage();
            }
        }

        require_once dirname(__DIR__, 2) . '/Presentation/Views/Courses/create_course.php';
    }

    // In creazione l'id è vuoto: quello vero lo genera Course::create()
    private function buildInput(string $id = ''): CreateCourseDTO
    {
        return new CreateCourseDTO(
            maxCapacity: (int)($_POST['maxCapacity'] ?? 0),
            targetAudience: $_POST['targetAudience'] ?? '',
            status: $_POST['status'] ?? '',
            id: $id,
            title: trim($_POST['title'] ?? ''),
            description: trim($_POST['description'] ?? ''),
            isMandatory: isset($_POST['isMandatory']),
            durationInHours: (int)($_POST['durationInHours'] ?? 0)
        );
    }
}
