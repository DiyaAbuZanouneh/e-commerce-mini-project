<?php

/** Admin: category CRUD with image upload. */
class AdminCategoryController
{
    public function index(): void
    {
        require_admin();
        render('admin/categories', [
            'title'      => 'Categories — KABEL Admin',
            'css'        => 'admin-categories',
            'active'     => 'categories',
            'categories' => (new Category())->all(),
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
        $category = (new Category())->find((int) $id);
        if (!$category) {
            not_found();
        }
        $this->form($category);
    }

    public function store(): void
    {
        require_admin();
        $this->save(null);
    }

    public function update(string $id): void
    {
        require_admin();
        $category = (new Category())->find((int) $id);
        if (!$category) {
            not_found();
        }
        $this->save($category);
    }

    public function delete(string $id): void
    {
        require_admin();
        $categories = new Category();
        $category = $categories->find((int) $id);
        if (!$category) {
            not_found();
        }

        $categories->delete((int) $category['id']); // products keep existing (category becomes empty)
        delete_image($category['image']);
        flash('success', 'Category deleted.');
        redirect('/admin/categories');
    }

    private function form(?array $category): void
    {
        render('admin/category_form', [
            'title'    => ($category ? 'Edit' : 'Add') . ' category — KABEL Admin',
            'css'      => 'category-form',
            'active'   => 'categories',
            'category' => $category,
        ]);
    }

    private function save(?array $category): void
    {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '') ?: null;
        $image = $category['image'] ?? null;
        $back = $category ? '/admin/categories/' . $category['id'] . '/edit' : '/admin/categories/create';
        $categories = new Category();

        $errors = [];
        if ($name === '' || mb_strlen($name) > 100) {
            $errors[] = 'Name is required (max 100 characters).';
        } elseif ($categories->nameExists($name, (int) ($category['id'] ?? 0))) {
            $errors[] = 'A category with this name already exists.';
        }

        [$newImage, $uploadError] = upload_image($_FILES['image'] ?? null, 'categories', 'cat');
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

        if ($newImage) {
            if ($category) {
                delete_image($category['image']);
            }
            $image = $newImage;
        }

        if ($category) {
            $categories->update((int) $category['id'], $name, $description, $image);
            flash('success', 'Category updated.');
        } else {
            $categories->create($name, $description, $image);
            flash('success', 'Category created.');
        }
        redirect('/admin/categories');
    }
}