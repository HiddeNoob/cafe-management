<?php
require_once __DIR__ . '/web.php'; // burada $router ve route'lar tanımlı

// Gelen URI ve method'u al
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

session_start();
// İstekleri router ile yönlendir
$router->dispatch($uri, $method);
