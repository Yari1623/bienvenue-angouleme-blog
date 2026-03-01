<?php

namespace App\Core;

use App\Core\Auth;

class Router
{
    private array $routes = [];

    public function get(string $uri, string $action, bool $auth = false, ?string $role = null): void
    {
        $this->addRoute('GET', $uri, $action, $auth, $role);
    }

    public function post(string $uri, string $action, bool $auth = false, ?string $role = null): void
    {
        $this->addRoute('POST', $uri, $action, $auth, $role);
    }

    private function addRoute(string $method, string $uri, string $action, bool $auth, ?string $role): void
    {
        $uri = trim($uri, '/');

        // Convertit {slug} en regex
        $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '([^/]+)', $uri);
        $pattern = "#^" . $pattern . "$#";

        $this->routes[$method][] = [
            'pattern' => $pattern,
            'uri' => $uri,
            'action' => $action,
            'auth' => $auth,
            'role' => $role
        ];
    }

    public function dispatch(string $uri): void
    {
        $uri = trim($uri, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$method] ?? [] as $route) {

            if (preg_match($route['pattern'], $uri, $matches)) {

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

                array_shift($matches);

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

                $controller->$methodName(...$matches);
                return;
            }
        }

        $this->handleNotFound();
    }

    private function handleNotFound(): void
    {
        http_response_code(404);
        $controller = new \App\Controllers\ErrorController();
        $controller->notFound();
    }
}