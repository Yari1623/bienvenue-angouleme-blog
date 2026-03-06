<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index(): void
    {
        $commentModel = new Comment();

        $filter   = $_GET['filter'] ?? 'all';
        $comments = match ($filter) {
            'pending'  => $commentModel->getPending(),
            default    => $commentModel->getAll(),
        };

        $this->view('admin/comments/index', [
            'comments' => $comments,
            'filter'   => $filter,
            'pending'  => $commentModel->countPending(),
        ]);
    }

    public function approve(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
        }

        $commentModel = new Comment();

        if (!$commentModel->find($id)) {
            Flash::error('Commentaire introuvable.');
            header('Location: ' . BASE_URL . '/admin/comments');
            exit;
        }

        $commentModel->updateStatus($id, 'approved');
        Flash::success('Commentaire approuvé.');
        header('Location: ' . BASE_URL . '/admin/comments');
        exit;
    }

    public function reject(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
        }

        $commentModel = new Comment();

        if (!$commentModel->find($id)) {
            Flash::error('Commentaire introuvable.');
            header('Location: ' . BASE_URL . '/admin/comments');
            exit;
        }

        $commentModel->updateStatus($id, 'rejected');
        Flash::success('Commentaire refusé.');
        header('Location: ' . BASE_URL . '/admin/comments');
        exit;
    }

    public function delete(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
        }

        $commentModel = new Comment();

        if (!$commentModel->find($id)) {
            Flash::error('Commentaire introuvable.');
            header('Location: ' . BASE_URL . '/admin/comments');
            exit;
        }

        $commentModel->delete($id);
        Flash::success('Commentaire supprimé.');
        header('Location: ' . BASE_URL . '/admin/comments');
        exit;
    }
}