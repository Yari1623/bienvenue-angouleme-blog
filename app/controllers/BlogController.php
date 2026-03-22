<?php
// app/controllers/BlogController.php
 
namespace App\Controllers;
 
use App\Core\Controller;
use App\Models\Category;
use App\Models\Place;
use App\Models\Post;
 
/**
 * BlogController — Page publique /blog
 *
 * Affiche la liste des articles avec recherche full-text,
 * filtres (catégorie, lieu, tag, tri) et pagination 6/12.
 */
class BlogController extends Controller
{
    public function index(): void
    {
        $postModel     = new Post();
        $categoryModel = new Category();
        $placeModel    = new Place();
 
        // ── Récupération des filtres depuis l'URL ──────────
        $search    = trim($_GET['q']         ?? '');
        $catSlug   = trim($_GET['categorie'] ?? '');
        $placeSlug = trim($_GET['lieu']      ?? '');
        $tag       = trim($_GET['tag']       ?? '');
        $sort      = $_GET['tri']            ?? 'date_desc';
 
        // ── Pagination ─────────────────────────────────────
        // Whitelist des valeurs autorisées pour per_page
        $_pp     = (int)($_GET['per_page'] ?? 6);
        $perPage = in_array($_pp, [6, 12]) ? $_pp : 6;
 
        $currentPage = max(1, (int)($_GET['page'] ?? 1));
        $offset      = ($currentPage - 1) * $perPage;
 
        // ── Données ────────────────────────────────────────
        $posts      = $postModel->search($search, $catSlug, $placeSlug, $tag, $sort, $perPage, $offset);
        $total      = $postModel->countSearch($search, $catSlug, $placeSlug, $tag);
 
        // CORRECTION : max(1, …) pour éviter totalPages = 0
        $totalPages = max(1, (int)ceil($total / $perPage));
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
















