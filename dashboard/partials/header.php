<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
        }

        .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
        }
    </style>

</head>
<body class="flex flex-col min-h-screen bg-gray-100 text-gray-800">
    <header class="bg-white shadow p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Kahve Paneli</h1>
            <nav class="space-x-4">
                <a href="/dashboard" class="text-blue-600 hover:underline">Ana Sayfa</a>
                <a href="/receipts" class="text-blue-600 hover:underline">Fişlerim</a>
                <a href="/logout" class="text-red-500 hover:underline">Çıkış</a>
            </nav>
        </div>
    </header>
