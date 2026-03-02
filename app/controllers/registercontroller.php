<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Models\User;

class RegisterController extends Controller
{
    public function show(): void
    {
        $this->view('auth/register');
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /bienvenue-angouleme-blog/public/register');
            exit;
        }

        $username   = trim($_POST['username'] ?? '');
        $firstName  = trim($_POST['first_name'] ?? '');
        $lastName   = trim($_POST['last_name'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $password   = $_POST['password'] ?? '';
        $company    = trim($_POST['company'] ?? '');
        $phone      = trim($_POST['phone'] ?? '');

        if (!$username || !$firstName || !$lastName || !$email || !$password) {
            Flash::error('Veuillez remplir tous les champs obligatoires.');
           header('Location: /bienvenue-angouleme-blog/public/register');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::error('Email invalide.');
            header('Location: /bienvenue-angouleme-blog/public/register');
            exit;
        }

        if (strlen($password) < 8) {
            Flash::error('Le mot de passe doit contenir au moins 8 caractères.');
            header('Location: /bienvenue-angouleme-blog/public/register');
            exit;
        }

        $userModel = new User();

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $userModel->create([
                'username'   => $username,
                'first_name' => $firstName,
                'last_name'  => $lastName,
                'email'      => $email,
                'password'   => $hashedPassword,
                'company'    => $company ?: null,
                'phone'      => $phone ?: null
            ]);
        } catch (\PDOException $e) {
            Flash::error('Username ou email déjà utilisé.');
            header('Location: /bienvenue-angouleme-blog/public/register');
            exit;
        }

        Flash::success('Inscription réussie. Vous pouvez vous connecter.');
        header('Location: /bienvenue-angouleme-blog/public/login');
        exit;
    }
}