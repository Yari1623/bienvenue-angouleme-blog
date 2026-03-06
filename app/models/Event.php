<?php

namespace App\Models;

use App\Core\Model;

class Event extends Model
{
    public function all(): array
    {
        $stmt = $this->pdo->query("
            SELECT e.*,
                   COUNT(ei.id) AS interest_count
            FROM events e
            LEFT JOIN event_interests ei ON ei.event_id = e.id
            GROUP BY e.id
            ORDER BY e.event_date ASC
        ");
        return $stmt->fetchAll();
    }

    public function upcoming(int $limit = 5): array
    {
        $stmt = $this->pdo->prepare("
            SELECT e.*,
                   COUNT(ei.id) AS interest_count
            FROM events e
            LEFT JOIN event_interests ei ON ei.event_id = e.id
            WHERE e.event_date >= CURDATE()
            GROUP BY e.id
            ORDER BY e.event_date ASC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO events (title, description, event_date, event_time, location)
            VALUES (:title, :description, :event_date, :event_time, :location)
        ");
        $stmt->execute([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'event_date'  => $data['event_date'],
            'event_time'  => $data['event_time'] ?? null,
            'location'    => $data['location'] ?? null,
        ]);
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE events
            SET title       = :title,
                description = :description,
                event_date  = :event_date,
                event_time  = :event_time,
                location    = :location
            WHERE id = :id
        ");
        $stmt->execute([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'event_date'  => $data['event_date'],
            'event_time'  => $data['event_time'] ?? null,
            'location'    => $data['location'] ?? null,
            'id'          => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM events WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function isInterested(int $eventId, int $userId): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM event_interests
            WHERE event_id = :event_id AND user_id = :user_id
        ");
        $stmt->execute(['event_id' => $eventId, 'user_id' => $userId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function addInterest(int $eventId, int $userId): void
    {
        $stmt = $this->pdo->prepare("
            INSERT IGNORE INTO event_interests (event_id, user_id) VALUES (:event_id, :user_id)
        ");
        $stmt->execute(['event_id' => $eventId, 'user_id' => $userId]);
    }

    public function removeInterest(int $eventId, int $userId): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM event_interests WHERE event_id = :event_id AND user_id = :user_id
        ");
        $stmt->execute(['event_id' => $eventId, 'user_id' => $userId]);
    }

    public function count(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
    }
}