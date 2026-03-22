<?php
// app/controllers/UserController.php
 
namespace App\Controllers;
 
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Auth;
use App\Models\User;
 
/**
 * UserController — Gestion admin des utilisateurs.
 *
 * Actions : index, edit, update, updateRole, delete
 */
class UserController extends Controller
{
    /** Liste tous les utilisateurs */
    public function index(): void
    {
        $this->view('admin/users/index', [
            'users' => (new User())->all(),
        ]);
    }
 
    /** Affiche le formulaire de modification d'un utilisateur */
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
 
    /** Met à jour les informations d'un utilisateur */
    public function update(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403); exit('Requête invalide (CSRF)');
        }
 
        $userModel = new User();
 
        if (!$userModel->find($id)) {
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
            Flash::error('Nom d\'utilisateur et email sont obligatoires.');
            header('Location: ' . BASE_URL . '/admin/users/' . $id . '/edit');
            exit;
        }
 
        // Vérifier l'unicité de l'email en excluant l'utilisateur courant
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
 
    /**
     * Change le rôle d'un utilisateur.
     *
     * CORRECTION passe 6 : validation de la valeur du rôle
     * pour éviter l'injection d'une valeur arbitraire.
     */
    public function updateRole(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403); exit('Requête invalide (CSRF)');
        }
 
        // Empêcher un admin de modifier son propre rôle
        if (Auth::id() === $id) {
            Flash::error('Vous ne pouvez pas modifier votre propre rôle.');
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }
 
        // CORRECTION : whitelist des rôles autorisés
        $role = $_POST['role'] ?? '';
        if (!in_array($role, ['admin', 'member'], true)) {
            Flash::error('Rôle invalide.');
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }
 
        (new User())->updateRole($id, $role);
        Flash::success('Rôle mis à jour.');
        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }
 
    /**
     * Supprime un utilisateur.
     * Protection : impossible de se supprimer soi-même.
     */
    public function delete(int $id): void
    {
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            http_response_code(403); exit('Requête invalide (CSRF)');
        }
 
        if (Auth::id() === $id) {
            Flash::error('Vous ne pouvez pas supprimer votre propre compte.');
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }
 
        (new User())->delete($id);
        Flash::success('Utilisateur supprimé.');
        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }
}