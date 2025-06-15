<?php
require_once __DIR__ . '/../../../app/autoload.php';
AuthController::check_employee_access();

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $customer_username = $_POST['customer_username'] ?? '';
        
        // Repository'leri al
        $customerRepo = CustomerRepository::getInstance();
        $receiptRepo = ReceiptRepository::getInstance();
        
        // Kullanıcıyı bul
        $customers = $customerRepo->findBy(['customer_nickname' => $customer_username]);
        
        if (empty($customers)) {
            $error = "Kullanıcı bulunamadı!";
        } else {
            $customer = $customers[0];
            
            // Yeni fiş oluştur
            $receipt = new Receipt();
            $receipt->customer_id = $customer->customer_id;
            $receipt->employee_id = $_SESSION['employee_id'];
            $receipt->receipt_timestamp = date('Y-m-d H:i:s');
            $receipt->receipt_total_amount = 0;
            
            $newReceipt = $receiptRepo->create($receipt);
            
            if ($newReceipt) {
                header("Location: edit.php?id=" . $newReceipt->receipt_id);
                exit;
            } else {
                $error = "Fiş oluşturulurken bir hata oluştu!";
            }
        }
    } catch (Exception $e) {
        $error = "Bir hata oluştu: " . $e->getMessage();
    }
}
?>

<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Yeni Fiş Oluştur</h3>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="customer_username" class="form-label">Müşteri Adı</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="customer_username" 
                                   name="customer_username" 
                                   placeholder="Müşterinin görünen adını girin"
                                   required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Fiş Oluştur
                            </button>
                            <a href="index.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Geri Dön
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
