<?php

class HomeController
{
    public function index(): void
    {
        render('home', [
            'title'      => 'KABEL — Home',
            'css'        => 'home',
            'categories' => (new Category())->all(),
            'products'   => (new Product())->latest(4),
        ]);
    }
}