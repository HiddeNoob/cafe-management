<?php

class Employee extends User implements IAuth {
    // Employee-specific properties
    public $hire_date;

    // Constructor to initialize employee properties
    public function __construct($id, $name, $surname, $phone, $email, $password, $hire_date, $nickname = null) {
        parent::__construct($id, $name, $surname, $nickname, $phone, $email, $password);
        $this->hire_date = $hire_date;
        $this->role = 'employee';
    }

    public function login(PDO $pdo, string $username, string $password): bool {
        require_once __DIR__ . '/../Repository/EmployeeRepository.php';
        $employees = EmployeeRepository::findBy($pdo, ['employee_nickname' => $username]);
        if (count($employees) === 1) {
            $employee = $employees[0];
            if (password_verify($password, $employee->password)) {
                // Giriş başarılı, session'a kaydet
                $_SESSION['user'] = $employee;
                return true;
            }
        }
        return false;
    }

    public function logout(): void {
        unset($_SESSION['user']);
        session_destroy();
        header('Location: /login');
    }



}