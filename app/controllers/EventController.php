<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Auth;
use App\Models\Event;

class EventController extends Controller
{
    // PUBLIC — agenda
    public function index(): void
    {
        $eventModel = new Event();
        $this->view('events/index', [
            'events' => $eventModel->all(),
        ]);
    }

    // PUBLIC — intérêt (toggle)
    public function toggleInterest(int $id): void
    {
        if (!Auth::check()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $eventModel = new Event();
        $event      = $eventModel->find($id);

        if (!$event) {
            http_response_code(404);
            exit;
        }

        $userId = Auth::id();

        if ($eventModel->isInterested($id, $userId)) {
            $eventModel->removeInterest($id, $userId);
        } else {
            $eventModel->addInterest($id, $userId);
        }

        header('Location: ' . BASE_URL . '/agenda');
        exit;
    }

    // ADMIN — liste
    public function adminIndex(): void
    {
        $eventModel = new Event();
        $this->view('admin/events/index', [
            'events' => $eventModel->all(),
        ]);
    }

    // ADMIN — création
    public function create(): void
    {
        $this->view('admin/events/create');
    }

    public function store(): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
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

        $eventModel = new Event();
        $eventModel->create([
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

    // ADMIN — édition
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

    public function update(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
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

        $eventModel = new Event();
        $eventModel->update($id, [
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

    // ADMIN — suppression
    public function delete(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
        }

        $eventModel = new Event();
        $eventModel->delete($id);

        Flash::success('Événement supprimé.');
        header('Location: ' . BASE_URL . '/admin/events');
        exit;
    }
}