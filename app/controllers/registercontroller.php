<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Flash;
use App\Core\Csrf;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Affiche le formulaire d'inscription
     */
    public function show(): void
    {
        $this->view('auth/register', [
            'pageTitle' => 'Créer un compte — Bienvenue à Angoulême',
        ]);
    }

    /**
     * Traite l'inscription
     * Toutes les validations sont côté SERVEUR
     */
    public function register(): void
    {
        // ── 1. Protection CSRF ─────────────────────────────────────────────
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide.');
        }

        // ── 2. Récupération et nettoyage des champs ────────────────────────
        $username  = trim($_POST['username']         ?? '');
        $firstName = trim($_POST['first_name']       ?? '');
        $lastName  = trim($_POST['last_name']        ?? '');
        $email     = trim($_POST['email']            ?? '');
        $password  = $_POST['password']              ?? '';
        $confirm   = $_POST['password_confirm']      ?? '';

        // ── 3. Validations côté serveur ────────────────────────────────────

        // Champs obligatoires
        if (empty($username) || empty($email) || empty($password)) {
            Flash::error('Tous les champs obligatoires (*) doivent être remplis.');
            header('Location: ' . BASE_URL . '/register'); exit;
        }

        // Format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::error('L\'adresse email n\'est pas valide.');
            header('Location: ' . BASE_URL . '/register'); exit;
        }

        // Validation de la confirmation du mot de passe côté SERVEUR
        if ($password !== $confirm) {
            Flash::error('Les mots de passe ne correspondent pas.');
            header('Location: ' . BASE_URL . '/register'); exit;
        }

        // Longueur minimale du mot de passe
        if (strlen($password) < 8) {
            Flash::error('Le mot de passe doit contenir au moins 8 caractères.');
            header('Location: ' . BASE_URL . '/register'); exit;
        }

        // Longueur maximale du username
        if (strlen($username) > 80) {
            Flash::error('Le nom d\'utilisateur ne peut pas dépasser 80 caractères.');
            header('Location: ' . BASE_URL . '/register'); exit;
        }

        // ── 4. Unicité email et username ───────────────────────────────────
        $user = new User();

        if ($user->existsByEmail($email)) {
            Flash::error('Cette adresse email est déjà utilisée.');
            header('Location: ' . BASE_URL . '/register'); exit;
        }

        if ($user->existsByUsername($username)) {
            Flash::error('Ce nom d\'utilisateur est déjà pris.');
            header('Location: ' . BASE_URL . '/register'); exit;
        }

        // ── 5. Création du compte ──────────────────────────────────────────
        $user->create([
            'username'   => $username,
            'first_name' => $firstName ?: null,
            'last_name'  => $lastName  ?: null,
            'email'      => $email,
            'password'   => password_hash($password, PASSWORD_BCRYPT),
            'role'       => 'member',
        ]);

        // ── 6. Redirection avec message de succès ──────────────────────────
        Flash::success('Compte créé avec succès ! Vous pouvez maintenant vous connecter.');
        header('Location: ' . BASE_URL . '/login'); exit;
    }
}