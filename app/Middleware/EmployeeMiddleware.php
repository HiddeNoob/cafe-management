<?php
// EmployeeMiddleware.php
// Checks if the user is authenticated and is an employee

require_once __DIR__ . '/../Interfaces/IMiddleware.php';

class EmployeeMiddleware implements IMiddleware {
    public static function handle(): void {
        if (!isset($_SESSION['user']) || $_SESSION['user']->role !== 'employee') {
            header('Location: /login');
            exit();
        }
    }
}
