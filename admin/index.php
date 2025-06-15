<?php
require_once __DIR__ . '/../app/autoload.php';
AuthController::redirect_if_logged_in(Employee::class, 'dashboard');

header("Location: login");

?>