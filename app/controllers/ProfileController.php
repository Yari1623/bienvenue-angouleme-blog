<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Core\Database;
use App\Models\Post;
use App\Models\Event;

class ProfileController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) {
            Flash::set('error', 'Connectez-vous pour accéder à votre profil.');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $userId     = Auth::id();
        $postModel  = new Post();
        $eventModel = new Event();

        $userLikes        = $postModel->getLikedByUser($userId);
        $recentViews      = $postModel->getViewedByUser($userId, 6);
        $interestedEvents = $eventModel->getInterestedByUser($userId);
        $userComments     = $this->getCommentCount($userId);

        $this->view('profil/index', [
            'userLikes'        => $userLikes,
            'recentViews'      => $recentViews,
            'interestedEvents' => $interestedEvents,
            'userComments'     => $userComments,
        ]);
    }

    private function getCommentCount(int $userId): array
    {
        try {
            $pdo  = Database::getPDO();
            $stmt = $pdo->prepare('SELECT COUNT(*) AS total FROM comments WHERE user_id = :uid');
            $stmt->execute([':uid' => $userId]);
            $row  = $stmt->fetch();
            return array_fill(0, (int)($row['total'] ?? 0), true);
        } catch (\Exception $e) {
            return [];
        }
    }
}




























