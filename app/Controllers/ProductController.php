<?php

class ProductController
{
    private const PER_PAGE = 9;

    public function index(): void
    {
        $search = trim($_GET['q'] ?? '');
        $categoryId = (int) ($_GET['category'] ?? 0);
        $page = max(1, (int) ($_GET['page'] ?? 1));

        $result = (new Product())->paginate($search, $categoryId, $page, self::PER_PAGE);

        render('products', [
            'title'      => 'Products — KABEL',
            'css'        => 'products',
            'active'     => 'products',
            'products'   => $result['items'],
            'total'      => $result['total'],
            'pages'      => max(1, (int) ceil($result['total'] / self::PER_PAGE)),
            'page'       => $page,
            'search'     => $search,
            'categoryId' => $categoryId,
            'categories' => (new Category())->all(),
        ]);
    }

    public function show(string $id): void
    {
        $product = (new Product())->find((int) $id);
        if (!$product) {
            not_found();
        }

        render('product', [
            'title'   => $product['name'] . ' — KABEL',
            'css'     => 'product',
            'active'  => 'products',
            'product' => $product,
        ]);
    }
}