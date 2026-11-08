<?php

require __DIR__ . '/../config/init.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$script = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
$root = preg_replace('#/public$#', '', $script);
$base = ($uri === $script || str_starts_with($uri, $script . '/')) ? $script : $root;

define('BASE_URL', $base);

$path = '/' . trim(rawurldecode(substr($uri, strlen($base))), '/');
$method = $_SERVER['REQUEST_METHOD'];

foreach (require __DIR__ . '/../routes.php' as $route => $handler) {
    [$routeMethod, $routePath] = explode(' ', $route);
    $regex = '#^' . preg_replace('#\{\w+\}#', '([^/]+)', $routePath) . '$#';

    if ($routeMethod !== $method || !preg_match($regex, $path, $matches)) {
        continue;
    }

    $token = $_SESSION['csrf'] ?? '';
    if ($method === 'POST' && ($token === '' || !hash_equals($token, $_POST['_token'] ?? ''))) {
        http_response_code(403);
        exit('Invalid or expired form token. Go back, refresh the page and try again.');
    }

    [$controller, $action] = explode('@', $handler);
    (new $controller())->$action(...array_slice($matches, 1));
    exit;
}

not_found();