<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load .env (real environment variables win, handy for testing)
$env = parse_ini_file(__DIR__ . '/../.env');
foreach ($env as $key => $value) {
    $_ENV[$key] = getenv($key) !== false ? getenv($key) : $value;
}

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../app/helpers.php';

// Simple autoloader: app/Models, app/Controllers
spl_autoload_register(function (string $class): void {
    foreach (['Models', 'Controllers'] as $folder) {
        $file = __DIR__ . "/../app/$folder/$class.php";
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

$database = new Database();
$db = $database->getConnection();
