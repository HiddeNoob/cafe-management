<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Erişim Engellendi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-7xl font-bold text-yellow-500 mb-4">403</h1>
        <h2 class="text-2xl font-semibold mb-2">Erişim Engellendi</h2>
        <p class="mb-6 text-gray-600">Bu sayfaya erişim yetkiniz bulunmamaktadır.</p>
        <a href="<?= $redirect_url ?> " class="inline-block px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Giriş Sayfasına Dön</a>
    </div>
</body>
</html>