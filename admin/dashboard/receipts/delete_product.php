<?php
require_once __DIR__ . '/../../../app/autoload.php';
AuthController::check_employee_access();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receipt_id = intval($_POST['receipt_id'] ?? 0);
    $product_id = intval($_POST['product_id'] ?? 0);

    if ($receipt_id && $product_id) {
        $receiptProductsRepo = ReceiptProductsRepository::getInstance();
        $receiptRepo = ReceiptRepository::getInstance();

        // Ürünü fişten sil
        if ($receiptProductsRepo->deleteProduct($receipt_id, $product_id)) {
            // Fiş toplam tutarını güncelle
            $receipt = $receiptRepo->get($receipt_id);
            if ($receipt) {
                $receipt->receipt_total_amount = $receiptProductsRepo->getReceiptTotal($receipt_id);
                $receiptRepo->update($receipt);
                NotificationHandler::success('Ürün fişten silindi.');
            }
        } else {
            NotificationHandler::error('Ürün silinemedi.');
        }
    }
}

// Fiş düzenleme sayfasına geri dön
header('Location: edit.php?id=' . $receipt_id);
exit;
