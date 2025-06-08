<?php
// CustomerMiddleware.php
// Checks if the user is authenticated and is a customer

require_once __DIR__ . '/../Models/User.php';

class CustomerMiddleware {
    public static function handle() {
        if (!User::check() || User::current()['role'] !== 'customer') {
            header('Location: /login');
            exit();
        }
    }
}
