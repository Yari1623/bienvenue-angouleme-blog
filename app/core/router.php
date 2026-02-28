<?php

namespace App\Core;

use App\Core\Auth;

class Router
{
    private array $routes = [];

    public function get(string $uri, string $action, bool $auth = false, ?string $role = null): void
    {
        $this->routes['GET'][trim($uri, '/')] = [
            'action' => $action,
            'auth' => $auth,
            'role' => $role
        ];
    }

    public function post(string $uri, string $action, bool $auth = false, ?string $role = null): void
    {
        $this->routes['POST'][trim($uri, '/')] = [
            'action' => $action,
            'auth' => $auth,
            'role' => $role
        ];
    }

    public function dispatch(string $uri): void
    {
        $uri = trim($uri, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        $route = $this->routes[$method][$uri] ?? null;

        if (!$route) {
            $this->handleNotFound();
            return;
        }

        if ($route['auth'] && !Auth::check()) {
            header('Location: /bienvenue-angouleme-blog/public/login');
            exit;
        }

        if ($route['role']) {
            $user = Auth::user();

            if (!$user || $user['role'] !== $route['role']) {
                http_response_code(403);
                echo "403 - Accès interdit";
                exit;
            }
        }

        [$controllerName, $methodName] = explode('@', $route['action']);

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