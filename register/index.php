<?php
    session_start();
    require_once __DIR__ . '/../app/Functions/log_errors.php';
    require_once __DIR__ .'/../app/autoload.php';
    try {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $required = ['customer_name', 'customer_surname', 'customer_phone', 'customer_email', 'customer_nickname', 'customer_password'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("Lütfen tüm alanları doldurun.");
                }
            }
            if (count(CustomerRepository::getInstance()->findBy(["customer_nickname" => $_POST['customer_nickname']])) > 0) {
                throw new Exception("Bu kullanıcı adı zaten alınmış.");
            }
            $customer = new Customer();
            foreach ($_POST as $key => $value) {
                if (property_exists($customer, $key)) {
                    $customer->$key = $key === 'customer_password' ? password_hash($value, PASSWORD_DEFAULT) : $value;
                }
            }
            $ok = CustomerRepository::getInstance()->create($customer);
            if ($ok) {
                NotificationHandler::notify("Kayıt başarılı", "success");
            } else {
                throw new Exception("Kayıt başarısız.");
            }
        }
    } catch (Exception $e) {
        NotificationHandler::notify($e->getMessage(), "danger");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow" style="width: 400px;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Kayıt Ol</h3>
                <form method="POST" class="p-w" action="./">
                    <div class="mb-3">
                        <label for="name" class="form-label">Ad</label>
                        <input type="text" id="name" name="customer_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="surname" class="form-label">Soyad</label>
                        <input type="text" id="surname" name="customer_surname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefon</label>
                        <input type="tel" id="phone" name="customer_phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-posta</label>
                        <input type="email" id="email" name="customer_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nickname" class="form-label">Kullanıcı Adı</label>
                        <input type="text" id="nickname" name="customer_nickname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Şifre</label>
                        <input type="password" id="password" name="customer_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Kayıt Ol</button>
                    <a href="../login" class="btn btn-primary w-100 mt-2">Giriş Yap</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php NotificationHandler::display(); ?>