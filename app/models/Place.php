<?php

namespace App\Models;

use App\Core\Model;

class Place extends Model
{
    public function all(): array
    {
        $stmt = $this->pdo->query("
            SELECT pl.*,
                   COUNT(p.id) AS post_count
            FROM places pl
            LEFT JOIN posts p ON p.place_id = pl.id AND p.status = 'published'
            GROUP BY pl.id
            ORDER BY pl.name ASC
        ");
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM places WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM places WHERE slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO places (name, slug, description) VALUES (:name, :slug, :description)
        ");
        $stmt->execute([
            'name'        => $data['name'],
            'slug'        => $data['slug'],
            'description' => $data['description'] ?? null,
        ]);
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE places SET name = :name, slug = :slug, description = :description WHERE id = :id
        ");
        $stmt->execute([
            'name'        => $data['name'],
            'slug'        => $data['slug'],
            'description' => $data['description'] ?? null,
            'id'          => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM places WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}