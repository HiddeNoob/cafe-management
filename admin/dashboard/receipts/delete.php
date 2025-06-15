<?php
require_once __DIR__ . '/../../../app/autoload.php';
AuthController::check_employee_access();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    if ($id) {
        // Önce fiş ürünlerini sil
        $receiptProductsRepo = ReceiptProductsRepository::getInstance();
        $pdo = DatabaseController::getPDO();
        $stmt = $pdo->prepare("DELETE FROM RECEIPT_PRODUCTS WHERE receipt_id = :id");
        $stmt->execute(['id' => $id]);

        // Sonra fişi sil
        $deleted = ReceiptRepository::getInstance()->delete($id);
        if ($deleted) {
            NotificationHandler::success('Fiş silindi.');
        } else {
            NotificationHandler::error('Fiş silinemedi.');
        }
    }
}
header('Location: index.php');
exit;
