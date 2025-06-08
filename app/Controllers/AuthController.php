<?php
require_once __DIR__ . '/../Models/User.php';

class AuthController {
    public function showLogin(): void {
        require __DIR__ . '/../Views/login.php';
    }

    public function login(PDO $pdo): void {
        $email = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        echo $email . " " . $password;
        if (User::authenticate($pdo, $email, $password)) {
            header('Location: /customers');
        } else {
            echo "Giriş başarısız";
        }
    }

    public function register(PDO $pdo): void {
        $username = $_POST["username"];
        $password = $_POST["password"];

        if (empty($username) || empty($password)) {
            echo "Kullanıcı adı ve şifre gerekli.";
            return;
        }

        // Check if user already exists
        $stmt = $pdo->prepare("SELECT id FROM customer WHERE customer_nickname = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            throw new Exception("Kullanıcı adı zaten alınmış.");
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO customers (customer_nickname, customer_password) VALUES (?, ?)");
        $stmt->execute([$username, $hashedPassword]);

        header('Location: /login');
        exit();
    }

    public function logout(): void {
        User::logout();
        header('Location: /login');
    }
}
