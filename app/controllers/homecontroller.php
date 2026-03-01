<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Post;

class HomeController extends Controller
{
    public function index(): void
    {
        $posts = Post::published();

        $this->view('home/index', [
            'posts' => $posts
        ]);
    }
}