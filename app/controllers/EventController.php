<?php
// app/controllers/EventController.php
 
namespace App\Controllers;
 
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Auth;
use App\Models\Event;
 
/**
 * EventController — Agenda public + gestion admin des événements.
 *
 * Routes publiques  : index, toggleInterest
 * Routes admin      : adminIndex, create, store, edit, update, delete
 */
class EventController extends Controller
{
    // ─────────────────────────────────────────────────────────
    // PUBLIC
    // ─────────────────────────────────────────────────────────
 
    /** Affiche la page publique Agenda (tous les événements) */
    public function index(): void
    {
        $this->view('events/index', [
            'events' => (new Event())->all(),
        ]);
    }
 
    /**
     * Toggle intérêt pour un événement (ajoute ou retire).
     * CORRECTION passe 6 : validation CSRF ajoutée.
     */
    public function toggleInterest(int $id): void
    {
        // CORRECTION : CSRF manquant dans la version originale
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403); exit('Requête invalide (CSRF)');
        }
 
        if (!Auth::check()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
 
        $eventModel = new Event();
        $event      = $eventModel->find($id);
 
        if (!$event) { http_response_code(404); exit; }
 
        $userId = Auth::id();
 
        if ($eventModel->isInterested($id, $userId)) {
            $eventModel->removeInterest($id, $userId);
        } else {
            $eventModel->addInterest($id, $userId);
        }
 
        header('Location: ' . BASE_URL . '/agenda');
        exit;
    }
 
    // ─────────────────────────────────────────────────────────
    // ADMIN — liste
    // ─────────────────────────────────────────────────────────
 
    /** Liste tous les événements pour l'admin */
    public function adminIndex(): void
    {
        $this->view('admin/events/index', [
            'events' => (new Event())->all(),
        ]);
    }
 
    // ─────────────────────────────────────────────────────────
    // ADMIN — création
    // ─────────────────────────────────────────────────────────
 
    /** Affiche le formulaire de création d'événement */
    public function create(): void
    {
        $this->view('admin/events/create');
    }
 
    /** Traite la soumission du formulaire de création */
    public function store(): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403); exit('Requête invalide (CSRF)');
        }
 
        $title       = trim($_POST['title']       ?? '');
        $description = trim($_POST['description'] ?? '');
        $eventDate   = trim($_POST['event_date']  ?? '');
        $eventTime   = trim($_POST['event_time']  ?? '');
        $location    = trim($_POST['location']    ?? '');
 
        if (!$title || !$eventDate) {
            Flash::error('Le titre et la date sont obligatoires.');
            header('Location: ' . BASE_URL . '/admin/events/create');
            exit;
        }
 
        (new Event())->create([
            'title'       => $title,
            'description' => $description ?: null,
            'event_date'  => $eventDate,
            'event_time'  => $eventTime  ?: null,
            'location'    => $location   ?: null,
        ]);
 
        Flash::success('Événement créé.');
        header('Location: ' . BASE_URL . '/admin/events');
        exit;
    }
 
    // ─────────────────────────────────────────────────────────
    // ADMIN — édition
    // ─────────────────────────────────────────────────────────
 
    /** Affiche le formulaire de modification */
    public function edit(int $id): void
    {
        $eventModel = new Event();
        $event      = $eventModel->find($id);
 
        if (!$event) {
            Flash::error('Événement introuvable.');
            header('Location: ' . BASE_URL . '/admin/events');
            exit;
        }
 
        $this->view('admin/events/edit', ['event' => $event]);
    }
 
    /** Traite la soumission de la modification */
    public function update(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403); exit('Requête invalide (CSRF)');
        }
 
        $title       = trim($_POST['title']       ?? '');
        $description = trim($_POST['description'] ?? '');
        $eventDate   = trim($_POST['event_date']  ?? '');
        $eventTime   = trim($_POST['event_time']  ?? '');
        $location    = trim($_POST['location']    ?? '');
 
        if (!$title || !$eventDate) {
            Flash::error('Le titre et la date sont obligatoires.');
            header('Location: ' . BASE_URL . '/admin/events/' . $id . '/edit');
            exit;
        }
 
        (new Event())->update($id, [
            'title'       => $title,
            'description' => $description ?: null,
            'event_date'  => $eventDate,
            'event_time'  => $eventTime  ?: null,
            'location'    => $location   ?: null,
        ]);
 
        Flash::success('Événement mis à jour.');
        header('Location: ' . BASE_URL . '/admin/events');
        exit;
    }
 
    // ─────────────────────────────────────────────────────────
    // ADMIN — suppression
    // ─────────────────────────────────────────────────────────
 
    /** Supprime un événement et ses intérêts (CASCADE BDD) */
    public function delete(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403); exit('Requête invalide (CSRF)');
        }
 
        (new Event())->delete($id);
        Flash::success('Événement supprimé.');
        header('Location: ' . BASE_URL . '/admin/events');
        exit;
    }
}