<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Database;

class AccountController extends Controller
{
    public function show(): void
    {
        if (!Auth::check()) {
            Flash::set('error', 'Connectez-vous pour accéder à votre compte.');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->view('profil/edit');
    }

    public function updateInfos(): void
    {
        if (!Auth::check()) { header('Location: ' . BASE_URL . '/login'); exit; }

        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            Flash::set('error', 'Token invalide.');
            header('Location: ' . BASE_URL . '/compte');
            exit;
        }

        $username  = trim($_POST['username']   ?? '');
        $email     = trim($_POST['email']      ?? '');
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName  = trim($_POST['last_name']  ?? '');
        $phone     = trim($_POST['phone']      ?? '');
        $company   = trim($_POST['company']    ?? '');

        if (empty($username) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::set('error', 'Nom d\'utilisateur et email valide requis.');
            header('Location: ' . BASE_URL . '/compte');
            exit;
        }

        try {
            $pdo  = Database::getPDO();
            $stmt = $pdo->prepare('
                UPDATE users
                SET username   = :username,
                    email      = :email,
                    first_name = :first_name,
                    last_name  = :last_name,
                    phone      = :phone,
                    company    = :company
                WHERE id = :id
            ');
            $stmt->execute([
                ':username'   => $username,
                ':email'      => $email,
                ':first_name' => $firstName ?: null,
                ':last_name'  => $lastName  ?: null,
                ':phone'      => $phone     ?: null,
                ':company'    => $company   ?: null,
                ':id'         => Auth::id(),
            ]);

            // Mettre à jour la session
            $_SESSION['user']['username']   = $username;
            $_SESSION['user']['email']      = $email;
            $_SESSION['user']['first_name'] = $firstName;
            $_SESSION['user']['last_name']  = $lastName;

            Flash::set('success', 'Informations mises à jour avec succès.');
        } catch (\Exception $e) {
            Flash::set('error', 'Erreur lors de la mise à jour. Vérifiez que le pseudo/email n\'est pas déjà utilisé.');
        }

        header('Location: ' . BASE_URL . '/compte');
        exit;
    }

    public function updatePassword(): void
    {
        if (!Auth::check()) { header('Location: ' . BASE_URL . '/login'); exit; }

        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            Flash::set('error', 'Token invalide.');
            header('Location: ' . BASE_URL . '/compte');
            exit;
        }

        $current = $_POST['current_password']      ?? '';
        $new     = $_POST['new_password']          ?? '';
        $confirm = $_POST['new_password_confirm']  ?? '';

        if (empty($current) || empty($new) || empty($confirm)) {
            Flash::set('error', 'Tous les champs sont requis.');
            header('Location: ' . BASE_URL . '/compte');
            exit;
        }

        if ($new !== $confirm) {
            Flash::set('error', 'Les nouveaux mots de passe ne correspondent pas.');
            header('Location: ' . BASE_URL . '/compte');
            exit;
        }

        if (strlen($new) < 8) {
            Flash::set('error', 'Le nouveau mot de passe doit contenir au moins 8 caractères.');
            header('Location: ' . BASE_URL . '/compte');
            exit;
        }

        // Vérifier le mot de passe actuel
        try {
            $pdo  = Database::getPDO();
            $stmt = $pdo->prepare('SELECT password FROM users WHERE id = :id');
            $stmt->execute([':id' => Auth::id()]);
            $row  = $stmt->fetch();

            if (!$row || !password_verify($current, $row['password'])) {
                Flash::set('error', 'Mot de passe actuel incorrect.');
                header('Location: ' . BASE_URL . '/compte');
                exit;
            }

            $hash = password_hash($new, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('UPDATE users SET password = :password WHERE id = :id');
            $stmt->execute([':password' => $hash, ':id' => Auth::id()]);

            Flash::set('success', 'Mot de passe modifié avec succès.');
        } catch (\Exception $e) {
            Flash::set('error', 'Erreur lors du changement de mot de passe.');
        }

        header('Location: ' . BASE_URL . '/compte');
        exit;
    }
}