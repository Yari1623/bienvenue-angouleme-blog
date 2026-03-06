<?php

namespace App\Core;

class Csrf
{
    public static function generate(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf'];
    }

    public static function validate(?string $token): bool
    {
        if (!$token || empty($_SESSION['_csrf'])) {
            return false;
        }

        $valid = hash_equals($_SESSION['_csrf'], $token);

        if ($valid) {
            // Rotation du token après chaque validation réussie
            unset($_SESSION['_csrf']);
        }

        return $valid;
    }
}
