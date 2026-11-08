<?php

class Product
{
    public function paginate(string $search, int $categoryId, int $page, int $perPage): array
    {
        global $db;
        $where = [];
        $params = [];
        if ($search !== '') {
            $where[] = 'p.name LIKE ?';
            $params[] = '%' . addcslashes($search, '%_\\') . '%';
        }
        if ($categoryId > 0) {
            $where[] = 'p.category_id = ?';
            $params[] = $categoryId;
        }
        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $stmt = $db->prepare("SELECT COUNT(*) FROM products p $whereSql");
        $stmt->execute($params);
        $total = (int) $stmt->fetchColumn();
        $offset = max(0, ($page - 1) * $perPage);

        $stmt = $db->prepare(
            "SELECT p.*, c.name AS category_name
             FROM products p LEFT JOIN categories c ON c.id = p.category_id
             $whereSql ORDER BY p.created_at DESC, p.id DESC
             LIMIT " . (int) $perPage . ' OFFSET ' . (int) $offset
        );
        $stmt->execute($params);
        $items = $stmt->fetchAll();

        return ['items' => $items, 'total' => $total];
    }

    public function latest(int $limit): array
    {
        global $db;
        $stmt = $db->prepare(
            'SELECT p.*, c.name AS category_name
             FROM products p LEFT JOIN categories c ON c.id = p.category_id
             ORDER BY p.created_at DESC, p.id DESC LIMIT ' . (int) $limit
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function all(): array
    {
        global $db;
        $stmt = $db->prepare(
            'SELECT p.*, c.name AS category_name
             FROM products p LEFT JOIN categories c ON c.id = p.category_id
             ORDER BY p.id DESC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        global $db;
        $stmt = $db->prepare(
            'SELECT p.*, c.name AS category_name
             FROM products p LEFT JOIN categories c ON c.id = p.category_id WHERE p.id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function count(): int
    {
        global $db;
        $stmt = $db->prepare('SELECT COUNT(*) FROM products');
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function create(array $d): int
    {
        global $db;
        $stmt = $db->prepare(
            'INSERT INTO products (category_id, name, description, price, stock, image) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$d['category_id'], $d['name'], $d['description'], $d['price'], $d['stock'], $d['image']]);
        return (int) $db->lastInsertId();
    }

    public function update(int $id, array $d): void
    {
        global $db;
        $stmt = $db->prepare(
            'UPDATE products SET category_id = ?, name = ?, description = ?, price = ?, stock = ?, image = ? WHERE id = ?'
        );
        $stmt->execute([$d['category_id'], $d['name'], $d['description'], $d['price'], $d['stock'], $d['image'], $id]);
    }

    public function delete(int $id): void
    {
        global $db;
        $stmt = $db->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);
    }
}