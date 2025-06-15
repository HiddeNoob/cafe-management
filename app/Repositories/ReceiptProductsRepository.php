<?php

require_once __DIR__ . '/../Controllers/DatabaseController.php';

class ReceiptProductsRepository {
    private static $instance = null;
    private $pdo;
    private $table_name = 'RECEIPT_PRODUCTS';
    private $table_columns = ['receipt_id', 'product_id', 'product_quantity', 'product_total_amount'];

    private function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new ReceiptProductsRepository(DatabaseController::getPDO());
        }
        return self::$instance;
    }

    public function getProductsByReceiptId($receiptId) {
        $query = "SELECT * FROM {$this->table_name} WHERE receipt_id = :receipt_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['receipt_id' => $receiptId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addProduct($receiptId, $productId, $quantity, $totalAmount) {
        $query = "INSERT INTO {$this->table_name} (receipt_id, product_id, product_quantity, product_total_amount) 
                 VALUES (:receipt_id, :product_id, :quantity, :total_amount)";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            'receipt_id' => $receiptId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'total_amount' => $totalAmount
        ]);
    }

    public function updateProductQuantity($receiptId, $productId, $quantity, $totalAmount) {
        $query = "UPDATE {$this->table_name} 
                 SET product_quantity = :quantity, product_total_amount = :total_amount 
                 WHERE receipt_id = :receipt_id AND product_id = :product_id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            'receipt_id' => $receiptId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'total_amount' => $totalAmount
        ]);
    }

    public function removeProduct($receiptId, $productId) {
        $query = "DELETE FROM {$this->table_name} WHERE receipt_id = :receipt_id AND product_id = :product_id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            'receipt_id' => $receiptId,
            'product_id' => $productId
        ]);
    }

    public function deleteProduct($receiptId, $productId) {
        $query = "DELETE FROM {$this->table_name} 
                 WHERE receipt_id = :receipt_id AND product_id = :product_id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute([
            'receipt_id' => $receiptId,
            'product_id' => $productId
        ]);
    }

    public function getReceiptTotal($receiptId) {
        $query = "SELECT SUM(product_total_amount) as total FROM {$this->table_name} WHERE receipt_id = :receipt_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['receipt_id' => $receiptId]);
        return $stmt->fetchColumn();
    }
}
