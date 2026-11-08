<?php
$navItems = ['dashboard' => ['Dashboard', '/admin'], 'products' => ['Products', '/admin/products'],
             'categories' => ['Categories', '/admin/categories'], 'orders' => ['Orders', '/admin/orders']];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
    <?php if ($css): ?>
    <link rel="stylesheet" href="<?= asset('css/pages/' . $css . '.css') ?>">
    <?php endif; ?>
</head>

<body class="admin <?= e($css) ?>">
    <aside class="admin-sidebar">
        <a class="logo" href="<?= url('/admin') ?>">KABEL <small>ADMIN</small></a>
        <nav aria-label="Admin">
            <ul>
                <?php foreach ($navItems as $key => [$label, $path]): ?>
                <li><a href="<?= url($path) ?>" <?= $active === $key ? ' aria-current="page"' : '' ?>><?= $label ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <footer>
            <a href="<?= url('/') ?>">View store</a>
            <form action="<?= url('/logout') ?>" method="post">
                <?= csrf_field() ?>
                <button type="submit">Logout</button>
            </form>
        </footer>
    </aside>
    <section class="admin-body">
        <?php include __DIR__ . '/flash.php'; ?>