<?php
require_once __DIR__ . '/../../../app/autoload.php';
AuthController::check_employee_access();

$error = null;
$success = null;

// Repository'leri al
$receiptRepo = ReceiptRepository::getInstance();
$receiptProductsRepo = ReceiptProductsRepository::getInstance();
$productRepo = ProductRepository::getInstance();
$customerRepo = CustomerRepository::getInstance();

// Fiş ID'sini al
$receipt_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$receipt_id) {
    header('Location: index.php');
    exit;
}

// Fişi ve müşteri bilgilerini getir
$receipt = $receiptRepo->get($receipt_id);
if (!$receipt) {
    header('Location: index.php');
    exit;
}
$customer = $customerRepo->get($receipt->customer_id);

// Tüm ürünleri getir
$products = $productRepo->getAll();

// POST işlemi - Ürün ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'] ?? 0;
    $quantity = $_POST['quantity'] ?? 0;
    
    if ($product_id && $quantity > 0) {
        $product = $productRepo->get($product_id);
        if ($product) {
            // Ürün fiyatını al ve toplam tutarı hesapla
            $total_amount = $product->product_price * $quantity;
            
            // Ürünü fişe ekle
            if ($receiptProductsRepo->addProduct($receipt_id, $product_id, $quantity, $total_amount)) {
                // Fiş toplam tutarını güncelle
                $receipt->receipt_total_amount = $receiptProductsRepo->getReceiptTotal($receipt_id);
                $receiptRepo->update($receipt);
                $success = "Ürün başarıyla eklendi!";
            } else {
                $error = "Ürün eklenirken bir hata oluştu!";
            }
        }
    }
}

// Fiş ürünlerini getir
$receipt_products = $receiptProductsRepo->getProductsByReceiptId($receipt_id);
?>

<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Fiş Düzenle #<?= htmlspecialchars($receipt->receipt_id) ?></h2>
            <p class="text-muted mb-0">Müşteri: <?= htmlspecialchars($customer->customer_nickname) ?></p>
        </div>
        <div>
            <form method="POST" action="delete.php" style="display: inline-block;" onsubmit="return confirmDelete()">
                <input type="hidden" name="id" value="<?= $receipt->receipt_id ?>">
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Fişi Sil
                </button>
            </form>
            <a href="index.php" class="btn btn-secondary ms-2">
                <i class="bi bi-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Yeni Ürün Ekle</strong>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Ürün</label>
                            <select class="form-select" id="product_id" name="product_id" required>
                                <option value="">Ürün Seçin</option>
                                <?php foreach ($products as $product): ?>
                                    <option value="<?= $product->product_id ?>">
                                        <?= htmlspecialchars($product->product_name) ?> 
                                        (<?= number_format($product->product_price, 2) ?> ₺)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Miktar</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="quantity" 
                                   name="quantity" 
                                   min="1" 
                                   value="1" 
                                   required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Ürün Ekle
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <strong>Fiş Ürünleri</strong>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ürün</th>
                                    <th>Miktar</th>
                                    <th>Birim Fiyat</th>
                                    <th>Toplam</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($receipt_products as $item): 
                                    $product = $productRepo->get($item['product_id']);
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($product->product_name) ?></td>
                                        <td><?= htmlspecialchars($item['product_quantity']) ?></td>
                                        <td><?= number_format($item['product_total_amount'] / $item['product_quantity'], 2) ?> ₺</td>
                                        <td>
                                            <?= number_format($item['product_total_amount'], 2) ?> ₺
                                            <form method="POST" action="delete_product.php" class="d-inline float-end" onsubmit="return confirm('Bu ürünü fişten silmek istediğinize emin misiniz?')">
                                                <input type="hidden" name="receipt_id" value="<?= $receipt_id ?>">
                                                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Genel Toplam:</strong></td>
                                    <td><strong><?= number_format($receipt->receipt_total_amount, 2) ?> ₺</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
<?php NotificationHandler::display(); ?>
