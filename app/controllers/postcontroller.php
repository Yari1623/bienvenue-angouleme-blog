<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;
use App\Core\Csrf;
use App\Models\Post;
use App\Models\Comment;
use App\Helpers\Slug;

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
        // ✅ CSRF
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
        }

        $title   = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        $user = Auth::user();
        $author = $user['id'] ?? null;

        if (!$title || !$content || !$author) {
            $_SESSION['error'] = "Champs obligatoires manquants";
            header('Location: ' . BASE_URL . '/admin/posts/create');
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
        header('Location: ' . BASE_URL . '/admin/posts');
        exit;
    }

    public function show(string $slug): void
    {
        $post = Post::findBySlug($slug);

        if (!$post) {
            http_response_code(404);
            echo "Article introuvable";
            return;
        }

        $commentModel = new Comment();
        $comments = $commentModel->getApprovedByPost($post['id']);

        $this->view('posts/show', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

    public function comment(string $slug): void
    {
        // ✅ CSRF
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
        }

        if (!Auth::check()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $post = Post::findBySlug($slug);

        if (!$post) {
            http_response_code(404);
            exit;
        }

        $content = trim($_POST['content'] ?? '');

        if (!$content) {
            header('Location: ' . BASE_URL . '/article/' . $slug);
            exit;
        }

        $commentModel = new Comment();

        $commentModel->create([
            'post_id' => $post['id'],
            'user_id' => Auth::user()['id'],
            'content' => $content
        ]);

        header('Location: ' . BASE_URL . '/article/' . $slug);
        exit;
    }

    public function toggleStatus(int $id): void
    {
        // ✅ CSRF
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
        }

        $post = Post::find($id);

        if (!$post) {
            http_response_code(404);
            echo "Article introuvable";
            return;
        }

        $newStatus = $post['status'] === 'draft'
            ? 'published'
            : 'draft';

        Post::updateStatus($id, $newStatus);

        $_SESSION['success'] = "Statut mis à jour";
        header('Location: ' . BASE_URL . '/admin/posts');
        exit;
    }

    public function edit(int $id): void
    {
        $post = Post::find($id);

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
        // ✅ CSRF
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
        }

        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if (!$title || !$content) {
            $_SESSION['error'] = "Champs obligatoires manquants";
            header("Location: " . BASE_URL . "/admin/posts/{$id}/edit");
            exit;
        }

        $pdo = Database::getPDO();
        $slug = Slug::generateUnique($pdo, $title);

        Post::update($id, [
            'title' => $title,
            'slug' => $slug,
            'content' => $content
        ]);

        $_SESSION['success'] = "Article mis à jour";
        header('Location: ' . BASE_URL . '/admin/posts');
        exit;
    }

    public function delete(int $id): void
    {
        // ✅ CSRF
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
        }

        $post = Post::find($id);

        if (!$post) {
            http_response_code(404);
            echo "Article introuvable";
            return;
        }

        Post::delete($id);

        $_SESSION['success'] = "Article supprimé";
        header('Location: ' . BASE_URL . '/admin/posts');
        exit;
    }
}