<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Post
{
    public static function all(): array
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public static function published(): array
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            SELECT * FROM posts
            WHERE status = 'published'
            ORDER BY created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function findBySlug(string $slug): ?array
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            SELECT * FROM posts
            WHERE slug = :slug
            AND status = 'published'
            LIMIT 1
        ");
        $stmt->execute(['slug' => $slug]);
        $post = $stmt->fetch();

        return $post ?: null;
    }

    public static function create(array $data): void
    {
        $pdo = Database::getPDO();

        $stmt = $pdo->prepare("
            INSERT INTO posts (title, slug, content, author_id, status)
            VALUES (:title, :slug, :content, :author_id, 'draft')
        ");

        $stmt->execute($data);
    }
}