<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Post;
use App\Helpers\Slug;
use App\Core\Auth;
class PostController extends Controller
{
    public function index(): void
    {
        $posts = Post::all();

        $this->view('admin/posts/index', [
            'posts' => $posts
        ]);
    }

    public function create(): void
    {
        $this->view('admin/posts/create');
    }

    public function store(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /bienvenue-angouleme-blog/public/admin/posts');
        exit;
    }

    $title   = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    $user = Auth::user();
    $author = $user['id'] ?? null;

    if (!$title || !$content || !$author) {
        $_SESSION['error'] = "Champs obligatoires manquants";
        header('Location: /bienvenue-angouleme-blog/public/admin/posts/create');
        exit;
    }

    $pdo  = Database::getPDO();
    $slug = Slug::generateUnique($pdo, $title);

    Post::create([
        'title'     => $title,
        'slug'      => $slug,
        'content'   => $content,
        'author_id' => $author
    ]);

    $_SESSION['success'] = "Article créé avec succès";
    header('Location: /bienvenue-angouleme-blog/public/admin/posts');
    exit;
}
public function show(string $slug): void
{
    $post = \App\Models\Post::findBySlug($slug);

    if (!$post) {
        http_response_code(404);
        echo "Article introuvable";
        return;
    }

    $this->view('posts/show', [
        'post' => $post
    ]);
}
}