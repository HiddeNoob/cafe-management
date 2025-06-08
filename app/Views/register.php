<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow" style="width: 400px;">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Kayıt Ol</h3>
            <form method="POST" action="/register">
                <div class="mb-3">
                    <label for="name" class="form-label">Ad</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="surname" class="form-label">Soyad</label>
                    <input type="text" id="surname" name="surname" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Telefon</label>
                    <input type="tel" id="phone" name="phone" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-posta</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="nickname" class="form-label">Kullanıcı Adı</label>
                    <input type="text" id="nickname" name="nickname" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Şifre</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Kayıt Ol</button>
            </form>
            <?php require_once __DIR__ . '/../Views/showException.php'; ?>
        </div>
    </div>
</div>
