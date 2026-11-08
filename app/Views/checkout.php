<main class="container">
    <header class="page-title">
        <h1>Checkout</h1>
    </header>

    <form class="checkout-layout" action="<?= url('/checkout') ?>" method="post">
        <?= csrf_field() ?>
        <section class="checkout-form">
            <fieldset>
                <legend>Delivery details</legend>
                <label class="field"><span>Full name</span>
                    <input class="input" type="text" name="full_name" maxlength="100"
                        value="<?= old('full_name', $profile['name'] ?? '') ?>" required></label>
                <label class="field"><span>Phone</span>
                    <input class="input" type="tel" name="phone" maxlength="20"
                        value="<?= old('phone', $profile['phone'] ?? '') ?>" required></label>
                <label class="field"><span>City</span>
                    <input class="input" type="text" name="city" maxlength="100" value="<?= old('city') ?>"
                        required></label>
                <label class="field"><span>Address</span>
                    <input class="input" type="text" name="address" maxlength="255"
                        value="<?= old('address', $profile['address'] ?? '') ?>" required></label>
                <label class="field"><span>Notes (optional)</span>
                    <textarea class="input" name="notes"><?= old('notes') ?></textarea></label>
            </fieldset>
            <fieldset>
                <legend>Payment</legend>
                <label class="payment-option">
                    <input type="radio" name="payment_method" value="COD" checked>
                    <span><strong>Cash on delivery (COD)</strong><br><small>Pay in cash when your order arrives. No card
                            details needed.</small></span>
                </label>
            </fieldset>
        </section>

        <aside class="summary">
            <h2>Order summary</h2>
            <ul>
                <?php foreach ($items as $item): ?>
                <li><span><?= e($item['name']) ?> ×
                        <?= (int) $item['quantity'] ?></span><span><?= money($item['line_total']) ?></span></li>
                <?php endforeach; ?>
            </ul>
            <p class="total"><span>Total</span><strong><?= money($total) ?></strong></p>
            <button class="btn btn-primary btn-lg btn-block" type="submit">Place order</button>
        </aside>
    </form>
</main>