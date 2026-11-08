<?php /** @var array $product */ $img = upload_url($product['image']); ?>
<li>
    <article class="product-card">
        <figure class="placeholder">
            <?php if ($img): ?><img src="<?= e($img) ?>" alt="<?= e($product['name']) ?>"
                loading="lazy"><?php else: ?>No image<?php endif; ?>
        </figure>
        <p class="kicker"><?= e($product['category_name'] ?? 'Uncategorised') ?></p>
        <h3><a href="<?= url('/products/' . $product['id']) ?>"><?= e($product['name']) ?></a></h3>
        <p class="price"><?= money($product['price']) ?></p>
        <?php if ($product['stock'] > 0): ?>
        <form action="<?= url('/cart/add') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
            <button class="btn btn-secondary btn-block" type="submit">Add to cart</button>
        </form>
        <?php else: ?>
        <p class="tag tag-neutral">Out of stock</p>
        <?php endif; ?>
    </article>
</li>