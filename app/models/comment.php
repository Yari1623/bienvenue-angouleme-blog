<?php

namespace App\Models;

use App\Core\Model;

class Comment extends Model
{
    public function create(array $data): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO comments (post_id, user_id, content)
            VALUES (:post_id, :user_id, :content)
        ");

        $stmt->execute($data);
    }

    public function getApprovedByPost(int $postId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT c.*, u.username
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.post_id = :post_id
            AND c.status = 'approved'
            ORDER BY c.created_at DESC
        ");

        $stmt->execute(['post_id' => $postId]);

        return $stmt->fetchAll();
    }

    public function getPending(): array
    {
        $stmt = $this->pdo->query("
            SELECT c.*, p.title, u.username
            FROM comments c
            JOIN posts p ON c.post_id = p.id
            JOIN users u ON c.user_id = u.id
            WHERE c.status = 'pending'
            ORDER BY c.created_at DESC
        ");

        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE comments
            SET status = :status
            WHERE id = :id
        ");

        $stmt->execute([
            'status' => $status,
            'id' => $id
        ]);
    }
}