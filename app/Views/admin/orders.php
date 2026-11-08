<main>
    <header>
        <h1>Orders</h1>
    </header>

    <?php if ($orders): ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Order</th>
                <th scope="col">Date</th>
                <th scope="col">Customer</th>
                <th scope="col">Total</th>
                <th scope="col">Payment</th>
                <th scope="col">Status</th>
                <th scope="col"><span class="visually-hidden">Actions</span></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><strong>#<?= (int) $order['id'] ?></strong></td>
                <td><?= e(date('M j, H:i', strtotime($order['created_at']))) ?></td>
                <td><?= e($order['customer_name']) ?></td>
                <td><?= money($order['total']) ?></td>
                <td><span
                        class="tag <?= $order['payment_status'] === 'Paid' ? 'tag-neutral' : 'tag-accent' ?>"><?= e($order['payment_status']) ?></span>
                </td>
                <td><span
                        class="tag <?= $order['order_status'] === 'Cancelled' ? 'tag-outline' : 'tag-neutral' ?>"><?= e($order['order_status']) ?></span>
                </td>
                <td><a class="btn btn-secondary" href="<?= url('/admin/orders/' . $order['id']) ?>">View</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p class="text-muted">No orders yet.</p>
    <?php endif; ?>
</main>