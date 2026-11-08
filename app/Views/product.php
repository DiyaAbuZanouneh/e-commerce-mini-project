<?php $img = upload_url($product['image']); ?>
<main class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <ol>
            <li><a href="<?= url('/') ?>">Home</a></li>
            <li><a
                    href="<?= url('/products' . ($product['category_id'] ? '?category=' . $product['category_id'] : '')) ?>"><?= e($product['category_name'] ?? 'Products') ?></a>
            </li>
            <li aria-current="page"><?= e($product['name']) ?></li>
        </ol>
    </nav>

    <article class="product-detail">
        <figure class="placeholder">
            <?php if ($img): ?><img src="<?= e($img) ?>" alt="<?= e($product['name']) ?>"><?php else: ?>No
            image<?php endif; ?>
        </figure>
        <section>
            <p class="kicker"><?= e($product['category_name'] ?? 'Uncategorised') ?></p>
            <h1><?= e($product['name']) ?></h1>
            <p class="price"><?= money($product['price']) ?></p>
            <p class="stock"><?= $product['stock'] > 0 ? 'In stock: ' . (int) $product['stock'] : 'Out of stock' ?></p>
            <p><?= nl2br(e($product['description'] ?? '')) ?></p>
            <?php if ($product['stock'] > 0): ?>
            <form action="<?= url('/cart/add') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                <label class="field"><span>Quantity</span>
                    <input class="input" type="number" name="quantity" value="1" min="1"
                        max="<?= (int) $product['stock'] ?>">
                </label>
                <button class="btn btn-primary btn-lg" type="submit">Add to cart</button>
            </form>
            <?php endif; ?>
        </section>
    </article>
</main>