<?php
require_once __DIR__ . '/../../app/autoload.php';
AuthController::check_customer_access();

$receiptId = $_GET['id'] ?? 0;
if (!$receiptId) {
    header('Location: index.php');
    exit;
}

// Repository'leri al
$receiptRepo = ReceiptRepository::getInstance();
$receiptProductsRepo = ReceiptProductsRepository::getInstance();
$productRepo = ProductRepository::getInstance();
$customerRepo = CustomerRepository::getInstance();

// Fiş bilgilerini al
$receipt = $receiptRepo->get($receiptId);

// Eğer fiş bulunamadıysa veya başka bir müşteriye aitse geri döndür
if (!$receipt || $receipt->customer_id != $_SESSION['customer_id']) {
    header('Location: index.php');
    exit;
}

// Müşteri bilgilerini al
$customer = $customerRepo->get($receipt->customer_id);

// Fiş ürünlerini getir
$products = $receiptProductsRepo->getProductsByReceiptId($receiptId);
?>

<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            Fiş Detayı #<?= htmlspecialchars($receipt->receipt_id) ?>
        </h2>
        <a href="index.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Geri Dön
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <strong>Fiş Bilgileri</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Müşteri:</strong> <?= htmlspecialchars($customer->customer_nickname) ?></p>
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
                                <td><?= number_format($item['product_total_amount'], 2) ?> ₺</td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="2" class="text-end"><strong>Genel Toplam:</strong></td>
                            <td><strong><?= number_format($receipt->receipt_total_amount, 2) ?> ₺</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
