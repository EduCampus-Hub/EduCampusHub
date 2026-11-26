<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use app\Application\UseCase\AuthenticateUserUseCase;
use app\Application\UseCase\RegisterUserUseCase;
use app\Infrastructure\Persistence\JsonUserRepository;
use app\Infrastructure\Router;
use app\Infrastructure\Session\PhpSessionAdapter;
use app\Presentation\Controller\AuthController;


$repository = new JsonUserRepository(__DIR__ . '/storage/json/users.json');
$sessionManager = new PhpSessionAdapter();
$authUseCase = new AuthenticateUserUseCase($repository, $sessionManager);
$registerUseCase = new RegisterUserUseCase($repository);
$authController = new AuthController($authUseCase, $registerUseCase, $sessionManager);


// 3. Configurazione delle Rotte
$router = new Router();


// Mappiamo gli URL parlanti
$router->get('/', [$authController, 'handleLogin']);
$router->get('/index', [$authController, 'handleLogin']);
$router->get('/index.php', [$authController, 'handleLogin']);
$router->post('/index.php', [$authController, 'handleLogin']);
$router->get('/register', [$authController, 'handleRegister']);
$router->post('/register', [$authController, 'handleRegister']);
$router->get('/register.php', [$authController, 'handleRegister']);
$router->post('/register.php', [$authController, 'handleRegister']);
$router->get('/logout', [$authController, 'handleLogout']);


// 4. Avvio del Router passando il REQUEST_URI
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';      // Es: "/create-request"
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET'; // Es: "GET" o "POST"


$router->dispatch($requestMethod, $requestUri);
