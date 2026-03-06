<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    // ----------------------------------------------------------
    // Authentification
    // ----------------------------------------------------------

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

    // ----------------------------------------------------------
    // CRUD
    // ----------------------------------------------------------

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, username, first_name, last_name, company, phone, email, role, created_at
            FROM users
            WHERE id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function all(): array
    {
        $stmt = $this->pdo->query("
            SELECT id, username, first_name, last_name, email, role, created_at
            FROM users
            ORDER BY created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function create(array $data): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO users (username, first_name, last_name, email, password, company, phone, role)
            VALUES (:username, :first_name, :last_name, :email, :password, :company, :phone, 'member')
        ");
        $stmt->execute([
            'username'   => $data['username'],
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => $data['password'],
            'company'    => $data['company'] ?? null,
            'phone'      => $data['phone'] ?? null,
        ]);
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE users
            SET username   = :username,
                first_name = :first_name,
                last_name  = :last_name,
                email      = :email,
                company    = :company,
                phone      = :phone
            WHERE id = :id
        ");
        $stmt->execute([
            'username'   => $data['username'],
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'company'    => $data['company'] ?? null,
            'phone'      => $data['phone'] ?? null,
            'id'         => $id,
        ]);
    }

    public function updatePassword(int $id, string $hashedPassword): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE users SET password = :password WHERE id = :id
        ");
        $stmt->execute(['password' => $hashedPassword, 'id' => $id]);
    }

    public function updateRole(int $id, string $role): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE users SET role = :role WHERE id = :id
        ");
        $stmt->execute(['role' => $role, 'id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    // ----------------------------------------------------------
    // Stats dashboard
    // ----------------------------------------------------------

    public function count(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }

    public function countByRole(string $role): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE role = :role");
        $stmt->execute(['role' => $role]);
        return (int) $stmt->fetchColumn();
    }

    public function existsByEmail(string $email, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $params = ['email' => $email];
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function existsByUsername(string $username, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM users WHERE username = :username";
        $params = ['username' => $username];
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }
}