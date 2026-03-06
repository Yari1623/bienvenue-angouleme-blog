<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Place;

class AboutController extends Controller
{
    public function index(): void
    {
        $categoryModel = new Category();
        $categories    = $categoryModel->all();

        $this->view('about/index', [
            'categories' => $categories,
        ]);
    }
}










