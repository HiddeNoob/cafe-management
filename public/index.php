<?php
require_once __DIR__ . '/routes/web.php'; // burada $router ve route'lar tanımlı
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Gelen URI ve method'u al
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// İstekleri router ile yönlendir


$router->dispatch($uri, $method);
