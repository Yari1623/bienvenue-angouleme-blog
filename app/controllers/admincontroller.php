<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use App\Models\Category;
use App\Models\Event;

class AdminController extends Controller
{
    public function index(): void
    {
        $postModel    = new Post();
        $commentModel = new Comment();
        $userModel    = new User();
        $eventModel   = new Event();

        // Compteurs principaux
        $stats = [
            'total_posts'      => $postModel->count(),
            'published_posts'  => $postModel->countByStatus('published'),
            'draft_posts'      => $postModel->countByStatus('draft'),
            'total_comments'   => $commentModel->count(),
            'pending_comments' => $commentModel->countPending(),
            'total_users'      => $userModel->count(),
            'total_admins'     => $userModel->countByRole('admin'),
            'total_members'    => $userModel->countByRole('member'),
            'total_events'     => $eventModel->count(),
        ];

        // Articles les plus vus (pour le tableau top articles)
        $popularPosts = $postModel->getPopular(5);

        // Données Chart.js — articles par mois
        $postsPerMonth = $postModel->getPostsPerMonth();
        $chartLabels   = array_column($postsPerMonth, 'month');
        $chartData     = array_column($postsPerMonth, 'total');

        // Derniers commentaires en attente
        $pendingComments = $commentModel->getPending();

        // Prochains événements
        $upcomingEvents = $eventModel->upcoming(3);

        $this->view('admin/index', [
            'stats'           => $stats,
            'popularPosts'    => $popularPosts,
            'chartLabels'     => json_encode($chartLabels),
            'chartData'       => json_encode($chartData),
            'pendingComments' => $pendingComments,
            'upcomingEvents'  => $upcomingEvents,
        ]);
    }
}