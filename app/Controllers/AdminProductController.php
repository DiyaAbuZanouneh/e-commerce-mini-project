<?php

/** Admin: product CRUD with image upload. */
class AdminProductController
{
    public function index(): void
    {
        require_admin();
        render('admin/products', [
            'title'    => 'Products — KABEL Admin',
            'css'      => 'admin-products',
            'active'   => 'products',
            'products' => (new Product())->all(),
        ]);
    }

    public function create(): void
    {
        require_admin();
        $this->form(null);
    }

    public function edit(string $id): void
    {
        require_admin();
        $product = (new Product())->find((int) $id);
        if (!$product) {
            not_found();
        }
        $this->form($product);
    }

    public function store(): void
    {
        require_admin();
        $this->save(null);
    }

    public function update(string $id): void
    {
        require_admin();
        $product = (new Product())->find((int) $id);
        if (!$product) {
            not_found();
        }
        $this->save($product);
    }

    public function delete(string $id): void
    {
        require_admin();
        $products = new Product();
        $product = $products->find((int) $id);
        if (!$product) {
            not_found();
        }

        try {
            $products->delete((int) $product['id']);
            delete_image($product['image']);
            flash('success', 'Product deleted.');
        } catch (PDOException $e) {
            flash('error', 'This product is part of existing orders and cannot be deleted. Set its stock to 0 instead.');
        }
        redirect('/admin/products');
    }

    private function form(?array $product): void
    {
        render('admin/product_form', [
            'title'      => ($product ? 'Edit' : 'Add') . ' product — KABEL Admin',
            'css'        => 'product-form',
            'active'     => 'products',
            'product'    => $product,
            'categories' => (new Category())->all(),
        ]);
    }

    private function save(?array $product): void
    {
        $data = [
            'name'        => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? '') ?: null,
            'price'       => $_POST['price'] ?? '',
            'stock'       => $_POST['stock'] ?? '',
            'category_id' => (int) ($_POST['category_id'] ?? 0) ?: null,
            'image'       => $product['image'] ?? null,
        ];
        $back = $product ? '/admin/products/' . $product['id'] . '/edit' : '/admin/products/create';

        $errors = [];
        if ($data['name'] === '' || mb_strlen($data['name']) > 150) {
            $errors[] = 'Name is required (max 150 characters).';
        }
        if (!is_numeric($data['price']) || $data['price'] < 0 || $data['price'] > 99999999) {
            $errors[] = 'Price must be a number of 0 or more.';
        }
        if (filter_var($data['stock'], FILTER_VALIDATE_INT) === false || $data['stock'] < 0) {
            $errors[] = 'Stock must be a whole number of 0 or more.';
        }
        if ($data['category_id'] && !(new Category())->find($data['category_id'])) {
            $errors[] = 'Selected category does not exist.';
        }

        [$newImage, $uploadError] = upload_image($_FILES['image'] ?? null, 'products', 'prod');
        if ($uploadError) {
            $errors[] = $uploadError;
        }

        if ($errors) {
            foreach ($errors as $error) {
                flash('error', $error);
            }
            with_old($_POST);
            redirect($back);
        }

        $data['price'] = round((float) $data['price'], 2);
        $data['stock'] = (int) $data['stock'];

        $products = new Product();
        if ($newImage) {
            if ($product) {
                delete_image($product['image']);
            }
            $data['image'] = $newImage;
        }

        if ($product) {
            $products->update((int) $product['id'], $data);
            flash('success', 'Product updated.');
        } else {
            $products->create($data);
            flash('success', 'Product created.');
        }
        redirect('/admin/products');
    }
}