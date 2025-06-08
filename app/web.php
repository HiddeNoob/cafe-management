<?php
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/CustomerController.php';
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Middleware/EmployeeMiddleware.php';
require_once __DIR__ . '/../app/Middleware/CustomerMiddleware.php';

$router = new Router();
$pdo = getPDO();

$router->get('', function () {
    header('Location: /login');
});

$router->get('/login', function () {
   (new AuthController())->showLogin();
});

$router->get('/user/:id', function ($id) {
    echo "$id";
});

$router->post('/login', function () use ($pdo) {
    (new AuthController())->login($pdo);
});

$router->post('/register', function () use ($pdo) {
    (new AuthController())->register($pdo);
});

$router->get('/logout', function () {
    (new AuthController())->logout();
});

$router->get('/customers', function () use ($pdo) {
    CustomerMiddleware::handle();
    (new CustomerController())->index($pdo);
});
