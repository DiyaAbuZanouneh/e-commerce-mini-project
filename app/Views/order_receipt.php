<main class="container">
    <header class="receipt-head">
        <p class="tag tag-accent">Order placed</p>
        <h1>Thank you! Your order is confirmed.</h1>
        <p>Order #<?= (int) $order['id'] ?> · <?= e(date('M j, Y', strtotime($order['created_at']))) ?></p>
    </header>

    <section class="receipt-layout">
        <article>
            <h2>Items</h2>
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
            <h2>Order details</h2>
            <dl>
                <dt>Payment method</dt>
                <dd>Cash on delivery (<?= e($order['payment_method']) ?>)</dd>
                <dt>Payment status</dt>
                <dd><?= e($order['payment_status']) ?></dd>
                <dt>Order status</dt>
                <dd><?= e($order['order_status']) ?></dd>
            </dl>
            <h2>Delivery</h2>
            <address>
                <?= e($order['full_name']) ?><br>
                <?= e($order['phone']) ?><br>
                <?= e($order['address']) ?>, <?= e($order['city']) ?>
                <?php if ($order['notes']): ?><br><small>Notes: <?= e($order['notes']) ?></small><?php endif; ?>
            </address>
        </aside>
    </section>

    <a class="btn btn-primary" href="<?= url('/products') ?>">Continue shopping</a>
</main>