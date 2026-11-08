<?php

/** Admin dashboard + order tracking. */
class AdminController
{
    public function dashboard(): void
    {
        require_admin();
        $orders = new Order();

        render('admin/dashboard', [
            'title'     => 'Dashboard — KABEL Admin',
            'css'       => 'dashboard',
            'active'    => 'dashboard',
            'revenue'   => $orders->revenue(),
            'orderCount' => $orders->count(),
            'productCount' => (new Product())->count(),
            'customerCount' => (new User())->countCustomers(),
            'recent'    => $orders->all(5),
        ]);
    }

    public function orders(): void
    {
        require_admin();
        render('admin/orders', [
            'title'  => 'Orders — KABEL Admin',
            'css'    => 'admin-orders',
            'active' => 'orders',
            'orders' => (new Order())->all(),
        ]);
    }

    public function orderDetail(string $id): void
    {
        require_admin();
        $orders = new Order();
        $order = $orders->find((int) $id);
        if (!$order) {
            not_found();
        }

        render('admin/order_detail', [
            'title'  => 'Order #' . $order['id'] . ' — KABEL Admin',
            'css'    => 'order-detail',
            'active' => 'orders',
            'order'  => $order,
            'items'  => $orders->items((int) $order['id']),
        ]);
    }

    public function updateOrder(string $id): void
    {
        require_admin();
        $orders = new Order();
        $order = $orders->find((int) $id);
        if (!$order) {
            not_found();
        }

        $orderStatus = $_POST['order_status'] ?? '';
        $paymentStatus = $_POST['payment_status'] ?? '';
        if (!in_array($orderStatus, Order::ORDER_STATUSES, true) || !in_array($paymentStatus, Order::PAYMENT_STATUSES, true)) {
            flash('error', 'Invalid status value.');
            redirect('/admin/orders/' . $order['id']);
        }

        $orders->updateStatus((int) $order['id'], $orderStatus, $paymentStatus);
        flash('success', 'Order #' . $order['id'] . ' updated.');
        redirect('/admin/orders/' . $order['id']);
    }
}