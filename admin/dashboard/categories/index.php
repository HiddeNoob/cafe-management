<?php
require_once __DIR__ . '/../../../app/autoload.php';
AuthController::check_employee_access();
require_once __DIR__ . '/../../../app/Functions/log_errors.php';
$pdo = DatabaseController::getPDO();
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;

if ($category_id) {
    $categories = CategoryRepository::getInstance()->findBy(['category_id' => $category_id]);
    // Alt kategorileri CATEGORY_TREE üzerinden çek
    $subCategoryIds = CategoryTreeRepository::getInstance()->getSubCategoryIds($category_id);
    $subCategories = [];
    if ($subCategoryIds) {
        $subCategories = CategoryRepository::getInstance()->findBy(['category_id' => $subCategoryIds]);
    }
} else {
    $categories = CategoryRepository::getInstance()->getAll();
    $subCategories = [];
}

function renderCategoryTree($parentId = null, $level = 0) {
    $treeRepo = CategoryTreeRepository::getInstance();
    $catRepo = CategoryRepository::getInstance();
    $subCategoryIds = $treeRepo->getSubCategoryIds($parentId);
    if (!$subCategoryIds) return;
    $subCategories = $catRepo->findBy(['category_id' => $subCategoryIds]);
    echo '<ul class="ps-' . (2 + $level) . '">';
    foreach ($subCategories as $cat) {
        echo '<li>';
        echo '<a href="?category_id=' . htmlspecialchars($cat->category_id) . '" class="text-decoration-none">' . htmlspecialchars($cat->category_name) . '</a>';
        // Recursive call for children
        renderCategoryTree($cat->category_id, $level + 1);
        echo '</li>';
    }
    echo '</ul>';
}

?>


<?php
require_once __DIR__ . '/../partials/header.php';
NotificationHandler::display();
?>
<div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Kategori Ağacı</h2>
        <a href="add.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Yeni Ana Kategori Ekle
        </a>
    </div>
    <div class="card card-body mb-4 bg-light">
        <?php renderCategoryAccordion($pdo); ?>
    </div>
</div>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>

<?php
function getRootCategoryIds(PDO $pdo): array {
    $stmt = $pdo->query("
        SELECT DISTINCT c.category_id 
        FROM CATEGORY c
        LEFT JOIN CATEGORY_TREE ct ON c.category_id = ct.sub_category_id
        WHERE ct.sub_category_id IS NULL
        ORDER BY c.category_id
    ");
    $rootCandidates = $stmt->fetchAll(PDO::FETCH_COLUMN);
    if (empty($rootCandidates)) {
        return [];
    }
    return $rootCandidates;
}

function renderCategoryAccordionTree($parentId, $level = 0) {
    $treeRepo = CategoryTreeRepository::getInstance();
    $catRepo = CategoryRepository::getInstance();
    $subCategoryIds = $treeRepo->getSubCategoryIds($parentId);
    if (!$subCategoryIds) return;
    $subCategories = $catRepo->findBy(['category_id' => $subCategoryIds]);
    
    echo '<div class="list-group list-group-flush">';
    foreach ($subCategories as $cat) {
        $hasSubCategories = $treeRepo->getSubCategoryIds($cat->category_id);
        
        echo '<div class="border-0 p-2 pe-0" style="padding-left: ' . ($level * 32) . 'px !important;">';
        echo '<div class="d-flex justify-content-between align-items-center">';
        echo '<div class="d-flex align-items-center">';
        echo '<i class="bi bi-arrow-return-right me-2 text-muted"></i>';
        echo '<span class="text-dark">#' . htmlspecialchars($cat->category_id) . ' ' . htmlspecialchars($cat->category_name) . '</span>';
        echo '</div>';
        
        echo '<div class="btn-group">';
        echo '<a href="add.php?parent_id=' . htmlspecialchars($cat->category_id) . '" class="btn btn-sm btn-success me-2"><i class="bi bi-plus-circle"></i> Alt Kategori Ekle</a>';
        
        if (!$hasSubCategories) {
            echo '<form action="delete.php" method="POST" class="d-inline" onsubmit="return confirm(\'Bu kategoriyi silmek istediğinize emin misiniz?\')">';
            echo '<input type="hidden" name="id" value="' . htmlspecialchars($cat->category_id) . '">';
            echo '<button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Sil</button>';
            echo '</form>';
        } else {
            echo '<button class="btn btn-sm btn-danger disabled" title="Alt kategorisi olan kategori silinemez"><i class="bi bi-trash"></i> Sil</button>';
        }
        echo '</div>';
        echo '</div>';
        
        if ($hasSubCategories) {
            renderCategoryAccordionTree($cat->category_id, $level + 1);
        }
        echo '</div>';
    }
    echo '</div>';
}

function renderCategoryAccordion(PDO $pdo) {
    $treeRepo = CategoryTreeRepository::getInstance();
    $rootCandidates = getRootCategoryIds($pdo);
    
    if (empty($rootCandidates)) {
        echo '<div class="list-group-item text-muted">Hiç kategori bulunamadı.</div>';
        return;
    }

    echo '<div class="accordion" id="categoryAccordion">';
    foreach ($rootCandidates as $index => $rootId) {
        $cat = CategoryRepository::getInstance()->findBy(['category_id' => $rootId]);
        $cat = $cat ? $cat[0] : null;
        if (!$cat) continue;

        $subIds = $treeRepo->getSubCategoryIds($rootId);
        
        echo '<div class="accordion-item mb-2">';
            echo '<h2 class="accordion-header d-flex" id="heading' . $index . '">';
            echo '<button class="accordion-button flex-grow-1 collapsed" type="button" data-bs-toggle="collapse" 
                data-bs-target="#collapse' . $index . '" aria-expanded="false" aria-controls="collapse' . $index . '">';
            echo '<div class="d-flex justify-content-between align-items-center w-100">';
                    
                    echo '<span class="fw-bold">#' . htmlspecialchars($cat->category_id) . ' ' . htmlspecialchars($cat->category_name) . '</span>';
                    echo '<div class="btn-group pe-3">';
                        echo '<a href="add.php?parent_id=' . htmlspecialchars($cat->category_id) . '" class="btn btn-sm btn-success me-2 d-inline"><i class="bi bi-plus-circle">Alt Kategori Ekle</i></a>';
                        echo '<a class="btn btn-sm btn-danger ' . ($subIds ? 'disabled"' : '"') . " onclick=handleRemove($cat->category_id) >"  . '<i class="bi bi-trash"></i> Sil</a>';
                    echo '</div>';
        

            echo '</div>';
            echo '</button>';
            

            echo '</h2>';

            echo '<div id="collapse' . $index . '" class="accordion-collapse collapse p-2" aria-labelledby="heading' . $index . '" data-bs-parent="#categoryAccordion">';
                if ($subIds) {
                    echo '<div class="accordion-body p-0">';
                    renderCategoryAccordionTree($rootId, 1);
                    echo '</div>';
                }else{
                    echo '<div class="accordion-body p-0">';
                    echo '</div>';
                }
            echo '</div>';
        echo '</div>';
    }
    echo '</div>';
}
?>

<script>
    function handleRemove(categoryId) {
        if (confirm('Bu kategoriyi silmek istediğinize emin misiniz?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'delete.php';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'id';
            input.value = categoryId;
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>