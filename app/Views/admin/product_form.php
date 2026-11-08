<?php
$editing = $product !== null;
$action = $editing ? '/admin/products/' . $product['id'] . '/update' : '/admin/products/store';
$val = fn (string $key) => old($key, (string) ($product[$key] ?? ''));
$selectedCategory = (int) ($_SESSION['old']['category_id'] ?? $product['category_id'] ?? 0);
$img = $editing ? upload_url($product['image']) : null;
?>
<main>
    <header>
        <h1><?= $editing ? 'Edit product' : 'Add product' ?></h1>
        <a class="btn btn-secondary" href="<?= url('/admin/products') ?>">← Back to products</a>
    </header>

    <form class="panel product-form" action="<?= url($action) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <label class="field"><span>Name</span><input class="input" type="text" name="name" maxlength="150"
                value="<?= $val('name') ?>" required></label>
        <label class="field"><span>Category</span>
            <select class="input" name="category_id">
                <option value="">— No category —</option>
                <?php foreach ($categories as $category): ?>
                <option value="<?= (int) $category['id'] ?>"
                    <?= $selectedCategory === (int) $category['id'] ? ' selected' : '' ?>><?= e($category['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label class="field"><span>Price</span><input class="input" type="number" name="price" min="0" step="0.01"
                value="<?= $val('price') ?>" required></label>
        <label class="field"><span>Stock</span><input class="input" type="number" name="stock" min="0" step="1"
                value="<?= $val('stock') ?>" required></label>
        <label class="field wide"><span>Description</span><textarea class="input"
                name="description"><?= $val('description') ?></textarea></label>
        <label class="field wide"><span>Image (JPG, PNG, WEBP — max 2 MB)</span>
            <input class="input" type="file" name="image" accept="image/jpeg,image/png,image/webp">
        </label>
        <?php if ($img): ?>
        <figure class="wide current-image"><img src="<?= e($img) ?>" alt="Current image">
            <figcaption>Current image — choose a new file to replace it.</figcaption>
        </figure>
        <?php endif; ?>
        <footer class="wide">
            <button class="btn btn-primary" type="submit">Save product</button>
            <a class="btn btn-secondary" href="<?= url('/admin/products') ?>">Cancel</a>
        </footer>
    </form>
</main>