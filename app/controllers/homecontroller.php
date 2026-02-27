<?php

namespace App\Controllers;

use App\Core\Controller;
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
}