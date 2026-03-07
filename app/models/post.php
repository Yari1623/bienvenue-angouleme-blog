<?php

namespace App\Models;

use App\Core\Model;

class Post extends Model
{
    // ----------------------------------------------------------
    // Lecture — liste
    // ----------------------------------------------------------

    public function all(): array
    {
        $stmt = $this->pdo->query("
            SELECT p.*,
                   u.username AS author_name,
                   c.name     AS category_name,
                   c.slug     AS category_slug,
                   pl.name    AS place_name
            FROM posts p
            LEFT JOIN users      u  ON p.author_id   = u.id
            LEFT JOIN categories c  ON p.category_id = c.id
            LEFT JOIN places     pl ON p.place_id    = pl.id
            ORDER BY p.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function published(int $limit = 10, int $offset = 0): array
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*,
                   u.username AS author_name,
                   c.name     AS category_name,
                   c.slug     AS category_slug,
                   pl.name    AS place_name,
                   (SELECT COUNT(*) FROM likes      WHERE post_id = p.id) AS like_count,
                   (SELECT COUNT(*) FROM post_views WHERE post_id = p.id) AS view_count,
                   (SELECT COUNT(*) FROM comments   WHERE post_id = p.id AND status = 'approved') AS comment_count
            FROM posts p
            LEFT JOIN users      u  ON p.author_id   = u.id
            LEFT JOIN categories c  ON p.category_id = c.id
            LEFT JOIN places     pl ON p.place_id    = pl.id
            WHERE p.status = 'published'
            ORDER BY p.created_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit',  $limit,  \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countPublished(): int
    {
        return (int) $this->pdo->query("
            SELECT COUNT(*) FROM posts WHERE status = 'published'
        ")->fetchColumn();
    }

    public function byCategory(int $categoryId, int $limit = 10, int $offset = 0): array
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*,
                   u.username AS author_name,
                   c.name     AS category_name,
                   c.slug     AS category_slug,
                   pl.name    AS place_name
            FROM posts p
            LEFT JOIN users      u  ON p.author_id   = u.id
            LEFT JOIN categories c  ON p.category_id = c.id
            LEFT JOIN places     pl ON p.place_id    = pl.id
            WHERE p.status = 'published'
              AND p.category_id = :category_id
            ORDER BY p.created_at DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':category_id', $categoryId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit',       $limit,      \PDO::PARAM_INT);
        $stmt->bindValue(':offset',      $offset,     \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ----------------------------------------------------------
    // Lecture — article unique
    // ----------------------------------------------------------

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*,
                   u.username AS author_name,
                   c.name     AS category_name,
                   c.slug     AS category_slug,
                   pl.name    AS place_name
            FROM posts p
            LEFT JOIN users      u  ON p.author_id   = u.id
            LEFT JOIN categories c  ON p.category_id = c.id
            LEFT JOIN places     pl ON p.place_id    = pl.id
            WHERE p.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*,
                   u.username AS author_name,
                   c.name     AS category_name,
                   c.slug     AS category_slug,
                   pl.name    AS place_name,
                   (SELECT COUNT(*) FROM likes      WHERE post_id = p.id) AS like_count,
                   (SELECT COUNT(*) FROM post_views WHERE post_id = p.id) AS view_count,
                   (SELECT COUNT(*) FROM comments   WHERE post_id = p.id AND status = 'approved') AS comment_count
            FROM posts p
            LEFT JOIN users      u  ON p.author_id   = u.id
            LEFT JOIN categories c  ON p.category_id = c.id
            LEFT JOIN places     pl ON p.place_id    = pl.id
            WHERE p.slug = :slug
              AND p.status = 'published'
            LIMIT 1
        ");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch() ?: null;
    }

    // ----------------------------------------------------------
    // CRUD
    // ----------------------------------------------------------

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO posts
                (title, slug, content, thumbnail, author_id, category_id, place_id, tags, reading_time, status)
            VALUES
                (:title, :slug, :content, :thumbnail, :author_id, :category_id, :place_id, :tags, :reading_time, 'draft')
        ");
        $stmt->execute([
            'title'        => $data['title'],
            'slug'         => $data['slug'],
            'content'      => $data['content'] ?? '',
            'thumbnail'    => $data['thumbnail'] ?? null,
            'author_id'    => $data['author_id'],
            'category_id'  => $data['category_id'] ?? null,
            'place_id'     => $data['place_id'] ?? null,
            'tags'         => $data['tags'] ?? null,
            'reading_time' => $data['reading_time'] ?? null,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE posts
            SET title        = :title,
                content      = :content,
                thumbnail    = :thumbnail,
                category_id  = :category_id,
                place_id     = :place_id,
                tags         = :tags,
                reading_time = :reading_time
            WHERE id = :id
        ");
        $stmt->execute([
            'title'        => $data['title'],
            'content'      => $data['content'] ?? '',
            'thumbnail'    => $data['thumbnail'] ?? null,
            'category_id'  => $data['category_id'] ?? null,
            'place_id'     => $data['place_id'] ?? null,
            'tags'         => $data['tags'] ?? null,
            'reading_time' => $data['reading_time'] ?? null,
            'id'           => $id,
        ]);
    }

    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE posts SET status = :status WHERE id = :id
        ");
        $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM posts WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    // ----------------------------------------------------------
    // Sections (éditeur par blocs)
    // ----------------------------------------------------------

    public function getSections(int $postId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM post_sections
            WHERE post_id = :post_id
            ORDER BY position ASC
        ");
        $stmt->execute(['post_id' => $postId]);
        return $stmt->fetchAll();
    }

    public function addSection(int $postId, string $type, ?string $content, ?string $mediaUrl, int $position): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO post_sections (post_id, type, content, media_url, position)
            VALUES (:post_id, :type, :content, :media_url, :position)
        ");
        $stmt->execute([
            'post_id'   => $postId,
            'type'      => $type,
            'content'   => $content,
            'media_url' => $mediaUrl,
            'position'  => $position,
        ]);
    }

    public function deleteSections(int $postId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM post_sections WHERE post_id = :post_id");
        $stmt->execute(['post_id' => $postId]);
    }

    // ----------------------------------------------------------
    // Likes
    // ----------------------------------------------------------

    public function hasLiked(int $postId, int $userId): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM likes WHERE post_id = :post_id AND user_id = :user_id
        ");
        $stmt->execute(['post_id' => $postId, 'user_id' => $userId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function addLike(int $postId, int $userId): void
    {
        $stmt = $this->pdo->prepare("
            INSERT IGNORE INTO likes (post_id, user_id) VALUES (:post_id, :user_id)
        ");
        $stmt->execute(['post_id' => $postId, 'user_id' => $userId]);
    }

    public function removeLike(int $postId, int $userId): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM likes WHERE post_id = :post_id AND user_id = :user_id
        ");
        $stmt->execute(['post_id' => $postId, 'user_id' => $userId]);
    }

    public function getLikeCount(int $postId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM likes WHERE post_id = :post_id");
        $stmt->execute(['post_id' => $postId]);
        return (int) $stmt->fetchColumn();
    }

    // ----------------------------------------------------------
    // Vues
    // ----------------------------------------------------------

    public function addView(int $postId, ?int $userId, ?string $ip): void
    {
        if ($userId) {
            $check = $this->pdo->prepare("
                SELECT COUNT(*) FROM post_views WHERE post_id = :post_id AND user_id = :user_id
            ");
            $check->execute(['post_id' => $postId, 'user_id' => $userId]);
            if ((int) $check->fetchColumn() > 0) return;
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO post_views (post_id, user_id, ip_address)
            VALUES (:post_id, :user_id, :ip_address)
        ");
        $stmt->execute([
            'post_id'    => $postId,
            'user_id'    => $userId,
            'ip_address' => $ip,
        ]);
    }

    // ----------------------------------------------------------
    // Stats dashboard
    // ----------------------------------------------------------

    public function count(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
    }

    public function countByStatus(string $status): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM posts WHERE status = :status");
        $stmt->execute(['status' => $status]);
        return (int) $stmt->fetchColumn();
    }

    public function getPostsPerMonth(): array
    {
        $stmt = $this->pdo->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS month,
                   COUNT(*) AS total
            FROM posts
            GROUP BY month
            ORDER BY month ASC
            LIMIT 12
        ");
        return $stmt->fetchAll();
    }

    // ----------------------------------------------------------
    // Articles populaires (les plus lus)
    // ----------------------------------------------------------

    public function getPopular(int $limit = 5): array
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*,
                   u.username  AS author_name,
                   c.name      AS category_name,
                   c.slug      AS category_slug,
                   pl.name     AS place_name,
                   COUNT(DISTINCT pv.id) AS view_count,
                   COUNT(DISTINCT cm.id) AS comment_count
            FROM posts p
            LEFT JOIN users      u  ON p.author_id   = u.id
            LEFT JOIN categories c  ON p.category_id = c.id
            LEFT JOIN places     pl ON p.place_id    = pl.id
            LEFT JOIN post_views pv ON pv.post_id    = p.id
            LEFT JOIN comments   cm ON cm.post_id    = p.id AND cm.status = 'approved'
            WHERE p.status = 'published'
            GROUP BY p.id
            ORDER BY view_count DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ----------------------------------------------------------
    // Articles les plus commentés
    // ----------------------------------------------------------

    public function getMostCommented(int $limit = 3): array
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*,
                   u.username  AS author_name,
                   c.name      AS category_name,
                   c.slug      AS category_slug,
                   pl.name     AS place_name,
                   COUNT(cm.id) AS comment_count
            FROM posts p
            LEFT JOIN users      u  ON p.author_id   = u.id
            LEFT JOIN categories c  ON p.category_id = c.id
            LEFT JOIN places     pl ON p.place_id    = pl.id
            LEFT JOIN comments   cm ON cm.post_id    = p.id AND cm.status = 'approved'
            WHERE p.status = 'published'
            GROUP BY p.id
            ORDER BY comment_count DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ----------------------------------------------------------
    // Recherche avec filtres (page Blog)
    // ----------------------------------------------------------

    public function search(
        string $q         = '',
        string $catSlug   = '',
        string $placeSlug = '',
        string $tag       = '',
        string $sort      = 'date_desc',
        int    $limit     = 6,
        int    $offset    = 0
    ): array {
        $where  = ["p.status = 'published'"];
        $params = [];

        if ($q) {
            $where[]       = "(p.title LIKE :q OR p.content LIKE :q2)";
            $params[':q']  = '%' . $q . '%';
            $params[':q2'] = '%' . $q . '%';
        }
        if ($catSlug) {
            $where[]        = "c.slug = :cat";
            $params[':cat'] = $catSlug;
        }
        if ($placeSlug) {
            $where[]          = "pl.slug = :place";
            $params[':place'] = $placeSlug;
        }
        if ($tag) {
            $where[]        = "p.tags LIKE :tag";
            $params[':tag'] = '%' . $tag . '%';
        }

        $orderBy = match($sort) {
            'date_asc' => 'p.created_at ASC',
            'vues'     => 'view_count DESC',
            'comments' => 'comment_count DESC',
            default    => 'p.created_at DESC',
        };

        $whereStr = implode(' AND ', $where);

        $stmt = $this->pdo->prepare("
            SELECT p.*,
                   u.username  AS author_name,
                   c.name      AS category_name,
                   c.slug      AS category_slug,
                   pl.name     AS place_name,
                   (SELECT COUNT(*) FROM likes      WHERE post_id = p.id) AS like_count,
                   (SELECT COUNT(*) FROM post_views WHERE post_id = p.id) AS view_count,
                   (SELECT COUNT(*) FROM comments   WHERE post_id = p.id AND status = 'approved') AS comment_count
            FROM posts p
            LEFT JOIN users      u  ON p.author_id   = u.id
            LEFT JOIN categories c  ON p.category_id = c.id
            LEFT JOIN places     pl ON p.place_id    = pl.id
            WHERE {$whereStr}
            ORDER BY {$orderBy}
            LIMIT :limit OFFSET :offset
        ");

        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit',  $limit,  \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ----------------------------------------------------------
    // Compter les résultats de recherche (pagination blog)
    // ----------------------------------------------------------

    public function countSearch(
        string $q         = '',
        string $catSlug   = '',
        string $placeSlug = '',
        string $tag       = ''
    ): int {
        $where  = ["p.status = 'published'"];
        $params = [];

        if ($q) {
            $where[]       = "(p.title LIKE :q OR p.content LIKE :q2)";
            $params[':q']  = '%' . $q . '%';
            $params[':q2'] = '%' . $q . '%';
        }
        if ($catSlug) {
            $where[]        = "c.slug = :cat";
            $params[':cat'] = $catSlug;
        }
        if ($placeSlug) {
            $where[]          = "pl.slug = :place";
            $params[':place'] = $placeSlug;
        }
        if ($tag) {
            $where[]        = "p.tags LIKE :tag";
            $params[':tag'] = '%' . $tag . '%';
        }

        $whereStr = implode(' AND ', $where);

        $stmt = $this->pdo->prepare("
            SELECT COUNT(DISTINCT p.id)
            FROM posts p
            LEFT JOIN categories c  ON p.category_id = c.id
            LEFT JOIN places     pl ON p.place_id    = pl.id
            WHERE {$whereStr}
        ");
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    // ----------------------------------------------------------
    // Profil utilisateur
    // ----------------------------------------------------------

    public function getLikedByUser(int $userId, int $limit = 10): array
    {
        $stmt = $this->pdo->prepare("
            SELECT p.id, p.title, p.slug, p.thumbnail, p.created_at,
                   c.name AS category_name
            FROM likes l
            JOIN posts p ON p.id = l.post_id
            LEFT JOIN categories c ON c.id = p.category_id
            WHERE l.user_id = :uid AND p.status = 'published'
            ORDER BY l.created_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':uid',   $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit,  \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getViewedByUser(int $userId, int $limit = 6): array
    {
        $stmt = $this->pdo->prepare("
            SELECT p.id, p.title, p.slug, p.thumbnail, p.created_at,
                   c.name AS category_name,
                   MAX(pv.viewed_at) AS viewed_at
            FROM post_views pv
            JOIN posts p ON p.id = pv.post_id
            LEFT JOIN categories c ON c.id = p.category_id
            WHERE pv.user_id = :uid AND p.status = 'published'
            GROUP BY p.id, p.title, p.slug, p.thumbnail, p.created_at, c.name
            ORDER BY viewed_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':uid',   $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit,  \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}