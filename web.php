<?php
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/app/Controllers/AuthController.php';
require_once __DIR__ . '/app/Controllers/CustomerController.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Middleware/EmployeeMiddleware.php';
require_once __DIR__ . '/app/Middleware/CustomerMiddleware.php';
require_once __DIR__ . '/app/Repository/CustomerRepository.php';

$router = new Router();
$pdo = getPDO();

$router->get('', function () {
    header('Location: /login');
});

$router->get('/login', function () {
    try{
        (new AuthController())->showLogin();
    } catch (Exception $e) {
        (new AuthController())->showLogin($e);  
    }
});

$router->get('/register', function () {
    (new AuthController())->showRegister(null);
});

$router->get('/customer', function () use ($pdo) {
    echo json_encode(CustomerRepository::getAll($pdo),0, JSON_PRETTY_PRINT);
});


$router->post('/login', function () use ($pdo) {
    session_start();
    try{
        (new AuthController())->login($pdo);
    } catch (Exception $e) {
        (new AuthController())->showLogin($e);
    }
});

$router->post('/register', function () use ($pdo) {
    session_start();
    try{
        (new AuthController())->register($pdo);
    }catch (Exception $e) {
        (new AuthController())->showRegister($e);
    }
});

$router->get('/logout', function () {
    (new AuthController())->logout();
});

$router->get('/customers', function () use ($pdo) {
    CustomerMiddleware::handle();
    (new CustomerController())->index($pdo);
});

$router->get('/main', function () {
    CustomerMiddleware::handle();
    require_once __DIR__ .'/app/Views/main.php';
});

$router->get('/session', function () {
    session_start();
    if (isset($_SESSION['user'])) {
        echo json_encode($_SESSION['user']);
    } else {
        echo json_encode(['error' => 'No active session']);
    }
});