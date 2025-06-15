<?php
    require_once __DIR__ . '/../../app/autoload.php';
    AuthController::check_employee_access();
    require_once __DIR__ . '/partials/header.php';
?>
<?php NotificationHandler::display(); ?>

<div class="container mt-5">
    <div class="row">
        <!-- Özet Kartları -->
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Toplam Müşteri</h5>
                    <h2 class="card-text">
                        <?= count(CustomerRepository::getInstance()->getAll()) ?>
                    </h2>
                    <small>Kayıtlı Müşteri Sayısı</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Toplam Ürün</h5>
                    <h2 class="card-text">
                        <?= count(ProductRepository::getInstance()->getAll()) ?>
                    </h2>
                    <small>Mevcut Ürün Sayısı</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <?php 
                        $receipts = ReceiptRepository::getInstance()->getAll();
                        $total_revenue = array_reduce($receipts, function($carry, $receipt) {
                            return $carry + $receipt->receipt_total_amount;
                        }, 0);
                    ?>
                    <h5 class="card-title">Toplam Kazanç</h5>
                    <h2 class="card-text">
                        <?= number_format($total_revenue, 2) ?> ₺
                    </h2>
                    <small>Tüm Zamanlar</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white">
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
        <!-- Son İşlemler -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Son Fişler</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fiş No</th>
                                    <th>Müşteri</th>
                                    <th>Tutar</th>
                                    <th>Tarih</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $customerRepo = CustomerRepository::getInstance();
                                $recent_receipts = array_slice($receipts, -5);
                                foreach ($recent_receipts as $receipt): 
                                    $customer = $customerRepo->get($receipt->customer_id);
                                ?>
                                <tr>
                                    <td>#<?= $receipt->receipt_id ?></td>
                                    <td><?= htmlspecialchars($customer->customer_nickname) ?></td>
                                    <td><?= number_format($receipt->receipt_total_amount, 2) ?> ₺</td>
                                    <td><?= date('d.m.Y H:i', strtotime($receipt->receipt_timestamp)) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- En Çok Satan Ürünler -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">En Çok Satan Ürünler</h5>
                </div>
                <div class="card-body">
                    <?php
                    $pdo = DatabaseController::getPDO();
                    $stmt = $pdo->query("
                        SELECT p.product_name, SUM(rp.product_quantity) as total_quantity
                        FROM RECEIPT_PRODUCTS rp
                        JOIN PRODUCT p ON p.product_id = rp.product_id
                        GROUP BY rp.product_id
                        ORDER BY total_quantity DESC
                        LIMIT 5
                    ");
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