<?php
    require_once __DIR__ .'/../../app/autoload.php';
    $redirect_url = '/admin/dashboard';
    require_once __DIR__ .'/../../app/Functions/redirect_if_logged_in.php';
    require_once __DIR__ . '/../../app/Functions/log_errors.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (Employee::login(DatabaseController::getPDO(), $_POST['username'], $_POST['password'])) {
            header('Location: /admin/dashboard/');
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
                        <input type="text" class="form-control" name="username" placeholder="E posta" required>
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