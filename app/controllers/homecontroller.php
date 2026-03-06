<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\Post;

class HomeController extends Controller
{
    public function index(): void
    {
        $postModel     = new Post();
        $categoryModel = new Category();
        $eventModel    = new Event();

        // ── Sélecteur 6 / 12 articles par page
        $perPageAllowed = [6, 12];
        $perPage = isset($_GET['per_page']) && in_array((int)$_GET['per_page'], $perPageAllowed)
            ? (int)$_GET['per_page']
            : 6;

        // ── Page courante
        $currentPage = max(1, (int)($_GET['page'] ?? 1));
        $offset      = ($currentPage - 1) * $perPage;

        // ── Données
        $posts      = $postModel->published($perPage, $offset);
        $total      = $postModel->countPublished();
        $totalPages = (int) ceil($total / $perPage);
        $categories = $categoryModel->all();
        $upcomingEvents = $eventModel->upcoming(3);

        $this->view('home/index', [
            'posts'          => $posts,
            'categories'     => $categories,
            'upcomingEvents' => $upcomingEvents,
            'currentPage'    => $currentPage,
            'totalPages'     => $totalPages,
            'total'          => $total,
            'perPage'        => $perPage,
        ]);
    }
}