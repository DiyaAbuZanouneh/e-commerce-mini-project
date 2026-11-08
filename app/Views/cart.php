<main class="container">
    <header class="page-title">
        <h1>Your cart</h1>
        <p><?= count($items) ?> <?= count($items) === 1 ? 'item' : 'items' ?></p>
    </header>

    <?php if (!$items): ?>
    <p class="empty">Your cart is empty. <a href="<?= url('/products') ?>">Browse products</a></p>
    <?php else: ?>
    <section class="cart-layout">
        <table class="table cart-table">
            <thead>
                <tr>
                    <th scope="col">Product</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total</th>
                    <th scope="col"><span class="visually-hidden">Remove</span></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): $img = upload_url($item['image']); ?>
                <tr>
                    <td>
                        <figure class="placeholder thumb"><?php if ($img): ?><img src="<?= e($img) ?>"
                                alt=""><?php endif; ?></figure>
                        <a href="<?= url('/products/' . $item['product_id']) ?>"><?= e($item['name']) ?></a>
                    </td>
                    <td><?= money($item['price']) ?></td>
                    <td>
                        <form class="qty-form" action="<?= url('/cart/update') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="product_id" value="<?= (int) $item['product_id'] ?>">
                            <label class="visually-hidden" for="qty-<?= (int) $item['product_id'] ?>">Quantity</label>
                            <input class="input" id="qty-<?= (int) $item['product_id'] ?>" type="number" name="quantity"
                                value="<?= (int) $item['quantity'] ?>" min="1" max="<?= (int) $item['stock'] ?>">
                            <button class="btn btn-secondary" type="submit">Update</button>
                        </form>
                    </td>
                    <td><?= money($item['line_total']) ?></td>
                    <td>
                        <form action="<?= url('/cart/remove') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="product_id" value="<?= (int) $item['product_id'] ?>">
                            <button class="btn btn-ghost" type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <aside class="summary">
            <h2>Order summary</h2>
            <dl>
                <dt>Subtotal</dt>
                <dd><?= money($total) ?></dd>
                <dt class="total">Total</dt>
                <dd class="total"><?= money($total) ?></dd>
            </dl>
            <a class="btn btn-primary btn-lg btn-block" href="<?= url('/checkout') ?>">Proceed to checkout</a>
            <a href="<?= url('/products') ?>">← Continue shopping</a>
        </aside>
    </section>
    <?php endif; ?>
</main>