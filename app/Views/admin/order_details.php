<main>
    <header>
        <h1>Order #<?= (int) $order['id'] ?></h1>
        <a class="btn btn-secondary" href="<?= url('/admin/orders') ?>">← Back to orders</a>
    </header>

    <section class="order-layout">
        <article class="panel">
            <h2>Ordered items</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= e($item['product_name']) ?></td>
                        <td><?= money($item['unit_price']) ?></td>
                        <td><?= (int) $item['quantity'] ?></td>
                        <td><?= money($item['subtotal']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th scope="row" colspan="3">Total</th>
                        <td><strong><?= money($order['total']) ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </article>

        <aside>
            <section class="panel">
                <h2>Customer &amp; delivery</h2>
                <address>
                    <strong><?= e($order['full_name']) ?></strong><br>
                    <?= e($order['customer_email']) ?><br>
                    <?= e($order['phone']) ?><br>
                    <?= e($order['address']) ?>, <?= e($order['city']) ?>
                    <?php if ($order['notes']): ?><br><small>Notes: <?= e($order['notes']) ?></small><?php endif; ?>
                </address>
            </section>

            <form class="panel" action="<?= url('/admin/orders/' . $order['id'] . '/update') ?>" method="post">
                <?= csrf_field() ?>
                <h2>Update order</h2>
                <label class="field"><span>Order status</span>
                    <select class="input" name="order_status">
                        <?php foreach (Order::ORDER_STATUSES as $status): ?>
                        <option<?= $order['order_status'] === $status ? ' selected' : '' ?>><?= e($status) ?></option>
                            <?php endforeach; ?>
                    </select>
                </label>
                <label class="field"><span>Payment status</span>
                    <select class="input" name="payment_status">
                        <?php foreach (Order::PAYMENT_STATUSES as $status): ?>
                        <option<?= $order['payment_status'] === $status ? ' selected' : '' ?>><?= e($status) ?></option>
                            <?php endforeach; ?>
                    </select>
                </label>
                <p><small>Payment method: Cash on delivery (<?= e($order['payment_method']) ?>)</small></p>
                <button class="btn btn-primary btn-block" type="submit">Save changes</button>
            </form>
        </aside>
    </section>
</main>