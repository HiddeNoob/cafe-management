<?php

require_once __DIR__ . '/../Interfaces/IRepository.php';
require_once __DIR__ . '/../Models/Receipt.php';

class ReceiptRepository extends BaseRepository {
    private static $instance = null;
    
    private function __construct($pdo) { 
        parent::__construct($pdo,Receipt::class);
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new ReceiptRepository(DatabaseController::getPDO());
        }
        return self::$instance;            
    }
}
