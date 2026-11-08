<main>
    <section class="hero">
        <article>
            <h1>Everything your phone plugs into.</h1>
            <p>Browse our catalog, search by title and order with cash on delivery.</p>
            <a class="btn btn-primary btn-lg" href="<?= url('/products') ?>">Shop now</a>
        </article>
        <figure class="placeholder">KABEL</figure>
    </section>

    <section class="container categories">
        <header class="section-head">
            <h2>Categories</h2><a href="<?= url('/products') ?>">All products →</a>
        </header>
        <?php if ($categories): ?>
        <ul class="category-grid">
            <?php foreach ($categories as $category): $img = upload_url($category['image']); ?>
            <li>
                <a href="<?= url('/products?category=' . $category['id']) ?>">
                    <figure class="placeholder"><?php if ($img): ?><img src="<?= e($img) ?>"
                            alt="<?= e($category['name']) ?>" loading="lazy"><?php endif; ?></figure>
                    <strong><?= e($category['name']) ?></strong>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p class="text-muted">No categories yet.</p>
        <?php endif; ?>
    </section>

    <section class="container featured">
        <header class="section-head">
            <h2>Latest products</h2><a href="<?= url('/products') ?>">View all →</a>
        </header>
        <?php if ($products): ?>
        <ul class="product-grid">
            <?php foreach ($products as $product) { include __DIR__ . '/partials/product_card.php'; } ?>
        </ul>
        <?php else: ?>
        <p class="text-muted">No products yet.</p>
        <?php endif; ?>
    </section>
</main>