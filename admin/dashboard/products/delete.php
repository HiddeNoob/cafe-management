<?php
require_once __DIR__ . '/../../../app/Functions/check_employee_access.php';
require_once __DIR__ . '/../../../app/autoload.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    if ($id) {
        $deleted = ProductRepository::getInstance()->delete($id);
        if ($deleted) {
            NotificationHandler::success('Ürün silindi.');
        } else {
            NotificationHandler::error('Ürün silinemedi.');
        }
    }
}
header('Location: index.php');
exit;
