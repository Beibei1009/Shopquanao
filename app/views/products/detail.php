<?php ob_start(); ?>

<div class="container my-4">
    <?php if (!empty($product)): ?>
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/"><i class="bi bi-house-door"></i> Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="/products">Sản phẩm</a></li>
                <!-- e($product['name']): Hàm e() bảo vệ output để tránh XSS, đảm bảo tên sản phẩm không chứa mã độc. -->
                <li class="breadcrumb-item active"><?= e($product['name']) ?></li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Ảnh Sản Phẩm-->
            <div class="col-md-5">
                <div class="card border-0 shadow-sm">
                    <div class="product-image-wrapper">
                        <img src="/uploads/<?= e($product['image']) ?>"
                             class="card-img-top"
                             alt="<?= e($product['name']) ?>"
                             onerror="this.src='https://via.placeholder.com/500x500?text=No+Image'">
                    </div>
                </div>
            </div>

            <!-- Thông tin sản phẩm-->
            <div class="col-md-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h2 class="card-title fw-bold text-danger mb-3">
                            <?= e($product['name']) ?>
                        </h2>

                        <div class="mb-4">
                            <h3 class="text-danger fw-bold mb-0">
                                <!-- Sử dụng hàm format_vnd($product['price']) để hiển thị giá dưới định dạng tiền tệ (VNĐ). -->
                                <?= format_vnd($product['price']) ?>
                            </h3>
                            <small class="text-muted">Giá đã bao gồm VAT</small>
                        </div>

                        <div class="mb-4">
                            <h5 class="fw-bold"><i class="bi bi-file-text"></i> Mô tả sản phẩm</h5>
                            <p class="text-muted">
                                <!-- e($product['description']) : Bảo vệ nội dung mô tả để tránh XSS.-->
                                <!-- nl2br() chuyển các ký tự xuống dòng (\n) thành thẻ <br>, giúp hiển thị văn bản đẹp hơn. -->
                                <?= nl2br(e($product['description'])) ?>
                            </p>
                        </div>

                        <hr>

                        <!-- thêm sản phẩm vào giỏ hàng-->
                        <form method="GET" action="/cart/add" class="mt-4">
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">

                            <div class="mb-3">
                                <label for="size" class="form-label fw-bold">
                                    <i class="bi bi-rulers"></i> Chọn size:
                                </label>
                                <select name="size" id="size" class="form-select form-select-lg" required style="font-size: 16px;">
                                    <option value="">-- Chọn size --</option>
                                    <?php
                                    $sizes = ['S' => 'Size S', 'M' => 'Size M', 'L' => 'Size L', 'XL' => 'Size XL', 'XXL' => 'Size XXL'];
                                    foreach ($sizes as $sizeCode => $sizeName):
                                    ?>
                                        <option value="<?= $sizeCode ?>">
                                            <?= $sizeName ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label fw-bold">
                                    <i class="bi bi-plus-minus"></i> Số lượng:
                                </label>
                                <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger btn-lg">
                                    <i class="bi bi-cart-plus-fill"></i> Thêm vào giỏ hàng
                                </button>
                                <a href="/products" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Quay lại danh sách
                                </a>
                            </div>
                        </form>

                        <!-- Các tính năng của sản phẩm -->
                        <div class="mt-4">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="p-2 border rounded">
                                        <i class="bi bi-truck text-success fs-4"></i>
                                        <p class="small mb-0 mt-1">Giao hàng nhanh</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 border rounded">
                                        <i class="bi bi-shield-check text-primary fs-4"></i>
                                        <p class="small mb-0 mt-1">Hàng chính hãng</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 border rounded">
                                        <i class="bi bi-arrow-repeat text-warning fs-4"></i>
                                        <p class="small mb-0 mt-1">Đổi trả 7 ngày</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-warning text-center">
            <i class="bi bi-exclamation-triangle"></i> Không tìm thấy sản phẩm.
            <br>
            <a href="/products" class="btn btn-danger mt-2">
                <i class="bi bi-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
.product-image-wrapper {
    position: relative;
    overflow: hidden;
    background: #f8f9fa;
    border-radius: 8px;
}

.product-image-wrapper img {
    width: 100%;
    height: auto;
    max-height: 800px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-image-wrapper:hover img {
    transform: scale(1.05);
}

.breadcrumb-item a {
    text-decoration: none;
    color: #6c757d;
}

.breadcrumb-item a:hover {
    color: #dc3545;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .product-image-wrapper img {
        max-height: 350px;
    }

    /* Fix dropdown size on mobile */
    #size {
        font-size: 16px !important;
        padding: 12px 16px;
        height: auto;
    }

    #size option {
        font-size: 16px;
        padding: 12px;
    }
}

/* Ensure select doesn't zoom on iOS */
select#size {
    font-size: 16px;
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>