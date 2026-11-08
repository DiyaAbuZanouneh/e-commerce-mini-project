# KABEL — E-Commerce Mini Project

PHP (OOP, PDO) + MySQL. Admin media uploads and Cash on Delivery (COD) checkout.

## Setup (XAMPP)
1. Copy this folder to `C:\xampp\htdocs\e-commerce-mini-project`.
2. Start **Apache** and **MySQL**, then import `database/ecommerce_db.sql` in phpMyAdmin
   (it creates the `ecommerce_db` database, sample data and an admin account).
3. Check `.env` (DB host / user / password).
4. Open <http://localhost/e-commerce-mini-project/>

Admin login: `admin@example.com` / `admin123` → <http://localhost/e-commerce-mini-project/admin>
(change this password after the first login).

## Structure
- `public/index.php` — front controller, `routes.php` — all routes
- `app/helpers.php` — small helper functions (database, render, login checks, image upload)
- `app/Controllers`, `app/Models` (PDO prepared statements), `app/Views` (semantic HTML)
- `public/assets/css/main.css` (variables + shared styles) and `pages/*.css` (one file per page)
- `public/uploads/` — uploaded product / category images

## Features
Storefront: register / login / logout, profile + my orders, product catalog, search by title,
category filter, product page, cart (add / update / remove / totals), COD checkout, order receipt.
Admin: dashboard (revenue, orders, products, customers), product CRUD, category CRUD
(both with image upload), order tracking (view items, set Delivered / Paid).
Security: sessions, `password_hash/verify`, prepared statements, output escaping,
CSRF tokens on every POST, admin-only routes, validated uploads (type + size).
