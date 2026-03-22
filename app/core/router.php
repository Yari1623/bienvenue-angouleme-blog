<?php
// app/core/Router.php
 
namespace App\Core;
 
/**
 * Routeur HTTP minimaliste.
 *
 * Enregistre les routes GET/POST avec support :
 *   - des segments dynamiques  {param} → ([^/]+)
 *   - de la protection auth    ($auth = true)
 *   - de la protection rôle    ($role = 'admin'|'member'…)
 *
 * CORRECTION passe 6 : la redirection en cas de non-authentification
 * utilise désormais BASE_URL au lieu d'un chemin hardcodé.
 */
class Router
{
    /** @var array<string, list<array>> Routes indexées par méthode HTTP */
    private array $routes = [];
 
    // ─────────────────────────────────────────────────────────
    // Enregistrement des routes
    // ─────────────────────────────────────────────────────────
 
    /** Enregistre une route GET */
    public function get(string $uri, string $action, bool $auth = false, ?string $role = null): void
    {
        $this->addRoute('GET', $uri, $action, $auth, $role);
    }
 
    /** Enregistre une route POST */
    public function post(string $uri, string $action, bool $auth = false, ?string $role = null): void
    {
        $this->addRoute('POST', $uri, $action, $auth, $role);
    }
 
    /**
     * Compile l'URI en regex et stocke la route.
     * Ex : "admin/posts/{id}/edit" → "#^admin/posts/([^/]+)/edit$#"
     */
    private function addRoute(string $method, string $uri, string $action, bool $auth, ?string $role): void
    {
        $uri     = trim($uri, '/');
        $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '([^/]+)', $uri);
        $pattern = "#^{$pattern}$#";
 
        $this->routes[$method][] = [
            'pattern' => $pattern,
            'action'  => $action,
            'auth'    => $auth,
            'role'    => $role,
        ];
    }
 
    // ─────────────────────────────────────────────────────────
    // Dispatch
    // ─────────────────────────────────────────────────────────
 
    /**
     * Parcourt les routes enregistrées, vérifie les droits,
     * puis instancie le controller et appelle la méthode.
     *
     * @param string $uri URI nettoyée (sans base path, sans slash de début/fin)
     */
    public function dispatch(string $uri): void
    {
        $uri    = trim($uri, '/');
        $method = $_SERVER['REQUEST_METHOD'];
 
        foreach ($this->routes[$method] ?? [] as $route) {
 
            if (!preg_match($route['pattern'], $uri, $matches)) {
                continue;
            }
 
            // ── Contrôle authentification ──────────────────
            if ($route['auth'] && !Auth::check()) {
                // CORRECTION : BASE_URL au lieu de chemin hardcodé
                header('Location: ' . BASE_URL . '/login');
                exit;
            }
 
            // ── Contrôle rôle ──────────────────────────────
            if ($route['role']) {
                $user = Auth::user();
                if (!$user || $user['role'] !== $route['role']) {
                    http_response_code(403);
                    echo "403 — Accès interdit";
                    exit;
                }
            }
 
            // ── Extraction des paramètres dynamiques ───────
            array_shift($matches); // supprimer la correspondance globale
 
            // ── Résolution Controller@méthode ──────────────
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
 
            // Appel avec les paramètres de l'URL (ex: $id, $slug)
            $controller->$methodName(...$matches);
            return;
        }
 
        $this->handleNotFound();
    }
 
    /** Renvoie une réponse 404 via ErrorController */
    private function handleNotFound(): void
    {
        http_response_code(404);
        (new \App\Controllers\ErrorController())->notFound();
    }
}