<?php
require_once __DIR__ . '/../../../app/autoload.php';
AuthController::check_employee_access();
$product = null;
$categories = CategoryRepository::getInstance()->getAll();
$id = intval($_GET['id'] ?? 0);
if ($id) {
    $product = ProductRepository::getInstance()->get($id);
    if (!$product) {
        NotificationHandler::error('Ürün bulunamadı.');
        header('Location: index.php');
        exit;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['product_name'] ?? '');
    $desc = trim($_POST['product_description'] ?? '');
    $price = floatval($_POST['product_price'] ?? 0);
    $cat = intval($_POST['category_id'] ?? 0);
    $img = trim($_POST['product_img_url'] ?? '');
    if ($name && $cat && $price > 0) {
        // Model instance ile güncelleme
        $product->product_name = $name;
        $product->product_description = $desc;
        $product->product_price = $price;
        $product->category_id = $cat;
        $product->product_img_url = $img;
        ProductRepository::getInstance()->update($product); // Sadece model instance gönderiliyore
            NotificationHandler::success('Ürün başarıyla güncellendi.');
            header('Location: index.php');
        exit;
    } else {
        NotificationHandler::error('Tüm zorunlu alanları doldurun.');
    }
}
require_once __DIR__ . '/../partials/header.php';
NotificationHandler::display();
?>
<div class="container mt-5">
    <h2>Ürün Düzenle</h2>
    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Ürün Adı *</label>
            <input type="text" name="product_name" class="form-control" value="<?= htmlspecialchars($product->product_name ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Açıklama</label>
            <textarea name="product_description" class="form-control"><?= htmlspecialchars($product->product_description ?? '') ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Fiyat (₺) *</label>
            <input type="number" name="product_price" class="form-control" min="0" step="0.01" value="<?= htmlspecialchars($product->product_price ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori *</label>
            <select name="category_id" class="form-select" required>
                <option value="">Seçiniz</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat->category_id) ?>" <?= ($product && $product->category_id == $cat->category_id) ? 'selected' : '' ?>>#<?= htmlspecialchars($cat->category_id) ?> - <?= htmlspecialchars($cat->category_name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Resim URL</label>
            <input type="url" name="product_img_url" class="form-control" value="<?= htmlspecialchars($product->product_img_url ?? '') ?>">
        </div>
        <button type="submit" class="btn btn-warning">Güncelle</button>
        <a href="index.php" class="btn btn-secondary">İptal</a>
    </form>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
