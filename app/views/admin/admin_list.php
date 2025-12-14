<?php ob_start(); ?>

<div class="container my-4">
  <!-- Tiêu Đề và Các Nút Điều Hướng -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-danger">
      <i class="bi bi-box-seam"></i> Quản lý sản phẩm
    </h2>
    <div>
      <a href="/admin/orders" class="btn btn-outline-primary me-2">
        <i class="bi bi-cart-check"></i> Đơn hàng
      </a>
      <a href="/admin/products/create" class="btn btn-danger">
        <i class="bi bi-plus-circle"></i> Thêm sản phẩm
      </a>
    </div>
  </div>
  <!-- Kiểm Tra Nếu Không Có Sản Phẩm -->
  <?php if (empty($products)): ?>
    <div class="alert alert-warning text-center">
      <i class="bi bi-exclamation-triangle"></i> Chưa có sản phẩm nào.
    </div>
  <?php else: ?>
    <!-- Bảng Danh Sách Sản Phẩm -->
    <div class="card shadow-sm">
      <div class="card-body">
        <!-- Table Responsive giúp bảng có thể cuộn ngang -->
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-danger">
              <tr>
                <th style="width: 60px;">ID</th>
                <th style="width: 100px;">Ảnh</th>
                <th>Tên sản phẩm</th>
                <th style="width: 150px;">Danh mục</th>
                <th class="text-end" style="width: 150px;">Giá</th>
                <th class="text-center" style="width: 120px;">Ngày tạo</th>
                <th class="text-center" style="width: 180px;">Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <!-- Hiển Thị Các Sản Phẩm -->
               <!-- foreach ($products as $p): Lặp qua tất cả các sản phẩm trong mảng -->
              <?php foreach ($products as $p): ?>
              <tr>
                <td class="fw-bold"><?= e($p['id']) ?></td>
                <td>
                  <!-- //tạo ảnh thumbnail -->
                  <img src="/uploads/<?= e($p['image']) ?>"
                       class="img-thumbnail"
                       style="width: 70px; height: 70px; object-fit: cover;"
                       alt="<?= e($p['name']) ?>">
                </td>
                <td>
                  <strong><?= e($p['name']) ?></strong>
                  <?php if (!empty($p['description'])): ?>
                    <br>
                    <small class="text-muted">
                      <?= e(mb_substr($p['description'], 0, 50)) ?><?= mb_strlen($p['description']) > 50 ? '...' : '' ?>
                    </small>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if (!empty($p['category_name'])): ?>
                    <span class="badge bg-secondary"><?= e($p['category_name']) ?></span>
                  <?php else: ?>
                    <span class="text-muted small">Chưa phân loại</span>
                  <?php endif; ?>
                </td>
                <td class="text-end text-danger fw-bold">
                  <?= format_vnd($p['price']) ?>
                </td>
                <td class="text-center text-muted">
                  <small><?= date('d/m/Y', strtotime($p['created_at'])) ?></small>
                </td>
                <td class="text-center">
                  <div class="btn-group btn-group-sm" role="group">
                    <a href="/products?action=detail&id=<?= $p['id'] ?>"
                       class="btn btn-outline-info"
                       title="Xem"
                       target="_blank">
                      <i class="bi bi-eye"></i>
                    </a>
                    <a href="/admin/products/edit?id=<?= $p['id'] ?>"
                       class="btn btn-outline-primary"
                       title="Sửa">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <a href="/admin/products/delete?id=<?= $p['id'] ?>"
                       class="btn btn-outline-danger"
                       title="Xóa"
                       onclick="return confirm('Xác nhận xóa sản phẩm &quot;<?= e($p['name']) ?>&quot;?')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Thống Kê Sản Phẩm -->
        <div class="row mt-4">
          <div class="col-md-4">
            <div class="card text-center border-primary">
              <div class="card-body">
                <h6 class="text-muted">Tổng sản phẩm</h6>
                <h3 class="text-primary"><?= count($products) ?></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card text-center border-success">
              <div class="card-body">
                <h6 class="text-muted">Giá trung bình</h6>
                <h3 class="text-success">
                  <?php
                  $avgPrice = count($products) > 0 ? array_sum(array_column($products, 'price')) / count($products) : 0;
                  echo format_vnd($avgPrice);
                  ?>
                </h3>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card text-center border-danger">
              <div class="card-body">
                <h6 class="text-muted">Giá cao nhất</h6>
                <h3 class="text-danger">
                  <?php
                  $maxPrice = count($products) > 0 ? max(array_column($products, 'price')) : 0;
                  echo format_vnd($maxPrice);
                  ?>
                </h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
