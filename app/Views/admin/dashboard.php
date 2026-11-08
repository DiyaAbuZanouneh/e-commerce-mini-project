<main>
    <header>
        <h1>Dashboard</h1>
    </header>

    <dl class="stats">
        <section>
            <dt>Total revenue</dt>
            <dd><?= money($revenue) ?></dd>
        </section>
        <section>
            <dt>Orders</dt>
            <dd><?= (int) $orderCount ?></dd>
        </section>
        <section>
            <dt>Products</dt>
            <dd><?= (int) $productCount ?></dd>
        </section>
        <section>
            <dt>Customers</dt>
            <dd><?= (int) $customerCount ?></dd>
        </section>
    </dl>

    <section class="recent">
        <header class="section-head">
            <h2>Latest orders</h2><a href="<?= url('/admin/orders') ?>">All orders →</a>
        </header>
        <?php if ($recent): ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Order</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Total</th>
                    <th scope="col">Payment</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent as $order): ?>
                <tr>
                    <td><a href="<?= url('/admin/orders/' . $order['id']) ?>">#<?= (int) $order['id'] ?></a></td>
                    <td><?= e($order['customer_name']) ?></td>
                    <td><?= money($order['total']) ?></td>
                    <td><span
                            class="tag <?= $order['payment_status'] === 'Paid' ? 'tag-neutral' : 'tag-accent' ?>"><?= e($order['payment_status']) ?></span>
                    </td>
                    <td><span class="tag tag-neutral"><?= e($order['order_status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p class="text-muted">No orders yet.</p>
        <?php endif; ?>
    </section>
</main>