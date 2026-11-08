<?php

class Cart
{
    private function cartId(int $userId): int
    {
        global $db;
        $stmt = $db->prepare('SELECT id FROM carts WHERE user_id = ?');
        $stmt->execute([$userId]);
        $id = $stmt->fetchColumn();
        if ($id) {
            return (int) $id;
        }
        $stmt = $db->prepare('INSERT INTO carts (user_id) VALUES (?)');
        $stmt->execute([$userId]);
        return (int) $db->lastInsertId();
    }

    public function items(int $userId): array
    {
        global $db;
        $stmt = $db->prepare(
            'SELECT ci.product_id, ci.quantity, p.name, p.price, p.stock, p.image,
                    (p.price * ci.quantity) AS line_total
             FROM cart_items ci
             JOIN carts c ON c.id = ci.cart_id
             JOIN products p ON p.id = ci.product_id
             WHERE c.user_id = ? ORDER BY ci.added_at, ci.id'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function count(int $userId): int
    {
        global $db;
        $stmt = $db->prepare(
            'SELECT COALESCE(SUM(ci.quantity), 0) FROM cart_items ci JOIN carts c ON c.id = ci.cart_id WHERE c.user_id = ?'
        );
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    public function total(array $items): float
    {
        return array_sum(array_column($items, 'line_total'));
    }

    public function add(int $userId, int $productId, int $quantity, int $stock): bool
    {
        global $db;
        if ($stock < 1) {
            return false;
        }
        $cartId = $this->cartId($userId);

        $stmt = $db->prepare('SELECT quantity FROM cart_items WHERE cart_id = ? AND product_id = ?');
        $stmt->execute([$cartId, $productId]);
        $current = (int) $stmt->fetchColumn();
        $newQty = min($stock, $current + max(1, $quantity));

        $stmt = $db->prepare(
            'INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE quantity = VALUES(quantity)'
        );
        $stmt->execute([$cartId, $productId, $newQty]);
        return true;
    }

    public function updateQuantity(int $userId, int $productId, int $quantity): void
    {
        global $db;
        $stmt = $db->prepare(
            'UPDATE cart_items ci JOIN carts c ON c.id = ci.cart_id
             SET ci.quantity = ? WHERE c.user_id = ? AND ci.product_id = ?'
        );
        $stmt->execute([$quantity, $userId, $productId]);
    }

    public function remove(int $userId, int $productId): void
    {
        global $db;
        $stmt = $db->prepare(
            'DELETE ci FROM cart_items ci JOIN carts c ON c.id = ci.cart_id WHERE c.user_id = ? AND ci.product_id = ?'
        );
        $stmt->execute([$userId, $productId]);
    }

    public function clear(int $userId): void
    {
        global $db;
        $stmt = $db->prepare(
            'DELETE ci FROM cart_items ci JOIN carts c ON c.id = ci.cart_id WHERE c.user_id = ?'
        );
        $stmt->execute([$userId]);
    }
}