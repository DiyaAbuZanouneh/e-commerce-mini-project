<?php

class CartController
{
    public function index(): void
    {
        $user = require_login();
        $cart = new Cart();
        $items = $cart->items($user['id']);

        render('cart', [
            'title'  => 'Your cart — KABEL',
            'css'    => 'cart',
            'active' => 'cart',
            'items'  => $items,
            'total'  => $cart->total($items),
        ]);
    }

    public function add(): void
    {
        $user = require_login();
        $product = (new Product())->find((int) ($_POST['product_id'] ?? 0));
        if (!$product) {
            not_found();
        }

        $added = (new Cart())->add($user['id'], (int) $product['id'], (int) ($_POST['quantity'] ?? 1), (int) $product['stock']);
        flash($added ? 'success' : 'error', $added ? '“' . $product['name'] . '” was added to your cart.' : 'Sorry, this product is out of stock.');
        redirect($added ? '/cart' : '/products/' . $product['id']);
    }

    public function update(): void
    {
        $user = require_login();
        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 1);

        $product = (new Product())->find($productId);
        if ($product) {
            $quantity = max(1, min($quantity, (int) $product['stock']));
            (new Cart())->updateQuantity($user['id'], $productId, $quantity);
            flash('success', 'Cart updated.');
        }
        redirect('/cart');
    }

    public function remove(): void
    {
        $user = require_login();
        (new Cart())->remove($user['id'], (int) ($_POST['product_id'] ?? 0));
        flash('success', 'Item removed from your cart.');
        redirect('/cart');
    }
}