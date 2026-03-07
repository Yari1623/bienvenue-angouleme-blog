<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\Post;
use App\Core\Database;

class HomeController extends Controller
{
    public function index(): void
    {
        $postModel  = new Post();
        $eventModel = new Event();

        // 3 derniers articles publiés
        $latestPosts = $postModel->published(3, 0);

        // 3 articles les plus lus
        $popularPosts = $postModel->getPopular(3);

        // 3 articles les plus commentés
        $mostCommented = $postModel->getMostCommented(3);

        // Stats pour le hero
        $totalPosts   = $postModel->countPublished();
        $totalEvents  = $eventModel->count();
        $totalMembers = $this->getMemberCount();

        $this->view('home/index', [
            'latestPosts'   => $latestPosts,
            'popularPosts'  => $popularPosts,
            'mostCommented' => $mostCommented,
            'totalPosts'    => $totalPosts,
            'totalEvents'   => $totalEvents,
            'totalMembers'  => $totalMembers,
        ]);
    }

    private function getMemberCount(): int
    {
        try {
            $pdo  = Database::getPDO();
            $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role != 'admin'");
            return (int) $stmt->fetchColumn();
        } catch (\Exception $e) {
            return 0;
        }
    }
}
















