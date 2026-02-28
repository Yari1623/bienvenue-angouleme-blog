<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Flash;

class LoginController extends Controller
{
    public function show(): void
    {
        $this->view('auth/login');
    }

    public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (Auth::attempt($email, $password)) {
            Flash::success('Connexion réussie');
            header('Location: /bienvenue-angouleme-blog/public');
            exit;
        }

        Flash::error('Identifiants incorrects');
        header('Location: /bienvenue-angouleme-blog/public/login');
        exit;
    }

    public function logout(): void
    {
        Auth::logout();
        Flash::success('Déconnexion réussie');
        header('Location: /bienvenue-angouleme-blog/public');
        exit;
    }
}