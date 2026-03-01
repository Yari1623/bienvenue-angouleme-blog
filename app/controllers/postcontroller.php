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
public function toggleStatus(int $id): void
{
    $posts = \App\Models\Post::all();

    $currentPost = null;
    foreach ($posts as $post) {
        if ($post['id'] == $id) {
            $currentPost = $post;
            break;
        }
    }

    if (!$currentPost) {
        http_response_code(404);
        echo "Article introuvable";
        return;
    }

    $newStatus = $currentPost['status'] === 'draft'
        ? 'published'
        : 'draft';

    \App\Models\Post::updateStatus($id, $newStatus);

    $_SESSION['success'] = "Statut mis à jour";

    header('Location: /bienvenue-angouleme-blog/public/admin/posts');
    exit;
}
public function edit(int $id): void
{
    $post = \App\Models\Post::find($id);

    if (!$post) {
        http_response_code(404);
        echo "Article introuvable";
        return;
    }

    $this->view('admin/posts/edit', [
        'post' => $post
    ]);
}

public function update(int $id): void
{
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (!$title || !$content) {
        $_SESSION['error'] = "Champs obligatoires manquants";
        header("Location: /bienvenue-angouleme-blog/public/admin/posts/{$id}/edit");
        exit;
    }

    $pdo = \App\Core\Database::getPDO();
    $slug = \App\Helpers\Slug::generateUnique($pdo, $title);

    \App\Models\Post::update($id, [
        'title' => $title,
        'slug' => $slug,
        'content' => $content
    ]);

    $_SESSION['success'] = "Article mis à jour";

    header('Location: /bienvenue-angouleme-blog/public/admin/posts');
    exit;
}
public function delete(int $id): void
{
    $post = \App\Models\Post::find($id);

    if (!$post) {
        http_response_code(404);
        echo "Article introuvable";
        return;
    }

    \App\Models\Post::delete($id);

    $_SESSION['success'] = "Article supprimé";

    header('Location: /bienvenue-angouleme-blog/public/admin/posts');
    exit;
}
}