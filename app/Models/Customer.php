<?php
require_once __DIR__ . '/../Interfaces/IAuth.php';


class Customer implements IAuth {
    public $customer_id, $customer_name, $customer_surname, $customer_phone, $customer_email, $customer_nickname, $customer_password, $role;

    // Customer-specific properties and methods can be added here
    // For example, you might want to add methods for customer-specific actions


    public function __construct() {
        $this->role = 'customer';
    }

    public static function login(PDO $pdo, string $username, string $password): bool {
        require_once __DIR__ . '/../Repository/CustomerRepository.php';
        $customers = CustomerRepository::findBy($pdo, ['customer_nickname' => $username]);
        if (count($customers) === 1) {
            $customer = $customers[0];
            if (password_verify($password, $customer->customer_password)) {
                // Giriş başarılı, session'a kaydet
                $_SESSION['user'] = $customer;
                return true;
            }
        }
        return false;
    }

    public static function register(
        PDO $pdo, 
        $customer_name,
        $customer_surname,
        $customer_phone,
        $customer_email,
        $customer_nickname,
        $customer_password): bool {

        require_once __DIR__ . '/../Repository/CustomerRepository.php';
        $customer = new Customer();
        $customer->customer_name = $customer_name;
        $customer->customer_surname = $customer_surname;
        $customer->customer_phone = $customer_phone;
        $customer->customer_email = $customer_email;
        $customer->customer_nickname = $customer_nickname;
        $customer->customer_password = password_hash($customer_password, PASSWORD_DEFAULT);
        return CustomerRepository::create($pdo, $customer);
    
    }

    public static function logout(): void {
        unset($_SESSION['user']);
        session_destroy();
        header('Location: /login');
    }

}


