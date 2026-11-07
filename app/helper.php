<?php

function e(?string $value):string 
{   
    return htmlspecialchars((string) $value , ENT_QUOTES, 'UTF-8');
}


function url(string $path = '/'){
    return (defined('BASE_URL') ? BASE_URL : ''). $path; 
}

function asset(string $path):string 
{
    return url('/assets/' . ltrim($path,'/'));
}

function upload_url(?string $path): ?string 
{   
    if(!$path || !is_file(__DIR__ . '/../public' . $path)){
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