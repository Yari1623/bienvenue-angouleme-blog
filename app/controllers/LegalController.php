<?php
// app/controllers/LegalController.php

namespace App\Controllers;

use App\Core\Controller;

class LegalController extends Controller
{
    public function mentions(): void
    {
        $this->view('legal/mentions');
    }

    public function privacy(): void
    {
        $this->view('legal/privacy');
    }

    public function cookies(): void
    {
        $this->view('legal/cookies');
    }

    public function rgpd(): void
    {
        $this->view('legal/rgpd');
    }
}










