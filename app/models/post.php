<?php

namespace App\Models;

use App\Core\Model;

class Post extends Model
{
    public function count(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM posts");
        $result = $stmt->fetch();
        return (int)$result['total'];
    }
}