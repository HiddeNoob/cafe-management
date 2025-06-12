<?php
require_once __DIR__ . '/../Interfaces/IRepository.php';
require_once __DIR__ . '/../Models/Employee.php';

class EmployeeRepository extends BaseRepository {
    private static $instance = null;
    
    private function __construct($pdo) { 
        parent::__construct($pdo,Employee::class);
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new EmployeeRepository(DatabaseController::getPDO());
        }
        return self::$instance;            
    }
}
