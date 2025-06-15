<?php
require_once __DIR__ . '/../../../app/autoload.php';
AuthController::check_employee_access();

// Repository'leri al
$receiptRepo = ReceiptRepository::getInstance();
$customerRepo = CustomerRepository::getInstance();

// Tüm fişleri getir
$receipts = $receiptRepo->getAll();
?>

<?php require_once __DIR__ . '/../partials/header.php'; ?>
<?php NotificationHandler::display(); ?>


<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Fişler</h2>
        <a href="create.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Yeni Fiş Oluştur
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_GET['success'] === '1' ? 'Fiş başarıyla tamamlandı!' : 'İşlem başarılı!'; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fiş No</th>
                            <th>Müşteri</th>
                            <th>Tarih</th>
                            <th>Toplam Tutar</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($receipts as $receipt): 
                            $customer = $customerRepo->get($receipt->customer_id);
                        ?>
                            <tr>
                                <td>#<?= htmlspecialchars($receipt->receipt_id) ?></td>
                                <td><?= $customer ? htmlspecialchars($customer->customer_nickname) : 'Bilinmiyor' ?></td>
                                <td><?= htmlspecialchars(date('d.m.Y H:i', strtotime($receipt->receipt_timestamp))) ?></td>
                                <td><?= number_format($receipt->receipt_total_amount, 2) ?> ₺</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="view.php?id=<?= $receipt->receipt_id ?>" 
                                           class="btn btn-sm btn-info ms-2 rounded">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="edit.php?id=<?= $receipt->receipt_id ?>" 
                                           class="btn btn-sm btn-primary ms-2 rounded">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="delete.php" class="d-inline ms-2" onsubmit="return confirmDelete()">
                                            <input type="hidden" name="id" value="<?= $receipt->receipt_id ?>">
                                            <button type="submit" class="btn btn-sm btn-danger rounded">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>