<?php

namespace App\Models;

use App\Core\Model;

class Comment extends Model
{
    public function create(array $data): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO comments (post_id, user_id, content, status)
            VALUES (:post_id, :user_id, :content, 'pending')
        ");
        $stmt->execute([
            'post_id' => $data['post_id'],
            'user_id' => $data['user_id'],
            'content' => $data['content'],
        ]);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM comments WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function getApprovedByPost(int $postId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT c.*, u.username
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.post_id = :post_id
              AND c.status = 'approved'
            ORDER BY c.created_at ASC
        ");
        $stmt->execute(['post_id' => $postId]);
        return $stmt->fetchAll();
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT c.*, u.username, p.title AS post_title, p.slug AS post_slug
            FROM comments c
            JOIN users u ON c.user_id = u.id
            JOIN posts  p ON c.post_id  = p.id
            ORDER BY c.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function getPending(): array
    {
        $stmt = $this->pdo->query("
            SELECT c.*, u.username, p.title AS post_title, p.slug AS post_slug
            FROM comments c
            JOIN users u ON c.user_id = u.id
            JOIN posts  p ON c.post_id  = p.id
            WHERE c.status = 'pending'
            ORDER BY c.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE comments SET status = :status WHERE id = :id
        ");
        $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM comments WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    // Stats dashboard
    public function count(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM comments")->fetchColumn();
    }

    public function countPending(): int
    {
        return (int) $this->pdo->query("
            SELECT COUNT(*) FROM comments WHERE status = 'pending'
        ")->fetchColumn();
    }
}