<?php

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = '/'): string
{
    return (defined('BASE_URL') ? BASE_URL : '') . $path;
}

function asset(string $path): string
{
    return url('/assets/' . ltrim($path, '/'));
}

function upload_url(?string $path): ?string
{
    if (!$path || !is_file(__DIR__ . '/../public/' . $path)) {
        return null;
    }
    return url('/' . $path);
}

function money($amount): string
{
    return '$' . number_format((float) $amount, 2);
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function take_flash(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

function with_old(array $data): void
{
    $_SESSION['old'] = $data;
}

function old(string $key, string $default = ''): string
{
    return e($_SESSION['old'][$key] ?? $default);
}

function clear_old(): void
{
    unset($_SESSION['old']);
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_admin(): bool
{
    return (current_user()['role'] ?? '') === 'admin';
}


/** Show a view inside the layout (admin/ views use the admin layout). */
function render(string $view, array $data = []): void
{
    $isAdmin = str_starts_with($view, 'admin/');
    $title = $data['title'] ?? 'KABEL';
    $css = $data['css'] ?? null;
    $active = $data['active'] ?? '';
    extract($data);

    require __DIR__ . '/Views/layouts/' . ($isAdmin ? 'admin_header' : 'header') . '.php';
    require __DIR__ . '/Views/' . $view . '.php';
    require __DIR__ . '/Views/layouts/' . ($isAdmin ? 'admin_footer' : 'footer') . '.php';
    clear_old();
}

function not_found(): never
{
    http_response_code(404);
    render('404', ['title' => 'Page not found', 'css' => 'home']);
    exit;
}

function require_login(): array
{
    $user = current_user();
    if (!$user) {
        flash('error', 'Please log in to continue.');
        redirect('/login');
    }
    return $user;
}

function require_admin(): array
{
    $user = require_login();
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        exit('403 — Admins only.');
    }
    return $user;
}


function upload_image(?array $file, string $folder, string $prefix): array
{
    if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return [null, null]; // no file chosen
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [null, 'The image could not be uploaded.'];
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        return [null, 'The image is too large. Maximum size is 2 MB.'];
    }

    $types = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    if (!isset($types[$mime]) || @getimagesize($file['tmp_name']) === false) {
        return [null, 'Invalid file type. Only JPG, PNG and WEBP images are allowed.'];
    }

    $dir = __DIR__ . '/../public/uploads/' . $folder;
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    $name = $prefix . '_' . bin2hex(random_bytes(8)) . '.' . $types[$mime];
    if (!move_uploaded_file($file['tmp_name'], "$dir/$name")) {
        return [null, 'The image could not be saved.'];
    }
    return ["uploads/$folder/$name", null];
}

function delete_image(?string $path): void
{
    if ($path && str_starts_with($path, 'uploads/')) {
        @unlink(__DIR__ . '/../public/' . $path);
    }
}