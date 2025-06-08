<?php
// CustomerMiddleware.php
// Checks if the user is authenticated and is a customer

require_once __DIR__ . '/../Interfaces/IMiddleware.php';

class CustomerMiddleware implements IMiddleware {
    public static function  handle() : void {
        if (!isset($_SESSION['user']) || $_SESSION['user']->role !== 'customer') {
            http_response_code(403);
            require_once __DIR__ . '/../Views/access_restricted.php';
            exit();
        }
    }
}
