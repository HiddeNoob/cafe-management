<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        html, body {
            height: 100%;
        }
    </style>
</head>
<body class="bg-light text-dark d-flex flex-column min-vh-100">
    <header class="bg-white shadow p-4 mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="h4 fw-bold m-0">Müşteri Paneli</h1>
            <nav class="d-flex gap-3">
                <a href="<?= dirname($_SERVER['PHP_SELF']) === '/dashboard' ? './' : '../' ?>" class="text-primary text-decoration-none">Ana Sayfa</a>
                <a href="<?= dirname($_SERVER['PHP_SELF']) === '/dashboard' ? 'receipts' : './receipts' ?>" class="text-primary text-decoration-none">Fişlerim</a>
                <a href="/logout" class="text-danger text-decoration-none">Çıkış</a>
            </nav>
        </div>
    </header>
