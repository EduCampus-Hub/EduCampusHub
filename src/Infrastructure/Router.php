<?php
namespace app\Infrastructure;
class Router
{
    private array $routes = [];


    public function get(string $path, array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }


    public function post(string $path, array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }


    private function addRoute(string $method, string $path, array $handler): void
    {
        // Normalizziamo il percorso per assicurarci che inizi sempre con '/'
        $path = '/' . trim($path, '/');
        $this->routes[$method][$path] = $handler;
    }


    public function dispatch(string $method, string $uri): void
    {
        // Puliamo l'URI (rimuoviamo eventuali query string ?foo=bar o slash finali)
        $parsedUrl = parse_url($uri, PHP_URL_PATH);
        $path = '/' . trim($parsedUrl, '/');


        if (isset($this->routes[$method][$path])) {
            [$controller, $methodName] = $this->routes[$method][$path];
            $controller->$methodName();
            return;
        }


        http_response_code(404);
        echo "<h1>404 - Pagina non trovata</h1>";
    }
}
