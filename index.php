<?php
require_once './vendor/autoload.php';

use App\Application\UseCase\CreateCourseUseCase;
use App\Application\UseCase\GetCourseUseCase;
use App\Application\UseCase\UpdateCourseUseCase;
use App\Application\UseCase\ViewAllCoursesUseCase;
use App\Infrastructure\Persistence\JsonCourseRepository;
use App\Infrastructure\Router;
use App\Infrastructure\Controller\CourseController;

$dataPathCourses = './storage/json/courses.json';
$repository = new JsonCourseRepository($dataPathCourses);

$courseController = new CourseController(
    new CreateCourseUseCase($repository),
    new ViewAllCoursesUseCase($repository),
    new GetCourseUseCase($repository),
    new UpdateCourseUseCase($repository)
);

$router = new Router();

$router->get('/', [$courseController, 'index']);
$router->get('/corsi', [$courseController, 'index']);
$router->get('/corsi/nuovo', [$courseController, 'handle']);
$router->post('/corsi/nuovo', [$courseController, 'handle']);
$router->get('/corsi/modifica', [$courseController, 'edit']);
$router->post('/corsi/modifica', [$courseController, 'edit']);

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

$router->dispatch($requestMethod, $requestUri);
