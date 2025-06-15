<?php
require_once __DIR__ . '/../autoload.php';

class AuthController {
    public static function redirect_if_logged_in($modelName, $redirect_url = '/') {
        session_start();
        if(isset($_SESSION['user']) && ($_SESSION['user'] instanceof $modelName)) {
            header("Location: $redirect_url");
            exit();
        }
    }

    public static function check_customer_access() {
        session_start();
        if (!isset($_SESSION['user']) || !($_SESSION['user'] instanceof Customer)) {
            $redirect = 'login';
            header("Location: $redirect");
            exit();
        }
        $_SESSION['customer_id'] = $_SESSION['user']->customer_id;
    }

    public static function check_employee_access() {
        session_start();
        if (!isset($_SESSION['user']) || !($_SESSION['user'] instanceof Employee)) {
            $redirect = '/admin/login';
            header("Location: $redirect");
            exit();
        }
        $_SESSION['employee_id'] = $_SESSION['user']->employee_id;
    }

}