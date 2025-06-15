<?php
    require_once __DIR__ .'/../../app/Functions/log_errors.php';
    require_once __DIR__ .'/../../app/autoload.php';
    AuthController::redirect_if_logged_in(Employee::class, '../dashboard');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (Employee::login(DatabaseController::getPDO(), $_POST['email'], $_POST['password'])) {
            header('Location: ../dashboard/');
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Giriş Yap</title>
</head>
<body>
    
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow" style="width: 350px;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Çalışan Girişi</h3>
                <form method="POST" action="./">
                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" placeholder="E posta" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Şifre" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-2">Giriş Yap</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php NotificationHandler::display(); ?>
