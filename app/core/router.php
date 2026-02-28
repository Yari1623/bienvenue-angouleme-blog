<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, string $action): void
    {
        $this->routes['GET'][trim($uri, '/')] = $action;
    }

    public function post(string $uri, string $action): void
    {
        $this->routes['POST'][trim($uri, '/')] = $action;
    }

    public function dispatch(string $uri): void
    {
        $uri = trim($uri, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        $action = $this->routes[$method][$uri] ?? null;

        if (!$action) {
            $this->handleNotFound();
            return;
        }

        [$controllerName, $methodName] = explode('@', $action);

        $controllerClass = "App\\Controllers\\{$controllerName}";

        if (!class_exists($controllerClass)) {
            $this->handleNotFound();
            return;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $methodName)) {
            $this->handleNotFound();
            return;
        }

        $controller->$methodName();
    }

    private function handleNotFound(): void
    {
        http_response_code(404);

        $controller = new \App\Controllers\ErrorController();
        $controller->notFound();
    }
}