<?php
// app/controllers/AccountController.php
 
namespace App\Controllers;
 
use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\User;
 
/**
 * AccountController — Espace "Mon compte" du membre connecté.
 *
 * Permet de modifier ses informations personnelles et son mot de passe.
 */
class AccountController extends Controller
{
    /** Affiche la page Mon compte */
    public function show(): void
    {
        if (!Auth::check()) {
            Flash::error('Connectez-vous pour accéder à votre compte.');
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
 
        $this->view('profil/edit');
    }
 
    /**
     * Met à jour les informations personnelles.
     *
     * CORRECTION passe 6 :
     * - Passe par User::update() au lieu de SQL direct (cohérence MVC)
     * - Suppression de la mise à jour $_SESSION['user'] inutile
     *   (Auth::user() recharge depuis la BDD via le cache statique)
     */
    public function updateInfos(): void
    {
        if (!Auth::check()) {
            header('Location: ' . BASE_URL . '/login'); exit;
        }
 
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            Flash::error('Token invalide.');
            header('Location: ' . BASE_URL . '/compte'); exit;
        }
 
        $username  = trim($_POST['username']   ?? '');
        $email     = trim($_POST['email']      ?? '');
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName  = trim($_POST['last_name']  ?? '');
        $phone     = trim($_POST['phone']      ?? '');
        $company   = trim($_POST['company']    ?? '');
 
        if (empty($username) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::error('Nom d\'utilisateur et email valide requis.');
            header('Location: ' . BASE_URL . '/compte'); exit;
        }
 
        $userModel = new User();
        $userId    = Auth::id();
 
        // Vérifier l'unicité email en excluant l'utilisateur courant
        if ($userModel->existsByEmail($email, $userId)) {
            Flash::error('Cet email est déjà utilisé par un autre compte.');
            header('Location: ' . BASE_URL . '/compte'); exit;
        }
 
        // Vérifier l'unicité username en excluant l'utilisateur courant
        if ($userModel->existsByUsername($username, $userId)) {
            Flash::error('Ce nom d\'utilisateur est déjà pris.');
            header('Location: ' . BASE_URL . '/compte'); exit;
        }
 
        try {
            // CORRECTION : utilise User::update() au lieu de SQL direct
            $userModel->update($userId, [
                'username'   => $username,
                'first_name' => $firstName ?: null,
                'last_name'  => $lastName  ?: null,
                'email'      => $email,
                'phone'      => $phone     ?: null,
                'company'    => $company   ?: null,
            ]);
 
            // CORRECTION : pas de mise à jour $_SESSION inutile
            // Auth::user() recharge les données via la BDD au prochain appel
 
            Flash::success('Informations mises à jour avec succès.');
        } catch (\Exception $e) {
            Flash::error('Erreur lors de la mise à jour.');
        }
 
        header('Location: ' . BASE_URL . '/compte');
        exit;
    }
 
    /**
     * Change le mot de passe après vérification de l'ancien.
     */
    public function updatePassword(): void
    {
        if (!Auth::check()) {
            header('Location: ' . BASE_URL . '/login'); exit;
        }
 
        if (!Csrf::validate($_POST['_csrf'] ?? null)) {
            Flash::error('Token invalide.');
            header('Location: ' . BASE_URL . '/compte'); exit;
        }
 
        $current = $_POST['current_password']     ?? '';
        $new     = $_POST['new_password']         ?? '';
        $confirm = $_POST['new_password_confirm'] ?? '';
 
        if (empty($current) || empty($new) || empty($confirm)) {
            Flash::error('Tous les champs sont requis.');
            header('Location: ' . BASE_URL . '/compte'); exit;
        }
 
        if ($new !== $confirm) {
            Flash::error('Les nouveaux mots de passe ne correspondent pas.');
            header('Location: ' . BASE_URL . '/compte'); exit;
        }
 
        if (strlen($new) < 8) {
            Flash::error('Le nouveau mot de passe doit contenir au moins 8 caractères.');
            header('Location: ' . BASE_URL . '/compte'); exit;
        }
 
        try {
            $pdo  = \App\Core\Database::getPDO();
            $stmt = $pdo->prepare('SELECT password FROM users WHERE id = :id');
            $stmt->execute([':id' => Auth::id()]);
            $row  = $stmt->fetch();
 
            if (!$row || !password_verify($current, $row['password'])) {
                Flash::error('Mot de passe actuel incorrect.');
                header('Location: ' . BASE_URL . '/compte'); exit;
            }
 
            // Utilise le modèle User pour la mise à jour
            (new User())->updatePassword(Auth::id(), password_hash($new, PASSWORD_BCRYPT));
            Flash::success('Mot de passe modifié avec succès.');
 
        } catch (\Exception $e) {
            Flash::error('Erreur lors du changement de mot de passe.');
        }
 
        header('Location: ' . BASE_URL . '/compte');
        exit;
    }
}