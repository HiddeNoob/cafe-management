<?php
    require_once __DIR__ . "/../app/Functions/log_errors.php";
    require_once __DIR__ . '/../app/autoload.php';
    AuthController::check_customer_access();
?>

<?php require_once __DIR__ . '/partials/header.php'; ?>


<div class="container mt-5">
    <h2 class="mb-4">Müşteri Paneli</h2>
    
    <?php
        $customer_id = $_SESSION['customer_id'];
        $customer = CustomerRepository::getInstance()->get($customer_id);
        $receipts = array_filter(ReceiptRepository::getInstance()->getAll(), function($receipt) use ($customer_id) {
            return $receipt->customer_id == $customer_id;
        });
        
        $total_spent = array_reduce($receipts, function($carry, $receipt) {
            return $carry + $receipt->receipt_total_amount;
        }, 0);

        $receiptProductsRepo = ReceiptProductsRepository::getInstance();
        $total_products = 0;
        foreach ($receipts as $receipt) {
            $products = $receiptProductsRepo->getProductsByReceiptId($receipt->receipt_id);
            $total_products += array_reduce($products, function($carry, $product) {
                return $carry + $product['product_quantity'];
            }, 0);
        }
    ?>

    <div class="row">
        <!-- Özet Kartları -->
        <div class="col-md-4 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Toplam Harcama</h5>
                    <h2 class="card-text">
                        <?= number_format($total_spent, 2) ?> ₺
                    </h2>
                    <small>Tüm Zamanlar</small>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Toplam Ürün</h5>
                    <h2 class="card-text">
                        <?= $total_products ?>
                    </h2>
                    <small>Satın Alınan Ürün Sayısı</small>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Toplam Fiş</h5>
                    <h2 class="card-text">
                        <?= count($receipts) ?>
                    </h2>
                    <small>Kesilen Fiş Sayısı</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Son Alışverişler -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Son Alışverişleriniz</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fiş No</th>
                                    <th>Ürün Sayısı</th>
                                    <th>Toplam Tutar</th>
                                    <th>Tarih</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $recent_receipts = array_slice(array_reverse($receipts), 0, 5);
                                foreach ($recent_receipts as $receipt):
                                    $receipt_products = $receiptProductsRepo->getProductsByReceiptId($receipt->receipt_id);
                                    $product_count = array_reduce($receipt_products, function($carry, $product) {
                                        return $carry + $product['product_quantity'];
                                    }, 0);
                                ?>
                                <tr>
                                    <td>#<?= $receipt->receipt_id ?></td>
                                    <td><?= $product_count ?> adet</td>
                                    <td><?= number_format($receipt->receipt_total_amount, 2) ?> ₺</td>
                                    <td><?= date('d.m.Y H:i', strtotime($receipt->receipt_timestamp)) ?></td>
                                    <td>
                                        <a href="receipts/view.php?id=<?= $receipt->receipt_id ?>" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> Görüntüle
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- En Çok Aldığınız Ürünler -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">En Çok Aldığınız Ürünler</h5>
                </div>
                <div class="card-body">
                    <?php
                    $pdo = DatabaseController::getPDO();
                    $stmt = $pdo->prepare("
                        SELECT p.product_name, SUM(rp.product_quantity) as total_quantity
                        FROM RECEIPT_PRODUCTS rp
                        JOIN PRODUCT p ON p.product_id = rp.product_id
                        JOIN RECEIPT r ON r.receipt_id = rp.receipt_id
                        WHERE r.customer_id = :customer_id
                        GROUP BY rp.product_id
                        ORDER BY total_quantity DESC
                        LIMIT 5
                    ");
                    $stmt->execute(['customer_id' => $customer_id]);
                    $top_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <div class="list-group">
                        <?php foreach ($top_products as $product): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($product['product_name']) ?>
                            <span class="badge bg-primary rounded-pill">
                                <?= $product['total_quantity'] ?> adet
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>