<main class="container">
    <header class="page-title">
        <h1>My profile</h1>
    </header>

    <section class="profile-card">
        <dl>
            <dt>Full name</dt>
            <dd><?= e($profile['name']) ?></dd>
            <dt>Email</dt>
            <dd><?= e($profile['email']) ?></dd>
            <dt>Phone</dt>
            <dd><?= e($profile['phone'] ?: '—') ?></dd>
            <dt>Address</dt>
            <dd><?= e($profile['address'] ?: '—') ?></dd>
            <dt>Member since</dt>
            <dd><?= e(date('M j, Y', strtotime($profile['created_at']))) ?></dd>
        </dl>
        <form action="<?= url('/logout') ?>" method="post">
            <?= csrf_field() ?>
            <button class="btn btn-secondary" type="submit">Logout</button>
        </form>
    </section>

    <section class="my-orders">
        <h2>My orders</h2>
        <?php if ($orders): ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Order</th>
                    <th scope="col">Date</th>
                    <th scope="col">Total</th>
                    <th scope="col">Payment</th>
                    <th scope="col">Status</th>
                    <th scope="col"><span class="visually-hidden">Receipt</span></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><strong>#<?= (int) $order['id'] ?></strong></td>
                    <td><?= e(date('M j, Y', strtotime($order['created_at']))) ?></td>
                    <td><?= money($order['total']) ?></td>
                    <td><span
                            class="tag <?= $order['payment_status'] === 'Paid' ? 'tag-neutral' : 'tag-accent' ?>"><?= e($order['payment_status']) ?></span>
                    </td>
                    <td><span class="tag tag-neutral"><?= e($order['order_status']) ?></span></td>
                    <td><a href="<?= url('/orders/' . $order['id']) ?>">Receipt</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p class="text-muted">You have not placed any orders yet.</p>
        <?php endif; ?>
    </section>
</main>