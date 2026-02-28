<?php

namespace App\Models;

use App\Core\Database;

class Post
{
    public static function all(): array
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
        return $stmt->fetchAll();
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