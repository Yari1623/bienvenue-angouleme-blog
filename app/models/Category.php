<?php

namespace App\Models;

use App\Core\Model;

class Category extends Model
{
    public function all(): array
    {
        $stmt = $this->pdo->query("
            SELECT c.*,
                   COUNT(p.id) AS post_count
            FROM categories c
            LEFT JOIN posts p ON p.category_id = c.id AND p.status = 'published'
            GROUP BY c.id
            ORDER BY c.name ASC
        ");
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO categories (name, slug) VALUES (:name, :slug)
        ");
        $stmt->execute(['name' => $data['name'], 'slug' => $data['slug']]);
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE categories SET name = :name, slug = :slug WHERE id = :id
        ");
        $stmt->execute(['name' => $data['name'], 'slug' => $data['slug'], 'id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function count(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    }
}