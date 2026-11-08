<?php

class Category
{
    public function all(): array
    {
        global $db;
        $stmt = $db->prepare(
            'SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) AS product_count
             FROM categories c ORDER BY c.name'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        global $db;
        $stmt = $db->prepare('SELECT * FROM categories WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function nameExists(string $name, int $exceptId = 0): bool
    {
        global $db;
        $stmt = $db->prepare('SELECT COUNT(*) FROM categories WHERE name = ? AND id <> ?');
        $stmt->execute([$name, $exceptId]);
        return (bool) $stmt->fetchColumn();
    }

    public function create(string $name, ?string $description, ?string $image): int
    {
        global $db;
        $stmt = $db->prepare('INSERT INTO categories (name, description, image) VALUES (?, ?, ?)');
        $stmt->execute([$name, $description, $image]);
        return (int) $db->lastInsertId();
    }

    public function update(int $id, string $name, ?string $description, ?string $image): void
    {
        global $db;
        $stmt = $db->prepare('UPDATE categories SET name = ?, description = ?, image = ? WHERE id = ?');
        $stmt->execute([$name, $description, $image, $id]);
    }

    public function delete(int $id): void
    {
        global $db;
        $stmt = $db->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->execute([$id]);
    }
}