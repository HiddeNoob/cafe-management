<?php
require_once __DIR__ . '/../../../app/Functions/check_employee_access.php';
require_once __DIR__ . '/../../../app/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    if ($id) {
        // Check if category has sub-categories
        if (!empty(CategoryTreeRepository::getInstance()->getSubCategoryIds($id))) {
            NotificationHandler::error('Alt kategorisi olan kategoriler silinemez.');
            header('Location: index.php');
            exit;
        }

        // Delete from CATEGORY_TREE where this is a sub-category
        $pdo = DatabaseController::getPDO();
        $stmt = $pdo->prepare("DELETE FROM CATEGORY_TREE WHERE sub_category_id = :id");
        $stmt->execute(['id' => $id]);

        // Then delete the category
        $deleted = CategoryRepository::getInstance()->delete($id);
        if ($deleted) {
            NotificationHandler::success('Kategori silindi.');
        } else {
            NotificationHandler::error('Kategori silinemedi.');
        }
    }
}
header('Location: index.php');
exit;
?>
