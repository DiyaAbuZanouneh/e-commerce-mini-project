<?php

class User
{
    public function find(int $id): ?array
    {
        global $db;
        $stmt = $db->prepare('SELECT id, name, email, phone, address, role, created_at FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        global $db;
        $stmt = $db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $name, string $email, string $password): int
    {
        global $db;
        $stmt = $db->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
        return (int) $db->lastInsertId();
    }

    public function countCustomers(): int
    {
        global $db;
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE role = 'customer'");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
}