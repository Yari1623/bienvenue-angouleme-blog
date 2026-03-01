<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    public function findByLogin(string $login): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM users 
            WHERE email = :login 
               OR username = :login 
            LIMIT 1
        ");

        $stmt->execute(['login' => $login]);

        return $stmt->fetch() ?: null;
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM users 
            WHERE id = :id 
            LIMIT 1
        ");

        $stmt->execute(['id' => $id]);

        return $stmt->fetch() ?: null;
    }

    public function create(array $data): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO users 
            (username, first_name, last_name, email, password, company, phone, role)
            VALUES 
            (:username, :first_name, :last_name, :email, :password, :company, :phone, 'member')
        ");

        $stmt->execute($data);
    }
}