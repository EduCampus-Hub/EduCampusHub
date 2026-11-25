<?php

use App\Application\UseCase\CreateCourseUseCase;
use App\Application\UseCase\GetCourseUseCase;
use App\Application\UseCase\UpdateCourseUseCase;
use App\Application\UseCase\ViewAllCoursesUseCase;
use App\Infrastructure\Persistence\JsonCourseRepository;
use App\Presentation\Controller\CourseController;
use App\Presentation\Routing\CourseRoutes;
use App\Presentation\Routing\Router;

$path = explode('?', $_SERVER['REQUEST_URI'] ?? '/', 2)[0];
if (PHP_SAPI === 'cli-server' && $path === '/public/css/courses.css') {
    return false;
}

require __DIR__ . '/vendor/autoload.php';

$storagePath = getenv('COURSES_STORAGE_PATH') ?: __DIR__ . '/storage/json/courses.json';
$repository = new JsonCourseRepository($storagePath);
$controller = new CourseController(
    new CreateCourseUseCase($repository),
    new ViewAllCoursesUseCase($repository),
    new GetCourseUseCase($repository),
    new UpdateCourseUseCase($repository)
);

$router = new Router();
$router->add(CourseRoutes::INDEX, ['GET' => $controller->index(...)]);
$router->add(CourseRoutes::CREATE, ['GET' => $controller->handle(...), 'POST' => $controller->handle(...)]);
$router->add(CourseRoutes::EDIT, ['GET' => $controller->edit(...), 'POST' => $controller->edit(...)]);

try {
    $router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $path);
} catch (Throwable $exception) {
    error_log((string) $exception);
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Impossibile completare la richiesta.';
}
