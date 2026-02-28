<?php

namespace App\Controllers;

use App\Core\Database;
use App\Helpers\Slug;
use PDO;

class PostController
{
    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }

        $pdo = Database::getInstance();

        $title   = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $author  = $_SESSION['user']['id'] ?? null;

        if (!$title || !$content || !$author) {
            $_SESSION['error'] = "Champs obligatoires manquants";
            header('Location: /admin/posts/create');
            exit;
        }

        // Génération slug unique
        $slug = Slug::generateUnique($pdo, $title);

        $stmt = $pdo->prepare("
            INSERT INTO posts (title, slug, content, author_id, status)
            VALUES (:title, :slug, :content, :author, 'draft')
        ");

        $stmt->execute([
            'title'   => $title,
            'slug'    => $slug,
            'content' => $content,
            'author'  => $author
        ]);

        $_SESSION['success'] = "Article créé avec succès";
        header('Location: /admin/posts');
        exit;
    }
}