<?php
// app/controllers/AdminController.php
 
namespace App\Controllers;
 
use App\Core\Controller;
use App\Core\Database;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
 
/**
 * AdminController — Tableau de bord administration.
 *
 * Collecte toutes les statistiques et données pour le dashboard :
 * - Cartes stats (articles, commentaires, utilisateurs, événements)
 * - Top 5 articles les plus vus
 * - Commentaires en attente
 * - 4 graphiques Chart.js (publications/mois, statuts, catégories, inscriptions)
 *
 * CORRECTION passe 6 : suppression des imports Event et Category inutilisés.
 */
class AdminController extends Controller
{
    public function index(): void
    {
        $pdo          = Database::getPDO();
        $postModel    = new Post();
        $commentModel = new Comment();
        $userModel    = new User();
 
        // ── Cartes statistiques ────────────────────────────
        $stats = [
            'published_posts'  => $this->count($pdo, "SELECT COUNT(*) FROM posts WHERE status='published'"),
            'draft_posts'      => $this->count($pdo, "SELECT COUNT(*) FROM posts WHERE status='draft'"),
            'total_comments'   => $commentModel->count(),
            'pending_comments' => $commentModel->countPending(),
            'total_users'      => $userModel->count(),
            'total_admins'     => $userModel->countByRole('admin'),
            'total_members'    => $userModel->countByRole('member'),
            'total_events'     => $this->count($pdo, "SELECT COUNT(*) FROM events"),
        ];
 
        // ── Top 5 articles les plus vus ────────────────────
        $popularPosts = $pdo->query("
            SELECT p.id, p.title, p.slug, COUNT(pv.id) AS view_count
            FROM posts p
            LEFT JOIN post_views pv ON p.id = pv.post_id
            WHERE p.status = 'published'
            GROUP BY p.id
            ORDER BY view_count DESC
            LIMIT 5
        ")->fetchAll();
 
        // ── Commentaires en attente (pour widget dashboard) ─
        $pendingComments = $commentModel->getPending();
 
        // ── Graphique 1 : publications publiées par mois ───
        $chartRows = $pdo->query("
            SELECT DATE_FORMAT(created_at, '%b %Y') AS label,
                   DATE_FORMAT(created_at, '%Y-%m') AS ym,
                   COUNT(*) AS total
            FROM posts
            WHERE status = 'published'
              AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY ym, label
            ORDER BY ym ASC
        ")->fetchAll();
        $chartLabels = json_encode(array_column($chartRows, 'label'));
        $chartData   = json_encode(array_map('intval', array_column($chartRows, 'total')));
 
        // ── Graphique 2 : répartition par catégorie ────────
        $categoryStats = $pdo->query("
            SELECT c.name, COUNT(p.id) AS count
            FROM categories c
            LEFT JOIN posts p ON p.category_id = c.id AND p.status = 'published'
            GROUP BY c.id, c.name
            ORDER BY count DESC
        ")->fetchAll();
 
        // ── Graphique 3 : inscriptions admins vs membres ───
        $userRows = $pdo->query("
            SELECT DATE_FORMAT(created_at, '%b %Y') AS label,
                   DATE_FORMAT(created_at, '%Y-%m') AS ym,
                   role,
                   COUNT(*) AS total
            FROM users
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY ym, label, role
            ORDER BY ym ASC
        ")->fetchAll();
 
        // Construction des datasets par mois
        $usersMonths    = [];
        $adminsByMonth  = [];
        $membersByMonth = [];
 
        foreach ($userRows as $r) {
            $usersMonths[$r['ym']] = $r['label'];
            if ($r['role'] === 'admin') {
                $adminsByMonth[$r['ym']]  = (int)$r['total'];
            } else {
                $membersByMonth[$r['ym']] = (int)$r['total'];
            }
        }
        ksort($usersMonths); // Tri chronologique
 
        $adminsData  = [];
        $membersData = [];
        foreach (array_keys($usersMonths) as $ym) {
            $adminsData[]  = $adminsByMonth[$ym]  ?? 0;
            $membersData[] = $membersByMonth[$ym] ?? 0;
        }
 
        $usersChartLabels  = json_encode(array_values($usersMonths));
        $usersChartAdmins  = json_encode($adminsData);
        $usersChartMembers = json_encode($membersData);
 
        $this->view('admin/index', compact(
            'stats',
            'popularPosts',
            'pendingComments',
            'chartLabels',
            'chartData',
            'categoryStats',
            'usersChartLabels',
            'usersChartAdmins',
            'usersChartMembers'
        ));
    }
 
    /**
     * Exécute un COUNT(*) SQL simple et retourne le résultat en int.
     * Évite de répéter le même code pour chaque stat.
     */
    private function count(\PDO $pdo, string $sql): int
    {
        return (int) $pdo->query($sql)->fetchColumn();
    }
}