<main>
    <header>
        <h1>Categories</h1>
        <a class="btn btn-primary" href="<?= url('/admin/categories/create') ?>">+ Add category</a>
    </header>

    <?php if ($categories): ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Image</th>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Products</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): $img = upload_url($category['image']); ?>
            <tr>
                <td>
                    <figure class="placeholder thumb"><?php if ($img): ?><img src="<?= e($img) ?>"
                            alt=""><?php endif; ?></figure>
                </td>
                <td><strong><?= e($category['name']) ?></strong></td>
                <td><?= e($category['description'] ?? '') ?></td>
                <td><?= (int) $category['product_count'] ?></td>
                <td class="actions">
                    <a class="btn btn-secondary"
                        href="<?= url('/admin/categories/' . $category['id'] . '/edit') ?>">Edit</a>
                    <form action="<?= url('/admin/categories/' . $category['id'] . '/delete') ?>" method="post"
                        onsubmit="return confirm('Delete this category? Its products will stay but lose their category.')">
                        <?= csrf_field() ?>
                        <button class="btn btn-ghost" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p class="text-muted">No categories yet.</p>
    <?php endif; ?>
</main>