<?php
require_once __DIR__ . '/../../../app/autoload.php';
require_once __DIR__ . '/../../../app/Functions/check_employee_access.php';

$parent_id = $_GET['parent_id'] ?? null;

// Eğer parent_id varsa, var olduğunu kontrol et
if ($parent_id) {
    $categoryRepo = CategoryRepository::getInstance();
    $parent = $categoryRepo->get($parent_id);
    if (!$parent) {
        NotificationHandler::error('Belirtilen üst kategori bulunamadı.');
        header('Location: index.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = trim($_POST['category_name'] ?? '');
    
    if (empty($categoryName)) {
        NotificationHandler::error('Kategori adı boş olamaz.');
    } else {
        // Create new category
        $category = new Category();
        $category->category_name = $categoryName;
        
        $createdCategory = CategoryRepository::getInstance()->create($category);
        if ($createdCategory) {
            // Eğer parent_id varsa, ağaç ilişkisini kur
            if ($parent_id) {
                if (CategoryTreeRepository::getInstance()->addSubCategory($parent_id, $createdCategory->category_id)) {
                    NotificationHandler::success('Alt kategori başarıyla oluşturuldu.');
                } else {
                    // If tree relationship fails, delete the created category
                    CategoryRepository::getInstance()->delete($createdCategory->category_id);
                    NotificationHandler::error('Alt kategori ilişkisi oluşturulamadı.');
                }
            } else {
                NotificationHandler::success('Ana kategori başarıyla oluşturuldu.');
            }
            header('Location: index.php');
            exit;
        } else {
            NotificationHandler::error('Kategori oluşturulurken bir hata oluştu.');
        }
    }
}

$pageTitle = $parent_id ? 'Alt Kategori Ekle' : 'Ana Kategori Ekle';
require_once __DIR__ . '/../partials/header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <?php if ($parent_id): ?>
                            "<?= htmlspecialchars($parent->category_name) ?>" Kategorisine Alt Kategori Ekle
                        <?php else: ?>
                            Yeni Ana Kategori Ekle
                        <?php endif; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="category_name" class="form-label">
                                <?= $parent_id ? 'Alt Kategori Adı' : 'Kategori Adı' ?>
                            </label>
                            <input type="text" class="form-control" id="category_name" name="category_name" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-secondary">İptal</a>
                            <button type="submit" class="btn btn-primary">
                                <?= $parent_id ? 'Alt Kategori Ekle' : 'Ana Kategori Ekle' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
