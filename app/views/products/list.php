<?php ob_start(); ?>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-danger">
            <i class="bi bi-bag-fill"></i> Danh sách sản phẩm
        </h2>
        <span class="text-muted">
            <i class="bi bi-box-seam"></i> <?= count($products) ?> sản phẩm
        </span>
    </div>

    <!-- Bộ lọc và tìm kiếm -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="/products" id="filterForm">
                <div class="row g-3">
                    <!-- Tìm kiếm theo tên -->
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">
                            <i class="bi bi-search"></i> Tìm kiếm
                        </label>
                        <!-- //Nếu có giá trị tìm kiếm ($_GET['search']), sẽ hiển thị lại trong ô input -->
                        <input type="text" name="search" class="form-control"
                               placeholder="Nhập tên sản phẩm..."
                               value="<?= e($_GET['search'] ?? '') ?>">
                    </div>

                    <!-- Lọc theo danh mục -->
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">
                            <i class="bi bi-grid"></i> Danh mục
                        </label>
                        <!-- //Dropdown select cho phép chọn danh mục sản phẩm. -->
                        <select name="category" class="form-select">
                            <option value="">Tất cả</option>
                            <?php if (isset($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    //selected attribute để giữ lựa chọn khi submit form
                                    <option value="<?= $cat['id'] ?>"
                                            <?= (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : '' ?>>
                                        <?= e($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Khoảng giá -->

                    <!-- Hai ô nhập liệu cho phép người dùng nhập giá tối thiểu và
                     giá tối đa (min_price, max_price). -->
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">
                            <i class="bi bi-cash"></i> Giá từ
                        </label>
                        <input type="number" name="min_price" class="form-control"
                               placeholder="0"
                               value="<?= e($_GET['min_price'] ?? '') ?>"
                               step="10000">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small fw-bold">Giá đến</label>
                        <input type="number" name="max_price" class="form-control"
                               placeholder="10,000,000"
                               value="<?= e($_GET['max_price'] ?? '') ?>"
                               step="10000">
                    </div>

                    <!-- Sắp xếp -->
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">
                            <i class="bi bi-sort-down"></i> Sắp xếp
                        </label>
                        <!-- //Dropdown để sắp xếp sản phẩm: -->
                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="newest" <?= (($_GET['sort'] ?? 'newest') == 'newest') ? 'selected' : '' ?>>
                                Mới nhất
                            </option>
                            <option value="oldest" <?= (($_GET['sort'] ?? '') == 'oldest') ? 'selected' : '' ?>>
                                Cũ nhất
                            </option>
                            <option value="price_asc" <?= (($_GET['sort'] ?? '') == 'price_asc') ? 'selected' : '' ?>>
                                Giá thấp → cao
                            </option>
                            <option value="price_desc" <?= (($_GET['sort'] ?? '') == 'price_desc') ? 'selected' : '' ?>>
                                Giá cao → thấp
                            </option>
                        </select>
                    </div>

                    <!-- Nút tìm kiếm -->
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-funnel"></i>
                        </button>
                    </div>
                </div>

                <!-- Nút xóa bộ lọc -->
                 <!-- Hiển thị nút xóa bộ lọc khi có ít nhất một bộ lọc đang áp dụng
                  reset form và đưa người dùng về trang /products -->
                  
                <?php if (!empty($_GET['search']) || !empty($_GET['category']) ||
                          !empty($_GET['min_price']) || !empty($_GET['max_price'])): ?>
                <div class="mt-3">
                    <a href="/products" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-circle"></i> Xóa bộ lọc
                    </a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <?php if (empty($products)): ?>
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> Không tìm thấy sản phẩm nào phù hợp.
        </div>
    <?php else: ?>
        <!-- Bootstrap Grid Product Cards -->
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($products as $p): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm product-card">
                        <a href="/products?action=detail&id=<?= $p['id'] ?>" class="text-decoration-none">
                            <div class="card-img-wrapper position-relative">
                                <img src="/uploads/<?= e($p['image']) ?>"
                                     class="card-img-top"
                                     alt="<?= e($p['name']) ?>"
                                     onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">
                            </div>
                            <div class="card-body text-center">
                                <h6 class="card-title text-dark mb-2"><?= e($p['name']) ?></h6>
                                <p class="card-text fw-bold text-danger mb-2">
                                    <?= format_vnd($p['price']) ?>
                                </p>
                            </div>
                        </a>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <a href="/products?action=detail&id=<?= $p['id'] ?>" class="btn btn-danger btn-sm w-100">
                                <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
}

.card-img-wrapper {
    /* Ảnh sẽ được cắt theo kích thước chuẩn (cover) và giới hạn chiều cao. */
    overflow: hidden;
    height: 400px;
    background: #f8f9fa;
}

.card-img-top {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .card-img-top {
    transform: scale(1.05);
}

.card-title {
    font-size: 0.95rem;
    font-weight: 600;
    
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.card-text {
    font-size: 1.1rem;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .card-img-wrapper {
        height: 200px;
    }

    .card-title {
        font-size: 0.85rem;
        min-height: 35px;
    }
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>