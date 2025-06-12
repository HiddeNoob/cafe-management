<?php
    require_once __DIR__ . '/../../../app/Functions/log_errors.php';
    require_once __DIR__ . '/../../../app/Functions/check_employee_access.php';
    require_once __DIR__ . '/../../../app/autoload.php';
    $products = ProductRepository::getInstance()->getAll();
?>
<?php 
    require_once __DIR__ . '/../partials/header.php';
?>

<div class="container mt-5">
    <?php NotificationHandler::display(); ?>
    <h2 class="mb-4">Ürünler</h2>
    <div class="mb-3">
        <a href="add.php" class="btn btn-success">+ Yeni Ürün Ekle</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle table-striped border rounded shadow-sm">
            <thead class="table-primary">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Ad</th>
                    <th scope="col">Açıklama</th>
                    <th scope="col">Fiyat</th>
                    <th scope="col">Resim</th>
                    <th scope="col">İşlemler</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <th scope="row" class="text-secondary"><?= htmlspecialchars($product->product_id) ?></th>
                    <td>
    <?php if ($product->category_id): ?>
        <a href="/admin/dashboard/categories/index.php?category_id=<?= htmlspecialchars($product->category_id) ?>" class="badge bg-info text-dark text-decoration-none">
            #<?= htmlspecialchars($product->category_id) ?>
        </a>
    <?php else: ?>
        <span class="badge bg-secondary">Kategorisiz</span>
    <?php endif; ?>
</td>
                    <td class="fw-semibold text-primary"><?= htmlspecialchars($product->product_name) ?></td>
                    <td style="max-width:220px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="<?= htmlspecialchars($product->product_description) ?>">
                        <?= htmlspecialchars($product->product_description) ?>
                    </td>
                    <td class="fw-bold text-success">₺<?= htmlspecialchars($product->product_price) ?></td>
                    <td>
                        <?php if (!empty($product->product_img_url)): ?>
                            <img src="<?= htmlspecialchars($product->product_img_url) ?>" alt="Ürün Resmi" class="img-thumbnail" style="max-width:80px;max-height:80px;object-fit:cover;">
                        <?php else: ?>
                            <span class="text-muted">Yok</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?= htmlspecialchars($product->product_id) ?>" class="btn btn-sm btn-warning me-1">Düzenle</a>
                        <form action="delete.php" method="POST" class="d-inline" onsubmit="return confirm('Bu ürünü silmek istediğinize emin misiniz?');">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($product->product_id) ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php 
    require_once __DIR__ . '/../partials/footer.php';
?>
