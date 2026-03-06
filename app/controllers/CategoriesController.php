<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Place;

class CategoriesController extends Controller
{
    public function index(): void
    {
        $categoryModel = new Category();
        $placeModel    = new Place();

        $categories = $categoryModel->all();
        $places     = $placeModel->all();

        $this->view('categories/index', [
            'categories' => $categories,
            'places'     => $places,
        ]);
    }
}










