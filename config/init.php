<?php

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$env = parse_ini_file(__Dir__ . '/../.env');
foreach ($env as $key => $value){
    $_ENV[$key] = $value;
}
    
require_once __DIR__ . '/database.php';
    
$database = new Database();
$db = $database->getConnection();