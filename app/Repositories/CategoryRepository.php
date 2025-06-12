<?php
require_once __DIR__ . '/../Interfaces/IRepository.php';
require_once __DIR__ . '/../Models/Category.php';

class CategoryRepository extends BaseRepository {
    private static $instance = null;
    private function __construct($pdo) {
        parent::__construct($pdo, Category::class);
    }
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new CategoryRepository(DatabaseController::getPDO());
        }
        return self::$instance;
    }
}
