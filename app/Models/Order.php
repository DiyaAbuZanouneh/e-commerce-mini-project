<?php

class Order
{
    public const ORDER_STATUSES = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
    public const PAYMENT_STATUSES = ['Unpaid', 'Paid'];


    public function placeFromCart(int $userId, array $delivery): int
    {
        global $db;
        $db->beginTransaction();
        try {
            $stmt = $db->prepare(
                'SELECT ci.product_id, ci.quantity, p.name, p.price, p.stock
                 FROM cart_items ci
                 JOIN carts c ON c.id = ci.cart_id
                 JOIN products p ON p.id = ci.product_id
                 WHERE c.user_id = ? FOR UPDATE'
            );
            $stmt->execute([$userId]);
            $items = $stmt->fetchAll();
            if (!$items) {
                throw new RuntimeException('Your cart is empty.');
            }

            $total = 0;
            foreach ($items as $item) {
                if ($item['quantity'] > $item['stock']) {
                    throw new RuntimeException("Not enough stock for “{$item['name']}” (only {$item['stock']} left).");
                }
                $total += $item['price'] * $item['quantity'];
            }

            $stmt = $db->prepare(
                "INSERT INTO orders (user_id, full_name, phone, city, address, notes, total, payment_method, payment_status, order_status)
                 VALUES (?, ?, ?, ?, ?, ?, ?, 'COD', 'Unpaid', 'Pending')"
            );
            $stmt->execute([$userId, $delivery['full_name'], $delivery['phone'], $delivery['city'], $delivery['address'], $delivery['notes'], $total]);
            $orderId = (int) $db->lastInsertId();

            $itemStmt = $db->prepare(
                'INSERT INTO order_items (order_id, product_id, product_name, unit_price, quantity) VALUES (?, ?, ?, ?, ?)'
            );
            $stockStmt = $db->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');
            foreach ($items as $item) {
                $itemStmt->execute([$orderId, $item['product_id'], $item['name'], $item['price'], $item['quantity']]);
                $stockStmt->execute([$item['quantity'], $item['product_id']]);
            }

            (new Cart())->clear($userId);
            $db->commit();
            return $orderId;
        } catch (Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function find(int $id): ?array
    {
        global $db;
        $stmt = $db->prepare(
            'SELECT o.*, u.name AS customer_name, u.email AS customer_email
             FROM orders o JOIN users u ON u.id = o.user_id WHERE o.id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function items(int $orderId): array
    {
        global $db;
        $stmt = $db->prepare('SELECT * FROM order_items WHERE order_id = ? ORDER BY id');
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public function forUser(int $userId): array
    {
        global $db;
        $stmt = $db->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC, id DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function all(int $limit = 0): array
    {
        global $db;
        $stmt = $db->prepare(
            'SELECT o.*, u.name AS customer_name FROM orders o JOIN users u ON u.id = o.user_id
             ORDER BY o.created_at DESC, o.id DESC' . ($limit > 0 ? ' LIMIT ' . $limit : '')
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $orderStatus, string $paymentStatus): void
    {
        global $db;
        $stmt = $db->prepare('UPDATE orders SET order_status = ?, payment_status = ? WHERE id = ?');
        $stmt->execute([$orderStatus, $paymentStatus, $id]);
    }

    /* ----- Dashboard metrics ----- */
    public function revenue(): float
    {
        global $db;
        $stmt = $db->prepare("SELECT COALESCE(SUM(total), 0) FROM orders WHERE payment_status = 'Paid'");
        $stmt->execute();
        return (float) $stmt->fetchColumn();
    }

    public function count(): int
    {
        global $db;
        $stmt = $db->prepare('SELECT COUNT(*) FROM orders');
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
}