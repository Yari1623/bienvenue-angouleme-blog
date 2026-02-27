<?php

namespace App\Core;

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);

        $viewPath = __DIR__ . '/../../views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(404);
            echo "Vue introuvable.";
            return;
        }

        require __DIR__ . '/../../views/layouts/main.php';
    }
}