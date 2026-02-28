<?php

namespace App\Controllers;

use App\Core\Database;
use App\Models\Post;
use App\Helpers\Slug;

class PostController
{
    public function index(): void
    {
        $posts = Post::all();

        require_once __DIR__ . '/../../views/admin/posts/index.php';
    }

    public function create(): void
    {
        require_once __DIR__ . '/../../views/admin/posts/create.php';
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/posts');
            exit;
        }

        $title   = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $author  = $_SESSION['user']['id'] ?? null;

        if (!$title || !$content || !$author) {
            $_SESSION['error'] = "Champs obligatoires manquants";
            header('Location: /admin/posts/create');
            exit;
        }

        $pdo = Database::getPDO();
        $slug = Slug::generateUnique($pdo, $title);

        Post::create([
            'title'     => $title,
            'slug'      => $slug,
            'content'   => $content,
            'author_id' => $author
        ]);

        $_SESSION['success'] = "Article créé avec succès";
        header('Location: /admin/posts');
        exit;
    }
}