<?php
$query = fn (int $p) => url('/products?' . http_build_query(array_filter(['q' => $search, 'category' => $categoryId ?: null, 'page' => $p > 1 ? $p : null])));
?>
<main class="container">
    <header class="page-title">
        <h1>Products</h1>
        <p><?= $total ?> <?= $total === 1 ? 'product' : 'products' ?></p>
    </header>

    <form class="filter-bar" action="<?= url('/products') ?>" method="get">
        <label class="field"><span>Search by title</span>
            <input class="input" type="search" name="q" placeholder="e.g. Smart Watch" value="<?= e($search) ?>">
        </label>
        <label class="field"><span>Category</span>
            <select class="input" name="category">
                <option value="">All categories</option>
                <?php foreach ($categories as $category): ?>
                <option value="<?= (int) $category['id'] ?>"
                    <?= $categoryId === (int) $category['id'] ? ' selected' : '' ?>><?= e($category['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button class="btn btn-primary" type="submit">Filter</button>
    </form>

    <?php if ($products): ?>
    <ul class="product-grid">
        <?php foreach ($products as $product) {
    include __DIR__ . '/partials/product_card.php';
} ?>
    </ul>
    <?php if ($pages > 1): ?>
    <nav class="pagination" aria-label="Pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a href="<?= $query($i) ?>" <?= $i === $page ? ' aria-current="page"' : '' ?>><?= $i ?></a>
        <?php endfor; ?>
    </nav>
    <?php endif; ?>
    <?php else: ?>
    <p class="empty">No products match your search. <a href="<?= url('/products') ?>">Show all products</a></p>
    <?php endif; ?>
</main>