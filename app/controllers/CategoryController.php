<?php
 // app/controllers/CategoryController.php

namespace App\Controllers;
 
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\Category;
use App\Models\Post;
use App\Helpers\Slug;
use App\Core\Database;
 
class CategoryController extends Controller
{
    // ADMIN — liste
    public function index(): void
    {
        $categoryModel = new Category();
        $this->view('admin/categories/index', [
            'categories' => $categoryModel->all(),
        ]);
    }
 
    // ADMIN — création
    public function create(): void
    {
        $this->view('admin/categories/create');
    }
 
    public function store(): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
        }
 
        $name = trim($_POST['name'] ?? '');
 
        if (!$name) {
            Flash::error('Le nom est obligatoire.');
            header('Location: ' . BASE_URL . '/admin/categories/create');
            exit;
        }
 
        $slug          = Slug::generate($name);
        $categoryModel = new Category();
 
        try {
            $categoryModel->create(['name' => $name, 'slug' => $slug]);
            Flash::success('Catégorie créée.');
        } catch (\PDOException $e) {
            Flash::error('Cette catégorie existe déjà.');
        }
 
        header('Location: ' . BASE_URL . '/admin/categories');
        exit;
    }
 
    // ADMIN — édition
    public function edit(int $id): void
    {
        $categoryModel = new Category();
        $category      = $categoryModel->find($id);
 
        if (!$category) {
            Flash::error('Catégorie introuvable.');
            header('Location: ' . BASE_URL . '/admin/categories');
            exit;
        }
 
        $this->view('admin/categories/edit', ['category' => $category]);
    }
 
    public function update(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
        }
 
        $name = trim($_POST['name'] ?? '');
 
        if (!$name) {
            Flash::error('Le nom est obligatoire.');
            header('Location: ' . BASE_URL . '/admin/categories/' . $id . '/edit');
            exit;
        }
 
        $slug          = Slug::generate($name);
        $categoryModel = new Category();
 
        try {
            $categoryModel->update($id, ['name' => $name, 'slug' => $slug]);
            Flash::success('Catégorie mise à jour.');
        } catch (\PDOException $e) {
            Flash::error('Ce nom est déjà utilisé.');
        }
 
        header('Location: ' . BASE_URL . '/admin/categories');
        exit;
    }
 
    // ADMIN — suppression
    public function delete(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
        }
 
        $categoryModel = new Category();
        $categoryModel->delete($id);
 
        Flash::success('Catégorie supprimée.');
        header('Location: ' . BASE_URL . '/admin/categories');
        exit;
    }
 
    // PUBLIC — articles par catégorie
    public function show(string $slug): void
    {
        $categoryModel = new Category();
        $category      = $categoryModel->findBySlug($slug);
 
        if (!$category) {
            http_response_code(404);
            $error = new \App\Controllers\ErrorController();
            $error->notFound();
            return;
        }
 
        $postModel = new Post();
 
        // Sélecteur 6 ou 12 — défaut 6
        $_pp     = (int)($_GET['per_page'] ?? 6);
        $perPage = in_array($_pp, [6, 12]) ? $_pp : 6;
 
        // Compter uniquement les articles publiés de CETTE catégorie
        $total       = $postModel->countByCategory($category['id']);
        $totalPages  = $total > 0 ? (int)ceil($total / $perPage) : 1;
        $currentPage = max(1, min((int)($_GET['page'] ?? 1), $totalPages));
        $offset      = ($currentPage - 1) * $perPage;
 
        $posts = $postModel->byCategory($category['id'], $perPage, $offset);
 
        $this->view('posts/by-category', [
            'category'    => $category,
            'posts'       => $posts,
            'currentPage' => $currentPage,
            'totalPages'  => $totalPages,
            'perPage'     => $perPage,
            'total'       => $total,
        ]);
    }
}