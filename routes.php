<?php

// 'METHOD /path' => 'Controller@action'. {id} matches one URL segment.
return [
    // Storefront
    'GET /' => 'HomeController@index',
    'GET /products' => 'ProductController@index',
    'GET /products/{id}' => 'ProductController@show',

    'GET /register' => 'AuthController@showRegister',
    'POST /register' => 'AuthController@register',
    'GET /login' => 'AuthController@showLogin',
    'POST /login' => 'AuthController@login',
    'POST /logout' => 'AuthController@logout',
    'GET /profile' => 'ProfileController@show',

    'GET /cart' => 'CartController@index',
    'POST /cart/add' => 'CartController@add',
    'POST /cart/update' => 'CartController@update',
    'POST /cart/remove' => 'CartController@remove',

    'GET /checkout' => 'OrderController@checkout',
    'POST /checkout' => 'OrderController@place',
    'GET /orders/{id}' => 'OrderController@show',

    // Admin (every handler calls requireAdmin())
    'GET /admin' => 'AdminController@dashboard',
    'GET /admin/orders' => 'AdminController@orders',
    'GET /admin/orders/{id}' => 'AdminController@orderDetail',
    'POST /admin/orders/{id}/update' => 'AdminController@updateOrder',

    'GET /admin/products' => 'AdminProductController@index',
    'GET /admin/products/create' => 'AdminProductController@create',
    'POST /admin/products/store' => 'AdminProductController@store',
    'GET /admin/products/{id}/edit' => 'AdminProductController@edit',
    'POST /admin/products/{id}/update' => 'AdminProductController@update',
    'POST /admin/products/{id}/delete' => 'AdminProductController@delete',

    'GET /admin/categories' => 'AdminCategoryController@index',
    'GET /admin/categories/create' => 'AdminCategoryController@create',
    'POST /admin/categories/store' => 'AdminCategoryController@store',
    'GET /admin/categories/{id}/edit' => 'AdminCategoryController@edit',
    'POST /admin/categories/{id}/update' => 'AdminCategoryController@update',
    'POST /admin/categories/{id}/delete' => 'AdminCategoryController@delete',
];