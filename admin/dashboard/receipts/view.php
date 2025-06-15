<?php
require_once __DIR__ . '/../../../app/autoload.php';
AuthController::check_employee_access();

// Repository'leri al
$receiptRepo = ReceiptRepository::getInstance();
$receiptProductsRepo = ReceiptProductsRepository::getInstance();
$productRepo = ProductRepository::getInstance();
$customerRepo = CustomerRepository::getInstance();

$receipt_id = $_GET['id'] ?? 0;
if (!$receipt_id) {
    header('Location: index.php');
    exit;
}

// Fiş ve müşteri bilgilerini al
$receipt = $receiptRepo->get($receipt_id);
if (!$receipt) {
    header('Location: index.php');
    exit;
}
$customer = $customerRepo->get($receipt->customer_id);

// Fiş ürünlerini getir
$products = $receiptProductsRepo->getProductsByReceiptId($receipt_id);
?>

<?php require_once __DIR__ . '/../partials/header.php'; ?>
<?php NotificationHandler::display(); ?>


<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Fiş Detayı #<?= htmlspecialchars($receipt->receipt_id) ?></h2>
            <p class="text-muted mb-0">Müşteri: <?= htmlspecialchars($customer->customer_nickname) ?></p>
        </div>
        <div>
            <a href="index.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Geri Dön
            </a>
            <?php if ($receipt->receipt_status !== 'completed'): ?>
                <a href="edit.php?id=<?= $receipt->receipt_id ?>" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Düzenle
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <strong>Fiş Bilgileri</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Müşteri:</strong> <?= htmlspecialchars($customer->customer_username) ?></p>
                </div>
                <div class="col-md-4">
                    <p><strong>Tarih:</strong> <?= htmlspecialchars(date('d.m.Y H:i', strtotime($receipt->receipt_timestamp))) ?></p>
                </div>
                <div class="col-md-4">
                    <p><strong>Toplam Tutar:</strong> <?= number_format($receipt->receipt_total_amount, 2) ?> ₺</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>Ürünler</strong>
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
                        <?php foreach ($products as $item): 
                            $product = $productRepo->get($item['product_id']);
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($product->product_name) ?></td>
                                <td><?= htmlspecialchars($item['product_quantity']) ?></td>
                                <td><?= number_format($item['product_total_amount'] / $item['product_quantity'], 2) ?> ₺</td>
                                <td><?= number_format($item['product_total_amount'], 2) ?> ₺</td>
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

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
