<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\Csrf;
use App\Models\User;

class RegisterController extends Controller
{
    public function show(): void
    {
        $this->view('auth/register');
    }

    public function register(): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
        }

        $username  = trim($_POST['username']   ?? '');
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName  = trim($_POST['last_name']  ?? '');
        $email     = trim($_POST['email']      ?? '');
        $password  = $_POST['password']        ?? '';
        $company   = trim($_POST['company']    ?? '');
        $phone     = trim($_POST['phone']      ?? '');

        if (!$username || !$firstName || !$lastName || !$email || !$password) {
            Flash::error('Veuillez remplir tous les champs obligatoires.');
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::error('Adresse email invalide.');
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        if (strlen($password) < 8) {
            Flash::error('Le mot de passe doit contenir au moins 8 caractères.');
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        $userModel = new User();

        if ($userModel->existsByEmail($email)) {
            Flash::error('Cette adresse email est déjà utilisée.');
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        if ($userModel->existsByUsername($username)) {
            Flash::error('Ce nom d\'utilisateur est déjà pris.');
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        try {
            $userModel->create([
                'username'   => $username,
                'first_name' => $firstName,
                'last_name'  => $lastName,
                'email'      => $email,
                'password'   => password_hash($password, PASSWORD_DEFAULT),
                'company'    => $company ?: null,
                'phone'      => $phone   ?: null,
            ]);
        } catch (\PDOException $e) {
            Flash::error('Une erreur est survenue lors de l\'inscription.');
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        Flash::success('Inscription réussie ! Vous pouvez vous connecter.');
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}