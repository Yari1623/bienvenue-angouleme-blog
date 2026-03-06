<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function index(): void
    {
        $userModel = new User();
        $this->view('admin/users/index', [
            'users' => $userModel->all(),
        ]);
    }

    public function edit(int $id): void
    {
        $userModel = new User();
        $user      = $userModel->find($id);

        if (!$user) {
            Flash::error('Utilisateur introuvable.');
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        $this->view('admin/users/edit', ['user' => $user]);
    }

    public function update(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
        }

        $userModel = new User();
        $user      = $userModel->find($id);

        if (!$user) {
            Flash::error('Utilisateur introuvable.');
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        $username  = trim($_POST['username']   ?? '');
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName  = trim($_POST['last_name']  ?? '');
        $email     = trim($_POST['email']      ?? '');
        $company   = trim($_POST['company']    ?? '');
        $phone     = trim($_POST['phone']      ?? '');

        if (!$username || !$email) {
            Flash::error('Username et email sont obligatoires.');
            header('Location: ' . BASE_URL . '/admin/users/' . $id . '/edit');
            exit;
        }

        if ($userModel->existsByEmail($email, $id)) {
            Flash::error('Cet email est déjà utilisé.');
            header('Location: ' . BASE_URL . '/admin/users/' . $id . '/edit');
            exit;
        }

        $userModel->update($id, [
            'username'   => $username,
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
            'company'    => $company ?: null,
            'phone'      => $phone   ?: null,
        ]);

        Flash::success('Utilisateur mis à jour.');
        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }

    public function updateRole(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
        }

        // Empêcher de modifier son propre rôle
        if (Auth::id() === $id) {
            Flash::error('Vous ne pouvez pas modifier votre propre rôle.');
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        $role      = $_POST['role'] ?? 'member';
        $userModel = new User();
        $userModel->updateRole($id, $role);

        Flash::success('Rôle mis à jour.');
        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }

    public function delete(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('Requête invalide (CSRF)');
        }

        // Empêcher de se supprimer soi-même
        if (Auth::id() === $id) {
            Flash::error('Vous ne pouvez pas supprimer votre propre compte.');
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        $userModel = new User();
        $userModel->delete($id);

        Flash::success('Utilisateur supprimé.');
        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }
}