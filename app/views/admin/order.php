<?php ob_start(); ?>

<div class="container my-4">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mb-md-4 gap-2">
    <h2 class="text-danger mb-0 fs-5 fs-md-4 fs-lg-3"><i class="bi bi-cart-check"></i> Quản lý đơn hàng</h2>
    <a href="/admin/products" class="btn btn-outline-danger btn-sm">
      <i class="bi bi-box"></i> <span class="d-none d-sm-inline">Quản lý sản phẩm</span><span class="d-inline d-sm-none">Sản phẩm</span>
    </a>
  </div>

  <?php if (empty($orders)): ?>
    <div class="alert alert-info text-center">
      <i class="bi bi-info-circle"></i> Chưa có đơn hàng nào.
    </div>
  <?php else: ?>
    <?php
    // Define status arrays for use in both table and modals
    $statusClass = [
      'pending' => 'warning',
      'confirmed' => 'info',
      'processing' => 'primary',
      'shipping' => 'dark',
      'completed' => 'success',
      'cancelled' => 'danger'
    ];
    $statusText = [
      'pending' => 'Chờ xử lý',
      'confirmed' => 'Đã xác nhận',
      'processing' => 'Đang chuẩn bị',
      'shipping' => 'Đang giao hàng',
      'completed' => 'Hoàn thành',
      'cancelled' => 'Đã hủy'
    ];
    ?>
    <div class="card shadow">
      <div class="card-body p-0 p-md-3">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-danger">
              <tr>
                <th class="ps-2 ps-md-3" style="min-width: 85px;"><small>Mã ĐH</small></th>
                <th style="min-width: 120px;"><small>Khách hàng</small></th>
                <th class="d-none d-lg-table-cell" style="min-width: 150px;"><small>Email/SĐT</small></th>
                <th class="d-none d-sm-table-cell" style="min-width: 105px;"><small>Tổng tiền</small></th>
                <th style="min-width: 105px;"><small>Trạng thái</small></th>
                <th class="d-none d-md-table-cell" style="min-width: 130px;"><small>Ngày đặt</small></th>
                <th class="pe-2 pe-md-3 text-center" style="min-width: 75px;"><small>Tác vụ</small></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($orders as $order): ?>
              <tr>
                <td class="fw-bold ps-2 ps-md-3 small"><?= e($order['order_code'] ?? '#' . str_pad($order['id'], 6, '0', STR_PAD_LEFT)) ?></td>
                <td>
                  <div class="d-flex align-items-start flex-column">
                    <span class="fw-medium small">
                      <?php if ($order['user_name']): ?>
                        <i class="bi bi-person-circle"></i> <?= e($order['user_name']) ?>
                      <?php else: ?>
                        <?= e($order['customer_name'] ?? 'N/A') ?>
                      <?php endif; ?>
                    </span>
                    <!-- Hiển thị Email/SĐT trên mobile -->
                    <div class="d-lg-none mt-1">
                      <small class="text-muted d-block" style="font-size: 0.75rem; line-height: 1.3;">
                        <?php if ($order['user_email']): ?>
                          <?= e($order['user_email']) ?>
                        <?php endif; ?>
                        <?php if (!empty($order['customer_phone'])): ?>
                          <br><?= e($order['customer_phone']) ?>
                        <?php endif; ?>
                      </small>
                    </div>
                    <!-- Hiển thị tổng tiền trên mobile cực nhỏ -->
                    <div class="d-sm-none mt-1">
                      <small class="text-danger fw-bold" style="font-size: 0.8rem;"><?= format_vnd($order['total_amount']) ?></small>
                    </div>
                  </div>
                </td>
                <td class="d-none d-lg-table-cell">
                  <small>
                    <?php if ($order['user_email']): ?>
                      <?= e($order['user_email']) ?><br>
                    <?php endif; ?>
                    <?php if (!empty($order['customer_phone'])): ?>
                      <?= e($order['customer_phone']) ?>
                    <?php endif; ?>
                  </small>
                </td>
                <td class="fw-bold text-danger d-none d-sm-table-cell"><?= format_vnd($order['total_amount']) ?></td>
                <td>
                  <?php
                  $class = $statusClass[$order['status']] ?? 'secondary';
                  $text = $statusText[$order['status']] ?? ucfirst($order['status']);
                  ?>
                  <span class="badge bg-<?= $class ?> small"><?= $text ?></span>
                  <!-- Hiển thị ngày trên mobile -->
                  <div class="d-md-none mt-1">
                    <small class="text-muted d-block" style="font-size: 0.7rem;"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></small>
                  </div>
                </td>
                <td class="d-none d-md-table-cell"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                <td class="pe-2 pe-md-3 text-center">
                  <div class="d-flex flex-column flex-xl-row gap-1 justify-content-center">
                    <a href="/admin/orders/detail?id=<?= $order['id'] ?>"
                       class="btn btn-outline-primary btn-sm" title="Xem chi tiết"
                       style="font-size: 0.7rem; padding: 0.2rem 0.35rem;">
                      <i class="bi bi-eye" style="font-size: 0.85rem;"></i><span class="d-none d-xl-inline ms-1">Xem</span>
                    </a>

                    <!-- Button mở modal cập nhật trạng thái - Ẩn nếu đã hoàn thành/hủy -->
                    <?php if (!in_array($order['status'], ['completed', 'cancelled'])): ?>
                      <button type="button" class="btn btn-outline-secondary btn-sm"
                              data-bs-toggle="modal"
                              data-bs-target="#statusModal<?= $order['id'] ?>"
                              title="Cập nhật trạng thái"
                              style="font-size: 0.7rem; padding: 0.2rem 0.35rem;">
                        <i class="bi bi-pencil" style="font-size: 0.85rem;"></i><span class="d-none d-xl-inline ms-1">Sửa</span>
                      </button>
                    <?php else: ?>
                      <button type="button" class="btn btn-outline-secondary btn-sm" disabled title="Đơn hàng đã hoàn thành/hủy"
                              style="font-size: 0.7rem; padding: 0.2rem 0.35rem;">
                        <i class="bi bi-lock" style="font-size: 0.85rem;"></i>
                      </button>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modals cập nhật trạng thái -->
    <?php foreach ($orders as $order): ?>
    <div class="modal fade" id="statusModal<?= $order['id'] ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">
              <i class="bi bi-pencil-square"></i> Cập nhật trạng thái đơn hàng
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p class="mb-3">
              <strong>Mã đơn hàng:</strong> <?= e($order['order_code'] ?? '#' . str_pad($order['id'], 6, '0', STR_PAD_LEFT)) ?><br>
              <strong>Khách hàng:</strong> <?= e($order['customer_name'] ?? $order['user_name'] ?? 'N/A') ?><br>
              <strong>Trạng thái hiện tại:</strong>
              <span class="badge bg-<?= $statusClass[$order['status']] ?? 'secondary' ?>">
                <?= $statusText[$order['status']] ?? ucfirst($order['status']) ?>
              </span>
            </p>

            <form method="POST" action="/admin/orders/update-status">
              <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
              <input type="hidden" name="order_id" value="<?= $order['id'] ?>">

              <div class="mb-3">
                <label class="form-label fw-bold">Chọn trạng thái mới:</label>
                <div class="d-grid gap-2">
                  <button type="submit" name="status" value="pending" class="btn btn-outline-warning text-start">
                    <i class="bi bi-clock"></i> Chờ xử lý
                  </button>
                  <button type="submit" name="status" value="confirmed" class="btn btn-outline-info text-start">
                    <i class="bi bi-check-circle"></i> Đã xác nhận
                  </button>
                  <button type="submit" name="status" value="processing" class="btn btn-outline-primary text-start">
                    <i class="bi bi-hourglass-split"></i> Đang chuẩn bị
                  </button>
                  <button type="submit" name="status" value="shipping" class="btn btn-outline-dark text-start">
                    <i class="bi bi-truck"></i> Đang giao hàng
                  </button>
                  <button type="submit" name="status" value="completed" class="btn btn-outline-success text-start">
                    <i class="bi bi-check-all"></i> Hoàn thành
                  </button>
                  <hr>
                  <button type="submit" name="status" value="cancelled" class="btn btn-outline-danger text-start">
                    <i class="bi bi-x-circle"></i> Hủy đơn
                  </button>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

    <!-- Statistics -->
    <div class="row mt-4">
      <div class="col-6 col-lg-3 mb-3">
        <div class="card text-center border-warning h-100">
          <div class="card-body p-2 p-md-3">
            <h6 class="text-muted small mb-1">Chờ xử lý</h6>
            <h3 class="text-warning mb-0">
              <?= count(array_filter($orders, fn($o) => in_array($o['status'], ['pending']))) ?>
            </h3>
          </div>
        </div>
      </div>
      <div class="col-6 col-lg-3 mb-3">
        <div class="card text-center border-primary h-100">
          <div class="card-body p-2 p-md-3">
            <h6 class="text-muted small mb-1">Đang xử lý</h6>
            <h3 class="text-primary mb-0">
              <?= count(array_filter($orders, fn($o) => in_array($o['status'], ['confirmed', 'processing', 'shipping']))) ?>
            </h3>
          </div>
        </div>
      </div>
      <div class="col-6 col-lg-3 mb-3">
        <div class="card text-center border-success h-100">
          <div class="card-body p-2 p-md-3">
            <h6 class="text-muted small mb-1">Hoàn thành</h6>
            <h3 class="text-success mb-0">
              <?= count(array_filter($orders, fn($o) => $o['status'] === 'completed')) ?>
            </h3>
          </div>
        </div>
      </div>
      <div class="col-6 col-lg-3 mb-3">
        <div class="card text-center border-danger h-100">
          <div class="card-body p-2 p-md-3">
            <h6 class="text-muted small mb-1">Doanh thu</h6>
            <h3 class="text-danger mb-0 fs-5 fs-lg-3">
              <?= format_vnd(array_sum(array_column($orders, 'total_amount'))) ?>
            </h3>
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
