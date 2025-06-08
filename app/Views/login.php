<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow" style="width: 350px;">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Giriş Yap</h3>
            <form method="POST" action="/login">
                <div class="mb-3">
                    <input type="text" class="form-control" name="username" placeholder="Kullanıcı Adı" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Şifre" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-2">Giriş Yap</button>
            </form>
            <a href="/register" class="btn btn-secondary w-100">Kayıt Ol</a>
            <?php require_once __DIR__ . '/../Views/showException.php'; ?>
        </div>
    </div>
</div>
