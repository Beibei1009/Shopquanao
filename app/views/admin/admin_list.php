<?php ob_start(); ?>

<div class="container my-4">
  <!-- Tiêu Đề và Các Nút Điều Hướng -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mb-md-4 gap-2">
    <h2 class="text-danger mb-0 fs-5 fs-md-4 fs-lg-3">
      <i class="bi bi-box-seam"></i> Quản lý sản phẩm
    </h2>
    <div class="d-flex gap-2 flex-wrap">
      <a href="/admin/orders" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-cart-check"></i> <span class="d-none d-sm-inline">Đơn hàng</span>
      </a>
      <a href="/admin/products/create" class="btn btn-danger btn-sm">
        <i class="bi bi-plus-circle"></i> <span class="d-none d-sm-inline">Thêm</span>
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
      <div class="card-body p-0 p-md-3">
        <!-- Table Responsive giúp bảng có thể cuộn ngang -->
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-danger">
              <tr>
                <th class="ps-2 ps-md-3" style="min-width: 50px;"><small>ID</small></th>
                <th class="d-none d-md-table-cell" style="min-width: 85px;"><small>Ảnh</small></th>
                <th style="min-width: 150px;"><small>Tên sản phẩm</small></th>
                <th class="d-none d-lg-table-cell" style="min-width: 110px;"><small>Danh mục</small></th>
                <th class="text-end" style="min-width: 100px;"><small>Giá</small></th>
                <th class="text-center d-none d-xl-table-cell" style="min-width: 100px;"><small>Ngày tạo</small></th>
                <th class="text-center pe-2 pe-md-3" style="min-width: 100px;"><small>Tác vụ</small></th>
              </tr>
            </thead>
            <tbody>
              <!-- Hiển Thị Các Sản Phẩm -->
               <!-- foreach ($products as $p): Lặp qua tất cả các sản phẩm trong mảng -->
              <?php foreach ($products as $p): ?>
              <tr>
                <td class="fw-bold ps-2 ps-md-3"><small><?= e($p['id']) ?></small></td>
                <td class="d-none d-md-table-cell">
                  <!-- //tạo ảnh thumbnail -->
                  <img src="/uploads/<?= e($p['image']) ?>"
                       class="img-thumbnail"
                       style="width: 60px; height: 60px; object-fit: cover;"
                       alt="<?= e($p['name']) ?>">
                </td>
                <td>
                  <div class="d-flex align-items-start gap-2">
                    <!-- Ảnh trên mobile -->
                    <img src="/uploads/<?= e($p['image']) ?>"
                         class="img-thumbnail d-md-none"
                         style="width: 50px; height: 50px; object-fit: cover; flex-shrink: 0;"
                         alt="<?= e($p['name']) ?>">
                    <div class="d-flex flex-column" style="min-width: 0;">
                      <strong class="small" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.3;"><?= e($p['name']) ?></strong>
                      <?php if (!empty($p['description'])): ?>
                        <small class="text-muted d-none d-lg-block" style="font-size: 0.75rem;">
                          <?= e(mb_substr($p['description'], 0, 40)) ?><?= mb_strlen($p['description']) > 40 ? '...' : '' ?>
                        </small>
                      <?php endif; ?>
                      <!-- Hiển thị danh mục trên mobile -->
                      <div class="d-lg-none mt-1">
                        <?php if (!empty($p['category_name'])): ?>
                          <span class="badge bg-secondary" style="font-size: 0.7rem;"><?= e($p['category_name']) ?></span>
                        <?php endif; ?>
                      </div>
                      <!-- Hiển thị ngày tạo trên mobile -->
                      <div class="d-xl-none mt-1">
                        <small class="text-muted" style="font-size: 0.7rem;"><?= date('d/m/Y', strtotime($p['created_at'])) ?></small>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="d-none d-lg-table-cell">
                  <?php if (!empty($p['category_name'])): ?>
                    <span class="badge bg-secondary small"><?= e($p['category_name']) ?></span>
                  <?php else: ?>
                    <span class="text-muted" style="font-size: 0.75rem;">Chưa phân loại</span>
                  <?php endif; ?>
                </td>
                <td class="text-end text-danger fw-bold">
                  <small><?= format_vnd($p['price']) ?></small>
                </td>
                <td class="text-center text-muted d-none d-xl-table-cell">
                  <small><?= date('d/m/Y', strtotime($p['created_at'])) ?></small>
                </td>
                <td class="text-center pe-2 pe-md-3">
                  <div class="d-flex flex-column flex-md-row gap-1 justify-content-center">
                    <a href="/products?action=detail&id=<?= $p['id'] ?>"
                       class="btn btn-outline-info btn-sm"
                       title="Xem"
                       target="_blank"
                       style="font-size: 0.7rem; padding: 0.2rem 0.35rem;">
                      <i class="bi bi-eye" style="font-size: 0.85rem;"></i>
                    </a>
                    <a href="/admin/products/edit?id=<?= $p['id'] ?>"
                       class="btn btn-outline-primary btn-sm"
                       title="Sửa"
                       style="font-size: 0.7rem; padding: 0.2rem 0.35rem;">
                      <i class="bi bi-pencil" style="font-size: 0.85rem;"></i>
                    </a>
                    <a href="/admin/products/delete?id=<?= $p['id'] ?>"
                       class="btn btn-outline-danger btn-sm"
                       title="Xóa"
                       onclick="return confirm('Xác nhận xóa sản phẩm &quot;<?= e($p['name']) ?>&quot;?')"
                       style="font-size: 0.7rem; padding: 0.2rem 0.35rem;">
                      <i class="bi bi-trash" style="font-size: 0.85rem;"></i>
                    </a>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Thống Kê Sản Phẩm -->
        <div class="row mt-3 mt-md-4 px-2 px-md-0">
          <div class="col-sm-4 mb-3">
            <div class="card text-center border-primary h-100">
              <div class="card-body p-2 p-md-3">
                <h6 class="text-muted small mb-1">Tổng sản phẩm</h6>
                <h3 class="text-primary mb-0"><?= count($products) ?></h3>
              </div>
            </div>
          </div>
          <div class="col-sm-4 mb-3">
            <div class="card text-center border-success h-100">
              <div class="card-body p-2 p-md-3">
                <h6 class="text-muted small mb-1">Giá TB</h6>
                <h3 class="text-success mb-0 fs-5 fs-md-3">
                  <?php
                  $avgPrice = count($products) > 0 ? array_sum(array_column($products, 'price')) / count($products) : 0;
                  echo format_vnd($avgPrice);
                  ?>
                </h3>
              </div>
            </div>
          </div>
          <div class="col-sm-4 mb-3">
            <div class="card text-center border-danger h-100">
              <div class="card-body p-2 p-md-3">
                <h6 class="text-muted small mb-1">Giá cao nhất</h6>
                <h3 class="text-danger mb-0 fs-5 fs-md-3">
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
