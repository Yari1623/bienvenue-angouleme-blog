<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Models\Post;

class HomeController extends Controller
{
    public function index(): void
    {
        $postModel = new Post();
        $totalPosts = $postModel->count();

        $this->view('home/index', [
            'message' => 'Connexion BDD OK',
            'totalPosts' => $totalPosts
        ]);
    }

    public function triggerFlash(): void
    {
        Flash::success('Flash déclenché avec succès');

        header('Location: /bienvenue-angouleme-blog/public');
        exit;
    }
}