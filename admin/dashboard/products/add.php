<?php
require_once __DIR__ . '/../../../app/autoload.php';
AuthController::check_employee_access();

$categories = CategoryRepository::getInstance()->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hasError = false;
    
    // Validate required fields are present
    if (empty($_POST['product_name']) || !isset($_POST['product_price']) || empty($_POST['category_id'])) {
        NotificationHandler::error('Tüm zorunlu alanları doldurun.');
        $hasError = true;
    } else {
        // Get and validate category first
        $categoryId = filter_var($_POST['category_id'], FILTER_VALIDATE_INT);
        if ($categoryId === false || !CategoryRepository::getInstance()->get($categoryId)) {
            NotificationHandler::error('Geçerli bir kategori seçmelisiniz.');
            $hasError = true;
        }

        // Validate price
        $price = filter_var($_POST['product_price'], FILTER_VALIDATE_FLOAT);
        if ($price === false || $price <= 0) {
            NotificationHandler::error('Geçerli bir fiyat giriniz.');
            $hasError = true;
        }

        if (!$hasError) {
            $product = new Product();
            $product->category_id = $categoryId;
            $product->product_name = trim($_POST['product_name']);
            $product->product_description = trim($_POST['product_description'] ?? '');
            $product->product_price = $price;
            $product->product_img_url = trim($_POST['product_img_url'] ?? '');
            
            if (ProductRepository::getInstance()->create($product)) {

                NotificationHandler::success('Ürün başarıyla eklendi.');
                header('Location: index.php');
                exit;
            } else {
                NotificationHandler::error('Ürün eklenirken bir hata oluştu.');
            }
        }
    }
}

require_once __DIR__ . '/../partials/header.php';
?>
<div class="container mt-5">
    <h2>Yeni Ürün Ekle</h2>
    <?php NotificationHandler::display(); ?>
    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Ürün Adı *</label>
            <input type="text" name="product_name" class="form-control" value="<?= htmlspecialchars($_POST['product_name'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Açıklama</label>
            <textarea name="product_description" class="form-control"><?= htmlspecialchars($_POST['product_description'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Fiyat (₺) *</label>
            <input type="number" name="product_price" class="form-control" min="0" step="0.01" value="<?= htmlspecialchars($_POST['product_price'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori *</label>
            <select name="category_id" class="form-select" required>
                <option value="" disabled <?= !isset($_POST['category_id']) ? 'selected' : '' ?>>Seçiniz</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat->category_id) ?>" <?= (isset($_POST['category_id']) && intval($_POST['category_id']) === intval($cat->category_id)) ? 'selected' : '' ?>>
                        #<?= htmlspecialchars($cat->category_id) ?> - <?= htmlspecialchars($cat->category_name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="form-text">Kategori seçimi zorunludur.</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Resim URL</label>
            <input type="url" name="product_img_url" class="form-control" value="<?= htmlspecialchars($_POST['product_img_url'] ?? '') ?>">
        </div>
        <button type="submit" class="btn btn-success">Kaydet</button>
        <a href="index.php" class="btn btn-secondary">İptal</a>
    </form>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
