<?php ob_start(); ?>

<div class="container my-4">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow">
        <div class="card-header bg-danger text-white">
          <h4 class="mb-0">
            <i class="bi bi-<?= isset($product) ? 'pencil' : 'plus-circle' ?>"></i>
            <?= isset($product) ? 'Sửa sản phẩm' : 'Thêm sản phẩm mới' ?>
          </h4>
        </div>
        <!-- Thông Báo Lỗi và Thành Công -->
         <!-- e($error) và e($success) là các hàm giúp escape dữ liệu, bảo vệ chống XSS -->
        <div class="card-body p-4">
          <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
              <i class="bi bi-exclamation-triangle"></i> <?= e($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>
            <!-- Form Thêm/Sửa Sản Phẩm -->
          <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show">
              <i class="bi bi-check-circle"></i> <?= e($success) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>
            <!-- enctype="multipart/form-data": Cho phép người dùng tải lên các tệp (ảnh sản phẩm). -->
          <form method="POST" enctype="multipart/form-data">
            <!-- csrf_token() được dùng để bảo vệ chống các cuộc tấn công Cross-Site Request Forgery (CSRF). -->
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <!-- Các Trường Nhập Liệu Cho Sản Phẩm -->
            <div class="mb-3">
              <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control"
                     value="<?= e($product['name'] ?? '') ?>" required>
            </div>
            <!-- Danh mục sản phẩm -->
            <div class="mb-3">
              <label class="form-label">Danh mục</label>
              <select name="category_id" class="form-select">
                <option value="">-- Chọn danh mục --</option>
                <?php if (isset($categories) && is_array($categories)): ?>
                  <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"
                            <?= (isset($product['category_id']) && $product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                      <?= e($cat['name']) ?>
                    </option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            <!-- Mô tả sản phẩm -->
            <div class="mb-3">
              <label class="form-label">Mô tả</label>
              <textarea name="description" class="form-control" rows="4"><?= e($product['description'] ?? '') ?></textarea>
            </div>
            <!-- Giá sản phẩm -->
            <div class="mb-3">
              <label class="form-label">Giá (VNĐ) <span class="text-danger">*</span></label>
              <input type="number" name="price" class="form-control"
                     value="<?= e($product['price'] ?? '') ?>" min="0" step="1000" required>
            </div>

            <!-- Ảnh sản phẩm -->
            <div class="mb-3">
              <label class="form-label">Ảnh sản phẩm</label>
              <?php if (isset($product['image']) && $product['image']): ?>
                <div class="mb-2">
                  <img src="/uploads/<?= e($product['image']) ?>"
                       class="img-thumbnail"
                       style="max-width: 200px; border-radius: 5px;">
                </div>
              <?php endif; ?>
              <input type="file" name="image" class="form-control" accept="image/*">
              <small class="text-muted">JPG, PNG, GIF, WEBP (tối đa 5MB)</small>
            </div>
                <!-- Lưu và Hủy -->
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-danger">
                <i class="bi bi-save"></i> Lưu
              </button>
              <a href="/admin/products" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Hủy
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
