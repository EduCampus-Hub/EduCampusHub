<?php
<<<<<<< HEAD
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
=======

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

$action = isset($_GET['action']) ? (string) $_GET['action'] : 'home';
$role = isset($_GET['role']) ? strtolower((string) $_GET['role']) : 'admin';

$totalCourses = 0;
$registeredUsers = 0;
$totalEnrollments = 0;
$issuedCertificates = 0;
$courses = [];
$enrollments = [];
$users = [];

switch ($action) {
    case 'home':
        if ($role === 'student' || $role === 'studente') {
            require __DIR__ . '/src/Presentation/Views/DashboardStudent.php';
        } else {
            require __DIR__ . '/src/Presentation/Views/DashboardAdmin.php';
        }
        break;

    case 'create_course':
    case 'edit_course':
        require __DIR__ . '/src/Presentation/Views/DashboardAdmin.php';
        break;

    case 'download_certificate':
        http_response_code(501);
        echo '<h1>Attestato PDF non ancora disponibile</h1>';
        break;

    case 'upload_certificate':
        http_response_code(501);
        echo '<h1>Caricamento attestato non ancora disponibile</h1>';
        break;

    default:
        http_response_code(404);
        echo '<h1>404 - Pagina non trovata</h1>';
}
>>>>>>> origin/Team4
