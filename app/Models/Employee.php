<?php
require_once __DIR__ . '/../autoload.php';
class Employee implements IAuth {
    // Employee-specific properties
    public $employee_id, $employee_name, $employee_surname, $employee_phone, $employee_email, $employee_password, $employee_hire_date;



    public static function login(PDO $pdo, string $username, string $password): bool {
        $employees = EmployeeRepository::getInstance()->findBy(['employee_email' => $username]);
        if (count($employees) === 1) {
            $employee = $employees[0];
            if (password_verify($password, $employee->employee_password)) {
                session_start();
                $_SESSION['user'] = $employee;
                return true;
            }
        }
        return false;
    }

    public static function logout(): void {
        session_start();
        unset($_SESSION['user']);
        session_destroy();
        header('Location: /admin/login/'); // Redirect to admin login page
    }



}