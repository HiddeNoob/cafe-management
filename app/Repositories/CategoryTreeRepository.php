<?php
require_once __DIR__ . '/../Models/CategoryTree.php';
require_once __DIR__ . '/../Controllers/DatabaseController.php';

class CategoryTreeRepository {
    private static $instance = null;
    private $pdo;
    private $table_name = 'CATEGORY_TREE';
    private $table_columns = ['parent_category_id', 'sub_category_id'];

    private function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new CategoryTreeRepository(DatabaseController::getPDO());
        }
        return self::$instance;
    }
    public function getSubCategoryIds($parentId) {
        $query = "SELECT sub_category_id FROM {$this->table_name} WHERE parent_category_id = :parent_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['parent_id' => $parentId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    public function getParentCategoryIds($subCategoryId) {
        $query = "SELECT parent_category_id FROM {$this->table_name} WHERE sub_category_id = :sub_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['sub_id' => $subCategoryId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    public function addSubCategory($parentId, $subCategoryId) {
        $query = "INSERT INTO {$this->table_name} (parent_category_id, sub_category_id) VALUES (:parent_id, :sub_id)";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            'parent_id' => $parentId,
            'sub_id' => $subCategoryId
        ]);
    }
    // İlişki ekleme/silme fonksiyonları da eklenebilir
}
