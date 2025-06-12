<?php
    session_start();
    require_once __DIR__ . '/../../app/Functions/log_errors.php';
    require_once __DIR__ .'/../../app/autoload.php';
    try {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $required = ['employee_name', 'employee_surname', 'employee_phone', 'employee_email','employee_password'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("Lütfen tüm alanları doldurun.");
                }
            }
            if (count(EmployeeRepository::getInstance()->findBy(["employee_email" => $_POST['employee_email']])) > 0) {
                throw new Exception("Bu e-posta zaten kullanılıyor.");
            }
            $employee = new Employee();
            foreach ($_POST as $key => $value) {
                if (property_exists($employee, $key)) {
                    $employee->$key = $value;
                }
            }
            $employee->employee_password = password_hash($employee->employee_password, PASSWORD_DEFAULT);
            $employee->employee_hire_date = date('Y-m-d');
            $ok = EmployeeRepository::getInstance()->create($employee);
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
                <form method="POST" action="./">
                    <div class="mb-3">
                        <label for="employee_name" class="form-label">Ad</label>
                        <input type="text" id="employee_name" name="employee_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="employee_surname" class="form-label">Soyad</label>
                        <input type="text" id="employee_surname" name="employee_surname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="employee_phone" class="form-label">Telefon</label>
                        <input type="tel" id="employee_phone" name="employee_phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="employee_email" class="form-label">E-posta</label>
                        <input type="email" id="employee_email" name="employee_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="employee_password" class="form-label">Şifre</label>
                        <input type="password" id="employee_password" name="employee_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Kayıt Ol</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>


