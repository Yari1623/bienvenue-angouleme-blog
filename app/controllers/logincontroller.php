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
        // Déjà connecté → redirection
        if (Auth::check()) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        $this->view('auth/login');
    }

    public function login(): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
        }

        $login    = trim($_POST['login']    ?? '');
        $password = $_POST['password'] ?? '';

        if (!$login || !$password) {
            Flash::error('Veuillez remplir tous les champs.');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if (Auth::attempt($login, $password)) {
            Flash::success('Bienvenue !');

            // Redirection selon le rôle
            if (Auth::isAdmin()) {
                header('Location: ' . BASE_URL . '/admin');
            } else {
                header('Location: ' . BASE_URL . '/');
            }
            exit;
        }

        Flash::error('Identifiants incorrects.');
        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    public function logout(): void
    {
        Auth::logout();
        Flash::success('Vous êtes déconnecté.');
        header('Location: ' . BASE_URL . '/');
        exit;
    }
}