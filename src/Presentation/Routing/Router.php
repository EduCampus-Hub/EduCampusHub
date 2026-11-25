<?php

namespace App\Presentation\Routing;

class Router
{
    private array $routes = [];

    /** @param array<string, callable> $handlers */
    public function add(string $path, array $handlers): void
    {
        $segments = explode('/', rtrim($path, '/'));
        $pattern = implode('/', array_map(
            static fn (string $segment): string => preg_match('/\A\{[a-zA-Z][a-zA-Z0-9_]*\}\z/', $segment)
                ? '([^/]+)'
                : preg_quote($segment, '~'),
            $segments
        ));

        $this->routes[] = ['pattern' => '~\A' . $pattern . '/?\z~', 'handlers' => $handlers];
    }

    public function dispatch(string $method, string $path): void
    {
        $allowed = [];
        foreach ($this->routes as $route) {
            if (!preg_match($route['pattern'], $path, $matches)) {
                continue;
            }

            $handler = $route['handlers'][$method] ?? null;
            if ($handler !== null) {
                $parameters = array_map('rawurldecode', array_slice($matches, 1));
                $handler(...$parameters);
                return;
            }

            $allowed = array_merge($allowed, array_keys($route['handlers']));
        }

        header('Content-Type: text/plain; charset=UTF-8');
        if ($allowed !== []) {
            header('Allow: ' . implode(', ', array_unique($allowed)));
            http_response_code(405);
            echo 'Metodo non consentito.';
            return;
        }

        http_response_code(404);
        echo 'Pagina non trovata.';
    }
}
