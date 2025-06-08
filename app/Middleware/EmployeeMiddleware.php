<?php
// EmployeeMiddleware.php
// Checks if the user is authenticated and is an employee

require_once __DIR__ . '/../Models/User.php';

class EmployeeMiddleware {
    public static function handle() {
        if (!User::check() || User::current()['role'] !== 'employee') {
            header('Location: /login');
            exit();
        }
    }
}
