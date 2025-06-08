<?php

class AuthController {
    public function showLogin(Exception $e = null): void {
        require __DIR__ . '/../Views/login.php';
    }

    public function showRegister(Exception $e = null): void {
        require __DIR__ . '/../Views/register.php';
    }

    public function login(PDO $pdo): void {
        $username = $_POST['username'];
        $password = $_POST['password'];
        require_once __DIR__ . '/../Models/Customer.php';
        if (Customer::login($pdo, $username, $password)) {
            header('Location: /dashboard');
        } else {
            throw new Exception("Kullanıcı adı veya şifre yanlış.");
        }
    }

    public function register(PDO $pdo): void {
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $phone = $_POST["phone"];
        $email = $_POST["email"];
        $nickname = $_POST["nickname"];
        $password = $_POST["password"];

        if (empty($name) || empty($surname) || empty($phone) || empty($email) || empty($nickname) || empty($password)) {
            throw new Exception("Lütfen tüm alanları doldurun.");
        }

        // Check if user already exists
        $stmt = $pdo->prepare("SELECT customer_id FROM CUSTOMER WHERE customer_nickname = ?");
        $stmt->execute([$nickname]);
        if ($stmt->fetch()) {
            throw new Exception("Kullanıcı adı zaten alınmış.");
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO CUSTOMER (customer_name, customer_surname, customer_phone, customer_email, customer_nickname, customer_password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $surname, $phone, $email, $nickname, $hashedPassword]);

        header('Location: /login');
        exit();
    }

    public function logout(): void {
        session_start();
        unset($_SESSION['user']);
        session_destroy();
        header('Location: /login');
    }
}
