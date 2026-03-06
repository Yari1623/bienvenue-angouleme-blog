<?php

namespace App\Core;

use App\Models\User;

class Auth
{
    private static ?array $cachedUser = null;

    public static function attempt(string $login, string $password): bool
    {
        $userModel = new User();
        $user = $userModel->findByLogin($login);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        // Protection contre la fixation de session
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        self::$cachedUser = $user;

        return true;
    }

    public static function user(): ?array
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        // Cache statique : évite une requête BDD à chaque appel
        if (self::$cachedUser !== null) {
            return self::$cachedUser;
        }

        $userModel = new User();
        self::$cachedUser = $userModel->find($_SESSION['user_id']);

        return self::$cachedUser;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function logout(): void
    {
        self::$cachedUser = null;
        unset($_SESSION['user_id']);
        session_regenerate_id(true);
    }

    public static function role(): ?string
    {
        $user = self::user();
        return $user['role'] ?? null;
    }

    public static function isAdmin(): bool
    {
        return self::role() === 'admin';
    }

    public static function isMember(): bool
    {
        return self::role() === 'member';
    }

    public static function id(): ?int
    {
        return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    }
}