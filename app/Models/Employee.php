<?php

class Employee implements IAuth {
    // Employee-specific properties
    public $employee_id, $employee_name, $employee_surname, $employee_phone, $employee_email, $employee_nickname, $employee_password, $employee_hire_date, $role;

    // Constructor to initialize employee properties
    public function __construct() {
        $this->role = 'employee';
    }

    public static function login(PDO $pdo, string $username, string $password): bool {
        require_once __DIR__ . '/../Repository/EmployeeRepository.php';
        $employees = EmployeeRepository::findBy($pdo, ['employee_nickname' => $username]);
        if (count($employees) === 1) {
            $employee = $employees[0];
            if (password_verify($password, $employee->employee_password)) {
                // Giriş başarılı, session'a kaydet
                $_SESSION['user'] = $employee;
                return true;
            }
        }
        return false;
    }

    public static function logout(): void {
        unset($_SESSION['user']);
        session_destroy();
        header('Location: /login');
    }



}