<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, string $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post(string $uri, string $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch(string $uri): void
    {
        $uri = trim($uri, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        $action = $this->routes[$method][$uri] ?? null;

        if (!$action) {
            http_response_code(404);
            echo "404 - Page non trouvée";
            return;
        }

        [$controllerName, $methodName] = explode('@', $action);

        $controllerClass = "App\\Controllers\\$controllerName";

        $controller = new $controllerClass();
        $controller->$methodName();
    }
}