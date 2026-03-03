<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Flash;
use App\Core\Csrf;

class LoginController extends Controller
{
    public function show(): void
    {
        $this->view('auth/login');
    }

    public function login(): void
    {
        // ✅ Vérification CSRF AVANT TOUT
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
        }

        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';

        if (Auth::attempt($login, $password)) {
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