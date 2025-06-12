<?php
require_once __DIR__ . '/../Interfaces/IRepository.php';
require_once __DIR__ . '/../Models/Product.php';

class ProductRepository extends BaseRepository {
    private static $instance = null;
    private function __construct($pdo) {
        parent::__construct($pdo, Product::class);
    }
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new ProductRepository(DatabaseController::getPDO());
        }
        return self::$instance;
    }
}
