<?php
    require_once __DIR__ . '/../app/Functions/log_errors.php';
    require_once __DIR__ .'/../app/autoload.php';
    AuthController::redirect_if_logged_in(Customer::class, '../dashboard');
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Customer login işlemini doğrudan burada yazabilirsin
        if (Customer::login(DatabaseController::getPDO(), $_POST['customer_username'], $_POST['customer_password'])) {            header('Location: ../dashboard');
            exit();
        } else {
            NotificationHandler::notify("Kullanıcı adı veya şifre yanlış.", "danger");
        }
    }
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Giriş Yap</title>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow" style="width: 350px;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Giriş Yap</h3>                
                <form method="POST" action="./">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="customer_username" placeholder="Kullanıcı Adı" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="customer_password" placeholder="Şifre" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-2">Giriş Yap</button>
                </form>
                <a href="../register" class="btn btn-secondary w-100 mb-2">Kayıt Ol</a>
                <a href="/admin" class="btn btn-warning w-100">Yönetici Girişi</a>
            </div>
        </div>
    </div>

</body>
</html>
<?php NotificationHandler::display(); ?>


