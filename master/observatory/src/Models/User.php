<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class User
{
    public function __construct(private PDO $db)
    {
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT u.*, s.id AS station_id FROM users u
             LEFT JOIN stations s ON s.user_id = u.id
             WHERE u.email = :email LIMIT 1'
        );
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT u.*, s.id AS station_id FROM users u
             LEFT JOIN stations s ON s.user_id = u.id
             WHERE u.id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(string $email, string $password, string $name): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (email, password_hash, name) VALUES (:email, :hash, :name)'
        );
        $stmt->execute([
            'email' => $email,
            'hash' => password_hash($password, PASSWORD_BCRYPT),
            'name' => $name,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
