<?php
require_once __DIR__ . '/vendor/autoload.php';

use app\Infrastructure\Persistence\JsonUserRepository;
use app\Application\GetRequestList;
use app\Application\CreateRequest;
use app\Infrastructure\Controller\AuthController;
use app\Infrastructure\Router;


$dataPath = __DIR__ . '/data/users.json';
$repository = new JsonUserRepository($dataPath);


// 2. Inizializzazione Controller
$authController = new AuthController(
    new AuthenticateUserUseCase($repository),
    new PhpSessionAdapter()
);


// 3. Configurazione delle Rotte
$router = new Router();


// Mappiamo gli URL parlanti
$router->get('/', [$authController, 'handle']);
$router->get('/index', [$authController, 'handle']);
$router->get('/index.php', [$authController, 'handle']);
$router->get('/create-request', [$createRequestController, 'handle']);
$router->post('/create-request', [$createRequestController, 'handle']);


// 4. Avvio del Router passando il REQUEST_URI
$requestUri = $_SERVER['REQUEST_URI'];      // Es: "/create-request"
$requestMethod = $_SERVER['REQUEST_METHOD']; // Es: "GET" o "POST"


$router->dispatch($requestMethod, $requestUri);
