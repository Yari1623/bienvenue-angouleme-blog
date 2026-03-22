<?php
// app/controllers/PostController.php
 
namespace App\Controllers;
 
use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Place;
use App\Helpers\Slug;
 
/**
 * PostController — Gestion complète des articles.
 *
 * Actions ADMIN : index, create, store, edit, update, toggleStatus, delete
 * Actions PUBLIC : show, comment, like
 */
class PostController extends Controller
{
    // ─────────────────────────────────────────────────────────
    // ADMIN — liste
    // ─────────────────────────────────────────────────────────
 
    /** Liste tous les articles pour l'admin */
    public function index(): void
    {
        $postModel = new Post();
        $this->view('admin/posts/index', [
            'posts' => $postModel->all(),
        ]);
    }
 
    // ─────────────────────────────────────────────────────────
    // ADMIN — création
    // ─────────────────────────────────────────────────────────
 
    /** Affiche le formulaire de création */
    public function create(): void
    {
        $this->view('admin/posts/create', [
            'categories' => (new Category())->all(),
            'places'     => (new Place())->all(),
        ]);
    }
 
    /** Traite la soumission du formulaire de création */
    public function store(): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403); exit('Requête invalide (CSRF)');
        }
 
        $title       = trim($_POST['title']         ?? '');
        $content     = trim($_POST['content']       ?? '');
        $thumbnail   = trim($_POST['thumbnail']     ?? '');
        $categoryId  = (int)($_POST['category_id']  ?? 0) ?: null;
        $placeId     = (int)($_POST['place_id']     ?? 0) ?: null;
        $tags        = trim($_POST['tags']           ?? '');
        $readingTime = (int)($_POST['reading_time']  ?? 0) ?: null;
 
        if (!$title) {
            Flash::error('Le titre est obligatoire.');
            header('Location: ' . BASE_URL . '/admin/posts/create');
            exit;
        }
 
        $pdo       = Database::getPDO();
        $slug      = Slug::generateUnique($pdo, $title);
        $postModel = new Post();
 
        $postId = $postModel->create([
            'title'        => $title,
            'slug'         => $slug,
            'content'      => $content,
            'thumbnail'    => $thumbnail ?: null,
            'author_id'    => Auth::id(),
            'category_id'  => $categoryId,
            'place_id'     => $placeId,
            'tags'         => $tags ?: null,
            'reading_time' => $readingTime,
        ]);
 
        // Sections dynamiques (éditeur par blocs)
        $this->storeSections($postModel, $postId);
 
        Flash::success('Article créé avec succès.');
        header('Location: ' . BASE_URL . '/admin/posts');
        exit;
    }
 
    // ─────────────────────────────────────────────────────────
    // ADMIN — édition
    // ─────────────────────────────────────────────────────────
 
    /** Affiche le formulaire d'édition */
    public function edit(int $id): void
    {
        $postModel = new Post();
        $post      = $postModel->find($id);
 
        if (!$post) {
            Flash::error('Article introuvable.');
            header('Location: ' . BASE_URL . '/admin/posts');
            exit;
        }
 
        $this->view('admin/posts/edit', [
            'post'       => $post,
            'sections'   => $postModel->getSections($id),
            'categories' => (new Category())->all(),
            'places'     => (new Place())->all(),
        ]);
    }
 
    /** Traite la soumission de la modification */
    public function update(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403); exit('Requête invalide (CSRF)');
        }
 
        $postModel = new Post();
 
        if (!$postModel->find($id)) {
            Flash::error('Article introuvable.');
            header('Location: ' . BASE_URL . '/admin/posts');
            exit;
        }
 
        $title       = trim($_POST['title']         ?? '');
        $content     = trim($_POST['content']       ?? '');
        $thumbnail   = trim($_POST['thumbnail']     ?? '');
        $categoryId  = (int)($_POST['category_id']  ?? 0) ?: null;
        $placeId     = (int)($_POST['place_id']     ?? 0) ?: null;
        $tags        = trim($_POST['tags']           ?? '');
        $readingTime = (int)($_POST['reading_time']  ?? 0) ?: null;
 
        if (!$title) {
            Flash::error('Le titre est obligatoire.');
            header('Location: ' . BASE_URL . '/admin/posts/' . $id . '/edit');
            exit;
        }
 
        $postModel->update($id, [
            'title'        => $title,
            'content'      => $content,
            'thumbnail'    => $thumbnail ?: null,
            'category_id'  => $categoryId,
            'place_id'     => $placeId,
            'tags'         => $tags ?: null,
            'reading_time' => $readingTime,
        ]);
 
        // Réécriture complète des sections
        $postModel->deleteSections($id);
        $this->storeSections($postModel, $id);
 
        Flash::success('Article mis à jour.');
        header('Location: ' . BASE_URL . '/admin/posts');
        exit;
    }
 
    // ─────────────────────────────────────────────────────────
    // ADMIN — toggle statut & suppression
    // ─────────────────────────────────────────────────────────
 
    /** Bascule le statut draft ↔ published */
    public function toggleStatus(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403); exit('Requête invalide (CSRF)');
        }
 
        $postModel = new Post();
        $post      = $postModel->find($id);
 
        if (!$post) {
            Flash::error('Article introuvable.');
            header('Location: ' . BASE_URL . '/admin/posts');
            exit;
        }
 
        $newStatus = $post['status'] === 'draft' ? 'published' : 'draft';
        $postModel->updateStatus($id, $newStatus);
 
        Flash::success('Article ' . ($newStatus === 'published' ? 'publié' : 'repassé en brouillon') . '.');
        header('Location: ' . BASE_URL . '/admin/posts');
        exit;
    }
 
    /** Supprime un article et ses sections */
    public function delete(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403); exit('Requête invalide (CSRF)');
        }
 
        $postModel = new Post();
 
        if (!$postModel->find($id)) {
            Flash::error('Article introuvable.');
            header('Location: ' . BASE_URL . '/admin/posts');
            exit;
        }
 
        $postModel->delete($id);
        Flash::success('Article supprimé.');
        header('Location: ' . BASE_URL . '/admin/posts');
        exit;
    }
 
    // ─────────────────────────────────────────────────────────
    // PUBLIC — affichage
    // ─────────────────────────────────────────────────────────
 
    /** Affiche un article publié par son slug */
    public function show(string $slug): void
    {
        $postModel = new Post();
        $post      = $postModel->findBySlug($slug);
 
        if (!$post) {
            http_response_code(404);
            (new ErrorController())->notFound();
            return;
        }
 
        // Enregistrement de la vue (déduplication par user ou IP)
        $postModel->addView($post['id'], Auth::id(), $_SERVER['REMOTE_ADDR'] ?? null);
 
        $sections     = $postModel->getSections($post['id']);
        $commentModel = new Comment();
        $comments     = $commentModel->getApprovedByPost($post['id']);
 
        $userHasLiked = Auth::check()
            ? $postModel->hasLiked($post['id'], Auth::id())
            : false;
 
        $this->view('posts/show', [
            'post'         => array_merge($post, ['user_has_liked' => $userHasLiked]),
            'sections'     => $sections,
            'comments'     => $comments,
        ]);
    }
 
    // ─────────────────────────────────────────────────────────
    // PUBLIC — commentaire
    // ─────────────────────────────────────────────────────────
 
    /** Soumet un commentaire (statut pending par défaut) */
    public function comment(string $slug): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403); exit('Requête invalide (CSRF)');
        }
 
        if (!Auth::check()) {
            Flash::error('Vous devez être connecté pour commenter.');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
 
        $postModel = new Post();
        $post      = $postModel->findBySlug($slug);
 
        if (!$post) { http_response_code(404); exit; }
 
        $content = trim($_POST['content'] ?? '');
 
        if (!$content) {
            Flash::error('Le commentaire ne peut pas être vide.');
            header('Location: ' . BASE_URL . '/article/' . $slug);
            exit;
        }
 
        (new Comment())->create([
            'post_id' => $post['id'],
            'user_id' => Auth::id(),
            'content' => $content,
        ]);
 
        Flash::success('Commentaire envoyé, en attente de modération.');
        header('Location: ' . BASE_URL . '/article/' . $slug);
        exit;
    }
 
    // ─────────────────────────────────────────────────────────
    // PUBLIC — like (toggle)
    // ─────────────────────────────────────────────────────────
 
    /**
     * Ajoute ou retire le like de l'utilisateur sur un article.
     * CORRECTION passe 6 : validation CSRF ajoutée.
     */
    public function like(string $slug): void
    {
        // CORRECTION : CSRF manquant dans la version originale
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403); exit('Requête invalide (CSRF)');
        }
 
        if (!Auth::check()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
 
        $postModel = new Post();
        $post      = $postModel->findBySlug($slug);
 
        if (!$post) { http_response_code(404); exit; }
 
        $userId = Auth::id();
 
        if ($postModel->hasLiked($post['id'], $userId)) {
            $postModel->removeLike($post['id'], $userId);
        } else {
            $postModel->addLike($post['id'], $userId);
        }
 
        header('Location: ' . BASE_URL . '/article/' . $slug);
        exit;
    }
 
    // ─────────────────────────────────────────────────────────
    // Méthode privée — enregistrement des sections (éditeur blocs)
    // ─────────────────────────────────────────────────────────
 
    /**
     * Lit les tableaux section_type[], section_content[], section_media_url[]
     * et insère chaque bloc dans post_sections.
     */
    private function storeSections(Post $postModel, int $postId): void
    {
        $types     = $_POST['section_type']      ?? [];
        $contents  = $_POST['section_content']   ?? [];
        $mediaUrls = $_POST['section_media_url'] ?? [];
 
        foreach ($types as $i => $type) {
            $type     = trim($type);
            $content  = trim($contents[$i]  ?? '');
            $mediaUrl = trim($mediaUrls[$i] ?? '');
 
            if (!$type) continue;
 
            $postModel->addSection(
                $postId,
                $type,
                $content  ?: null,
                $mediaUrl ?: null,
                $i          // position = index d'insertion
            );
        }
    }
}