<?php

class OrderController
{
    public function checkout(): void
    {
        $user = require_login();
        $cart = new Cart();
        $items = $cart->items($user['id']);
        if (!$items) {
            flash('error', 'Your cart is empty.');
            redirect('/cart');
        }

        $profile = (new User())->find($user['id']);

        render('checkout', [
            'title'   => 'Checkout — KABEL',
            'css'     => 'checkout',
            'active'  => 'cart',
            'items'   => $items,
            'total'   => $cart->total($items),
            'profile' => $profile,
        ]);
    }

    public function place(): void
    {
        $user = require_login();

        $delivery = [
            'full_name' => trim($_POST['full_name'] ?? ''),
            'phone'     => trim($_POST['phone'] ?? ''),
            'city'      => trim($_POST['city'] ?? ''),
            'address'   => trim($_POST['address'] ?? ''),
            'notes'     => trim($_POST['notes'] ?? '') ?: null,
        ];

        $errors = [];
        if ($delivery['full_name'] === '' || mb_strlen($delivery['full_name']) > 100) {
            $errors[] = 'Please enter your full name.';
        }
        if (!preg_match('/^[0-9+\-\s()]{6,20}$/', $delivery['phone'])) {
            $errors[] = 'Please enter a valid phone number.';
        }
        if ($delivery['city'] === '' || mb_strlen($delivery['city']) > 100) {
            $errors[] = 'Please enter your city.';
        }
        if ($delivery['address'] === '' || mb_strlen($delivery['address']) > 255) {
            $errors[] = 'Please enter your delivery address.';
        }
        // Only Cash on Delivery is supported
        if (($_POST['payment_method'] ?? 'COD') !== 'COD') {
            $errors[] = 'Only Cash on Delivery is available.';
        }

        if ($errors) {
            foreach ($errors as $error) {
                flash('error', $error);
            }
            with_old($_POST);
            redirect('/checkout');
        }

        try {
            $orderId = (new Order())->placeFromCart($user['id'], $delivery);
        } catch (RuntimeException $e) {
            flash('error', $e->getMessage());
            redirect('/cart');
        }

        flash('success', 'Your order has been placed.');
        redirect('/orders/' . $orderId);
    }

    /** Receipt / confirmation page — only the owner (or an admin) can view it. */
    public function show(string $id): void
    {
        $user = require_login();
        $orders = new Order();
        $order = $orders->find((int) $id);

        if (!$order || ($order['user_id'] != $user['id'] && !is_admin())) {
            not_found();
        }

        render('order_receipt', [
            'title'  => 'Order #' . $order['id'] . ' — KABEL',
            'css'    => 'order-confirmation',
            'order'  => $order,
            'items'  => $orders->items((int) $order['id']),
        ]);
    }
}