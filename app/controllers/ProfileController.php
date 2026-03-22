<?php
// app/controllers/ProfileController.php
 
namespace App\Controllers;
 
use App\Core\Auth;
use App\Core\Controller;
use App\Core\Flash;
use App\Core\Database;
use App\Models\Post;
use App\Models\Event;
 
/**
 * ProfileController — Page publique "Mon profil" du membre connecté.
 *
 * Affiche les likes, historique de lecture, événements suivis
 * et le nombre de commentaires postés.
 */
class ProfileController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) {
            Flash::error('Connectez-vous pour accéder à votre profil.');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
 
        $userId     = Auth::id();
        $postModel  = new Post();
        $eventModel = new Event();
 
        $userLikes        = $postModel->getLikedByUser($userId);
        $recentViews      = $postModel->getViewedByUser($userId, 6);
        $interestedEvents = $eventModel->getInterestedByUser($userId);
 
        // CORRECTION : retourne directement un int au lieu d'un array
        $userComments = $this->getCommentCount($userId);
 
        $this->view('profil/index', [
            'userLikes'        => $userLikes,
            'recentViews'      => $recentViews,
            'interestedEvents' => $interestedEvents,
            'userComments'     => $userComments,
        ]);
    }
 
    /**
     * Retourne le nombre de commentaires postés par l'utilisateur.
     *
     * CORRECTION passe 6 : retourne un int directement
     * (l'ancienne version retournait un array_fill inutile).
     *
     * La vue peut utiliser directement $userComments comme entier.
     */
    private function getCommentCount(int $userId): int
    {
        try {
            $pdo  = Database::getPDO();
            $stmt = $pdo->prepare('SELECT COUNT(*) AS total FROM comments WHERE user_id = :uid');
            $stmt->execute([':uid' => $userId]);
            $row  = $stmt->fetch();
            return (int)($row['total'] ?? 0);
        } catch (\Exception $e) {
            return 0;
        }
    }
}




























