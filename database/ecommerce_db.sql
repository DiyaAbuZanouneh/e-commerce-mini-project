-- =====================================================
--  E-Commerce Mini-Project Database (MySQL 8+)
--  PHP OOP + PDO | Media Uploads | COD Checkout
-- =====================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP DATABASE IF EXISTS ecommerce_db;
CREATE DATABASE ecommerce_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE ecommerce_db;

-- -----------------------------------------------------
-- 1) users
-- -----------------------------------------------------
CREATE TABLE users (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100)  NOT NULL,
  email       VARCHAR(150)  NOT NULL UNIQUE,
  password    VARCHAR(255)  NOT NULL,              -- password_hash()
  phone       VARCHAR(20)   NULL,
  address     VARCHAR(255)  NULL,
  role        ENUM('customer','admin') NOT NULL DEFAULT 'customer',
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 2) categories
-- -----------------------------------------------------
CREATE TABLE categories (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name         VARCHAR(100) NOT NULL UNIQUE,
  description  TEXT NULL,
  image        VARCHAR(255) NULL,                  -- e.g. uploads/categories/cat_65a1f.jpg
  created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 3) products   (categories 1 --- N products)
-- -----------------------------------------------------
CREATE TABLE products (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id  INT UNSIGNED NULL,
  name         VARCHAR(150) NOT NULL,
  description  TEXT NULL,
  price        DECIMAL(10,2) NOT NULL,
  stock        INT UNSIGNED NOT NULL DEFAULT 0,
  image        VARCHAR(255) NULL,                  -- e.g. uploads/products/prod_65a1f.jpg
  created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT chk_products_price CHECK (price >= 0),
  CONSTRAINT fk_products_category
    FOREIGN KEY (category_id) REFERENCES categories(id)
    ON DELETE SET NULL ON UPDATE CASCADE,
  INDEX idx_products_name (name),                  -- search by title
  INDEX idx_products_category (category_id)        -- filter by category
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 4) carts   (users 1 --- 1 carts)
-- -----------------------------------------------------
CREATE TABLE carts (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     INT UNSIGNED NOT NULL UNIQUE,        -- UNIQUE => one cart per user
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_carts_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 5) cart_items   (carts 1---N cart_items, products 1---N cart_items)
-- -----------------------------------------------------
CREATE TABLE cart_items (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  cart_id     INT UNSIGNED NOT NULL,
  product_id  INT UNSIGNED NOT NULL,
  quantity    INT UNSIGNED NOT NULL DEFAULT 1,
  added_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT chk_cart_items_qty CHECK (quantity > 0),
  CONSTRAINT uq_cart_product UNIQUE (cart_id, product_id),   -- no duplicate product rows
  CONSTRAINT fk_cart_items_cart
    FOREIGN KEY (cart_id) REFERENCES carts(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_cart_items_product
    FOREIGN KEY (product_id) REFERENCES products(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 6) orders   (users 1 --- N orders)
-- -----------------------------------------------------
CREATE TABLE orders (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id           INT UNSIGNED NOT NULL,
  full_name         VARCHAR(100) NOT NULL,         -- delivery details
  phone             VARCHAR(20)  NOT NULL,
  city              VARCHAR(100) NOT NULL,
  address           VARCHAR(255) NOT NULL,
  notes             TEXT NULL,
  total             DECIMAL(10,2) NOT NULL,
  payment_method    ENUM('COD') NOT NULL DEFAULT 'COD',
  payment_status    ENUM('Unpaid','Paid') NOT NULL DEFAULT 'Unpaid',
  order_status      ENUM('Pending','Processing','Shipped','Delivered','Cancelled')
                    NOT NULL DEFAULT 'Pending',
  created_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT chk_orders_total CHECK (total >= 0),
  CONSTRAINT fk_orders_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE RESTRICT ON UPDATE CASCADE,         -- keep order history
  INDEX idx_orders_status (order_status),
  INDEX idx_orders_user (user_id)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 7) order_items   (orders 1---N order_items, products 1---N order_items)
-- -----------------------------------------------------
CREATE TABLE order_items (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id      INT UNSIGNED NOT NULL,
  product_id    INT UNSIGNED NOT NULL,
  product_name  VARCHAR(150) NOT NULL,             -- snapshot at purchase time
  unit_price    DECIMAL(10,2) NOT NULL,            -- snapshot at purchase time
  quantity      INT UNSIGNED NOT NULL,
  subtotal      DECIMAL(10,2) AS (unit_price * quantity) STORED,
  CONSTRAINT chk_order_items_qty CHECK (quantity > 0),
  CONSTRAINT fk_order_items_order
    FOREIGN KEY (order_id) REFERENCES orders(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_order_items_product
    FOREIGN KEY (product_id) REFERENCES products(id)
    ON DELETE RESTRICT ON UPDATE CASCADE          -- can't delete a product that was ordered
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
--  Sample data
-- =====================================================

INSERT INTO categories (name, description, image) VALUES
('Electronics', 'Phones, laptops and accessories', 'uploads/categories/electronics.jpg'),
('Clothing',    'Men and women fashion',           'uploads/categories/clothing.jpg'),
('Books',       'Printed books and novels',        'uploads/categories/books.jpg');

INSERT INTO products (category_id, name, description, price, stock, image) VALUES
(1, 'Wireless Headphones', 'Bluetooth over-ear headphones',   45.00, 30, 'uploads/products/headphones.jpg'),
(1, 'Smart Watch',         'Fitness tracking smart watch',    80.00, 15, 'uploads/products/watch.jpg'),
(2, 'Cotton T-Shirt',      '100% cotton, multiple colors',    12.50, 100, 'uploads/products/tshirt.jpg'),
(2, 'Denim Jacket',        'Classic blue denim jacket',       35.00, 20, 'uploads/products/jacket.jpg'),
(3, 'PHP & MySQL Guide',   'Beginner to advanced web dev',    20.00, 40, 'uploads/products/php-book.jpg');

-- Admin account (login: admin@example.com / admin123 — change the password after first login)
INSERT INTO users (name, email, password, role) VALUES
('Store Admin', 'admin@example.com', '$2y$10$JhutrnOhiDJutr3JMj5YWOMjGfBwnLc3NpNEO9AX2lM1bL7/3Umse', 'admin');

-- =====================================================
--  Useful queries (Dashboard)
-- =====================================================
-- Total Revenue:   SELECT COALESCE(SUM(total),0) FROM orders WHERE payment_status = 'Paid';
-- Orders Count:    SELECT COUNT(*) FROM orders;
-- Product Count:   SELECT COUNT(*) FROM products;
-- Customers:       SELECT COUNT(*) FROM users WHERE role = 'customer';
