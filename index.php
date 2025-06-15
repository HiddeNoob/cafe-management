<?php 
    require_once __DIR__ . '/app/Functions/log_errors.php';
    require_once __DIR__ . '/app/autoload.php';
    AuthController::redirect_if_logged_in(Customer::class, 'dashboard');
    
    //if not logged in, redirect to login page
    header('Location: login');
?>