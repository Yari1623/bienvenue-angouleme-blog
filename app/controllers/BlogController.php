<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Place;
use App\Models\Post;

class BlogController extends Controller
{
    public function index(): void
    {
        $postModel     = new Post();
        $categoryModel = new Category();
        $placeModel    = new Place();

        // Filtres
        $search    = trim($_GET['q']         ?? '');
        $catSlug   = trim($_GET['categorie'] ?? '');
        $placeSlug = trim($_GET['lieu']      ?? '');
        $tag       = trim($_GET['tag']       ?? '');
        $sort      = $_GET['tri']            ?? 'date_desc';

        // Pagination
        $perPageAllowed = [6, 12];
        $perPage = isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $perPageAllowed)
            ? (int)$_GET['per_page'] : 6;
        $currentPage = max(1, (int)($_GET['page'] ?? 1));
        $offset      = ($currentPage - 1) * $perPage;

        // Données
        $posts      = $postModel->search($search, $catSlug, $placeSlug, $tag, $sort, $perPage, $offset);
        $total      = $postModel->countSearch($search, $catSlug, $placeSlug, $tag);
        $totalPages = (int) ceil($total / $perPage);
        $categories = $categoryModel->all();
        $places     = $placeModel->all();

        $this->view('blog/index', [
            'posts'       => $posts,
            'categories'  => $categories,
            'places'      => $places,
            'total'       => $total,
            'totalPages'  => $totalPages,
            'currentPage' => $currentPage,
            'perPage'     => $perPage,
        ]);
    }
}
















