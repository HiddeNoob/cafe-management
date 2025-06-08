<?php
class Customer extends User implements IAuth {
    // Customer-specific properties and methods can be added here
    // For example, you might want to add methods for customer-specific actions


    public function __construct(
        $id,
        $name,
        $surname,
        $phone,
        $email,
        $nickname,
        $password
    ) {
        $this->id = $id; // corresponds to customer_id
        $this->name = $name; // corresponds to customer_name
        $this->surname = $surname; // corresponds to customer_surname
        $this->phone = $phone; // corresponds to customer_phone
        $this->email = $email; // corresponds to customer_email
        $this->nickname = $nickname; // corresponds to customer_nickname
        $this->password = $password; // corresponds to customer_password
        parent::__construct($id, $name, $surname, $phone, $email, $nickname, $password);
        $this->role = 'customer';
    }

    public function login(PDO $pdo, string $username, string $password): bool {
        require_once __DIR__ . '/../Repository/CustomerRepository.php';
        $customers = CustomerRepository::findBy($pdo, ['customer_nickname' => $username]);
        if (count($customers) === 1) {
            $customer = $customers[0];
            if (password_verify($password, $customer->password)) {
                // Giriş başarılı, session'a kaydet
                $_SESSION['user'] = $customer;
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


