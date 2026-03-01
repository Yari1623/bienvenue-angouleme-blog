<?php

namespace App\Core;

use App\Models\User;

class Auth
{
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

        $_SESSION['user_id'] = $user['id'];

        return true;
    }

    public static function user(): ?array
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        $userModel = new User();
        return $userModel->find($_SESSION['user_id']);
    }

    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id']);
    }
}