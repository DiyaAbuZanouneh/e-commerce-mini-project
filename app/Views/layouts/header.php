<?php
$user = current_user();
$cartCount = $user ? (new Cart())->count($user['id']) : 0;
$nav = fn (string $key) => $active === $key ? ' aria-current="page"' : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
    <?php if ($css): ?>
    <link rel="stylesheet" href="<?= asset('css/pages/' . $css . '.css') ?>">
    <?php endif; ?>
</head>

<body class="<?= e($css) ?>">
    <header class="site-header">
        <a class="logo" href="<?= url('/') ?>">KABEL</a>
        <form class="search" role="search" action="<?= url('/products') ?>" method="get">
            <label class="visually-hidden" for="q">Search by title</label>
            <input class="input" id="q" name="q" type="search" placeholder="Search products by title"
                value="<?= e($_GET['q'] ?? '') ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>
        <nav aria-label="Main">
            <a href="<?= url('/products') ?>" <?= $nav('products') ?>>Products</a>
            <a href="<?= url('/cart') ?>" <?= $nav('cart') ?>>Cart (<?= $cartCount ?>)</a>
            <?php if ($user): ?>
            <?php if (is_admin()): ?><a href="<?= url('/admin') ?>">Admin</a><?php endif; ?>
            <a href="<?= url('/profile') ?>" <?= $nav('profile') ?>><?= e($user['name']) ?></a>
            <form action="<?= url('/logout') ?>" method="post">
                <?= csrf_field() ?>
                <button class="btn btn-ghost" type="submit">Logout</button>
            </form>
            <?php else: ?>
            <a href="<?= url('/login') ?>">Login</a>
            <a href="<?= url('/register') ?>">Register</a>
            <?php endif; ?>
        </nav>
    </header>
    <?php include __DIR__ . '/flash.php'; ?>