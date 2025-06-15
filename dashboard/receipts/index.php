<?php
require_once __DIR__ . '/../../app/autoload.php';
AuthController::check_customer_access();

$pdo = DatabaseController::getPDO();
$receiptRepo = ReceiptRepository::getInstance();

// Sadece giriş yapmış müşterinin fişlerini getir
$receipts = $receiptRepo->findBy(['customer_id' => $_SESSION['customer_id']]);
?>

<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Fişlerim</h2>

    <?php if (empty($receipts)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Henüz hiç fişiniz bulunmuyor.
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fiş No</th>
                                <th>Tarih</th>
                                <th>Toplam Tutar</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($receipts as $receipt): ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($receipt->receipt_id) ?></td>
                                    <td><?= htmlspecialchars(date('d.m.Y H:i', strtotime($receipt->receipt_timestamp))) ?></td>
                                    <td><?= number_format($receipt->receipt_total_amount, 2) ?> ₺</td>
                                    <td>
                                        <a href="view.php?id=<?= $receipt->receipt_id ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Detay
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>

