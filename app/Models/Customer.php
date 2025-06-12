<?php
require_once __DIR__ . "/../autoload.php";


class Customer implements IAuth {
    public $customer_id, $customer_name, $customer_surname, $customer_phone, $customer_email, $customer_nickname, $customer_password;


    public static function login(PDO $pdo, string $username, string $password): bool {
        $customers = CustomerRepository::getInstance()->findBy(['customer_nickname' => $username]);
        if (count($customers) === 1) {
            $customer = $customers[0];
            if (password_verify($password, $customer->customer_password)) {
                session_start();
                $_SESSION['user'] = $customer;
                return true;
            }
        }
        return false;
    }

    public static function register(Customer $customer): bool {
        require_once __DIR__ . '/../Repositories/CustomerRepository.php';
        $pass = $customer->customer_password;
        $customer->customer_password = password_hash($customer->customer_password, PASSWORD_DEFAULT);
        return CustomerRepository::getInstance()->create($customer);
    
    }

    public static function logout(): void {
        unset($_SESSION['user']);
        session_destroy();
        header('Location: /login/');
    }

    public static function createInstance($data) : Customer {
        $customer = new Customer();
        foreach ($data as $key => $value) {
            if (property_exists(Customer::class, $key)) {
                $customer->$key = $value;
            }
        }           
        return $customer;
    }

}


