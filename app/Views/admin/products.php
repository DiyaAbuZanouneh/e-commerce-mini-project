<main>
    <header>
        <h1>Products</h1>
        <a class="btn btn-primary" href="<?= url('/admin/products/create') ?>">+ Add product</a>
    </header>

    <?php if ($products): ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Image</th>
                <th scope="col">Name</th>
                <th scope="col">Category</th>
                <th scope="col">Price</th>
                <th scope="col">Stock</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): $img = upload_url($product['image']); ?>
            <tr>
                <td>
                    <figure class="placeholder thumb"><?php if ($img): ?><img src="<?= e($img) ?>"
                            alt=""><?php endif; ?></figure>
                </td>
                <td><strong><?= e($product['name']) ?></strong></td>
                <td><?= e($product['category_name'] ?? '—') ?></td>
                <td><?= money($product['price']) ?></td>
                <td><?= (int) $product['stock'] ?></td>
                <td class="actions">
                    <a class="btn btn-secondary"
                        href="<?= url('/admin/products/' . $product['id'] . '/edit') ?>">Edit</a>
                    <form action="<?= url('/admin/products/' . $product['id'] . '/delete') ?>" method="post"
                        onsubmit="return confirm('Delete this product?')">
                        <?= csrf_field() ?>
                        <button class="btn btn-ghost" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p class="text-muted">No products yet.</p>
    <?php endif; ?>
</main>