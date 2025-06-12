<?php
require_once __DIR__ . '/../Interfaces/IRepository.php';
require_once __DIR__ . '/../Models/Customer.php';

class CustomerRepository extends BaseRepository {
    static private $instance = null;
    private function __construct(PDO $pdo) {
        parent::__construct($pdo,Customer::class);
    }

    public static function getInstance(){
        if (self::$instance === null) {
            self::$instance = new CustomerRepository(DatabaseController::getPDO());
        }
        return self::$instance;
    }
}
