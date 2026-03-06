<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Event;

class HomeController extends Controller
{
    private const PER_PAGE = 9;

    public function index(): void
    {
        $postModel = new Post();
        $page      = max(1, (int) ($_GET['page'] ?? 1));
        $offset    = ($page - 1) * self::PER_PAGE;
        $total     = $postModel->countPublished();
        $posts     = $postModel->published(self::PER_PAGE, $offset);

        $categoryModel = new Category();
        $eventModel    = new Event();

        $this->view('home/index', [
            'posts'          => $posts,
            'categories'     => $categoryModel->all(),
            'upcomingEvents' => $eventModel->upcoming(3),
            'currentPage'    => $page,
            'totalPages'     => (int) ceil($total / self::PER_PAGE),
        ]);
    }
}
