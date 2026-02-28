<?php

namespace App\Helpers;

use PDO;

class Slug
{
    public static function generate(string $title): string
    {
        // Convertir en minuscules
        $slug = mb_strtolower($title, 'UTF-8');

        // Supprimer les accents
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);

        // Supprimer caractères spéciaux
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);

        // Remplacer espaces par tirets
        $slug = preg_replace('/[\s-]+/', '-', $slug);

        // Nettoyer tirets début/fin
        $slug = trim($slug, '-');

        return $slug;
    }

    public static function generateUnique(PDO $pdo, string $title): string
    {
        $baseSlug = self::generate($title);
        $slug = $baseSlug;
        $counter = 1;

        while (self::slugExists($pdo, $slug)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private static function slugExists(PDO $pdo, string $slug): bool
    {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE slug = :slug");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetchColumn() > 0;
    }
}