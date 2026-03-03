<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index(): void
    {
        $commentModel = new Comment();
        $comments = $commentModel->getPending();

        $this->view('admin/comments/index', [
            'comments' => $comments
        ]);
    }

    public function approve(int $id): void
    {
        $commentModel = new Comment();
        $commentModel->updateStatus($id, 'approved');

        header('Location: ' . BASE_URL . '/admin/comments');
        exit;
    }

    public function reject(int $id): void
    {
        $commentModel = new Comment();
        $commentModel->updateStatus($id, 'rejected');

        header('Location: ' . BASE_URL . '/admin/comments');
        exit;
    }
}