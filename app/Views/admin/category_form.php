<?php
$editing = $category !== null;
$action = $editing ? '/admin/categories/' . $category['id'] . '/update' : '/admin/categories/store';
$val = fn (string $key) => old($key, (string) ($category[$key] ?? ''));
$img = $editing ? upload_url($category['image']) : null;
?>
<main>
    <header>
        <h1><?= $editing ? 'Edit category' : 'Add category' ?></h1>
        <a class="btn btn-secondary" href="<?= url('/admin/categories') ?>">← Back to categories</a>
    </header>

    <form class="panel category-form" action="<?= url($action) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <label class="field"><span>Name</span><input class="input" type="text" name="name" maxlength="100"
                value="<?= $val('name') ?>" required></label>
        <label class="field"><span>Description</span><textarea class="input"
                name="description"><?= $val('description') ?></textarea></label>
        <label class="field"><span>Image (JPG, PNG, WEBP — max 2 MB)</span>
            <input class="input" type="file" name="image" accept="image/jpeg,image/png,image/webp">
        </label>
        <?php if ($img): ?>
        <figure class="current-image"><img src="<?= e($img) ?>" alt="Current image">
            <figcaption>Current image — choose a new file to replace it.</figcaption>
        </figure>
        <?php endif; ?>
        <footer>
            <button class="btn btn-primary" type="submit">Save category</button>
            <a class="btn btn-secondary" href="<?= url('/admin/categories') ?>">Cancel</a>
        </footer>
    </form>
</main>