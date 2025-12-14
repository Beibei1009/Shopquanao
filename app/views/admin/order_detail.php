<?php ob_start(); ?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-danger">
      <i class="bi bi-receipt"></i> Chi tiết đơn hàng #<?= e($order['order_code'] ?? $order['id']) ?>
    </h2>
    <a href="/admin/orders" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left"></i> Quay lại danh sách
    </a>
  </div>

  <div class="row">
    <!-- Thông tin đơn hàng -->
    <div class="col-md-8">
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-danger text-white">
          <h5 class="mb-0"><i class="bi bi-box-seam"></i> Sản phẩm trong đơn hàng</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th style="width: 80px;">Ảnh</th>
                  <th>Sản phẩm</th>
                  <th class="text-center">Size</th>
                  <th class="text-center">Màu</th>
                  <th class="text-end">Đơn giá</th>
                  <th class="text-center">SL</th>
                  <th class="text-end">Thành tiền</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($orderItems as $item): ?>
                <tr>
                  <td>
                    <?php if (!empty($item['product_image'])): ?>
                      <img src="/uploads/<?= e($item['product_image']) ?>"
                           class="img-thumbnail"
                           style="width: 60px; height: 60px; object-fit: cover;"
                           alt="<?= e($item['product_name']) ?>">
                    <?php else: ?>
                      <div class="bg-light d-flex align-items-center justify-content-center"
                           style="width: 60px; height: 60px;">
                        <i class="bi bi-image text-muted"></i>
                      </div>
                    <?php endif; ?>
                  </td>
                  <td>
                    <strong><?= e($item['product_name']) ?></strong>
                    <?php if (!empty($item['product_sku'])): ?>
                      <br><small class="text-muted">SKU: <?= e($item['product_sku']) ?></small>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <?= !empty($item['size']) ? e($item['size']) : '-' ?>
                  </td>
                  <td class="text-center">
                    <?= !empty($item['color']) ? e($item['color']) : '-' ?>
                  </td>
                  <td class="text-end"><?= format_vnd($item['product_price']) ?></td>
                  <td class="text-center"><strong><?= e($item['quantity']) ?></strong></td>
                  <td class="text-end text-danger fw-bold"><?= format_vnd($item['subtotal']) ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot class="table-light">
                <tr>
                  <td colspan="6" class="text-end"><strong>Tạm tính:</strong></td>
                  <td class="text-end"><?= format_vnd($order['subtotal']) ?></td>
                </tr>
                <tr>
                  <td colspan="6" class="text-end"><strong>Phí vận chuyển:</strong></td>
                  <td class="text-end"><?= format_vnd($order['shipping_fee']) ?></td>
                </tr>
                <?php if (!empty($order['discount_amount']) && $order['discount_amount'] > 0): ?>
                <tr>
                  <td colspan="6" class="text-end"><strong>Giảm giá:</strong></td>
                  <td class="text-end text-success">-<?= format_vnd($order['discount_amount']) ?></td>
                </tr>
                <?php endif; ?>
                <tr class="table-danger">
                  <td colspan="6" class="text-end"><strong>Tổng cộng:</strong></td>
                  <td class="text-end fw-bold fs-5"><?= format_vnd($order['total_amount']) ?></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Thông tin khách hàng và trạng thái -->
    <div class="col-md-4">
      <!-- Trạng thái đơn hàng -->
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-danger text-white">
          <h6 class="mb-0"><i class="bi bi-info-circle"></i> Trạng thái</h6>
        </div>
        <div class="card-body">
          <?php
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
          $class = $statusClass[$order['status']] ?? 'secondary';
          $text = $statusText[$order['status']] ?? ucfirst($order['status']);
          ?>
          <p class="mb-2">
            <strong>Trạng thái:</strong><br>
            <span class="badge bg-<?= $class ?> fs-6"><?= $text ?></span>
          </p>
          <p class="mb-2">
            <strong>Thanh toán:</strong><br>
            <?= $order['payment_method'] === 'cod' ? 'COD (Tiền mặt)' : e($order['payment_method']) ?>
          </p>
          <p class="mb-0">
            <strong>Ngày đặt:</strong><br>
            <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
          </p>
          <?php if (!empty($order['confirmed_at'])): ?>
          <p class="mb-0 mt-2">
            <strong>Ngày xác nhận:</strong><br>
            <?= date('d/m/Y H:i', strtotime($order['confirmed_at'])) ?>
          </p>
          <?php endif; ?>

          <?php if (!in_array($order['status'], ['completed', 'cancelled'])): ?>
          <hr>
          <button type="button" class="btn btn-sm btn-outline-primary w-100"
                  data-bs-toggle="modal"
                  data-bs-target="#statusModal">
            <i class="bi bi-pencil"></i> Cập nhật trạng thái
          </button>
          <?php endif; ?>
        </div>
      </div>

      <!-- Thông tin khách hàng -->
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-danger text-white">
          <h6 class="mb-0"><i class="bi bi-person"></i> Khách hàng</h6>
        </div>
        <div class="card-body">
          <p class="mb-2">
            <strong>Tên:</strong><br>
            <?= e($order['customer_name']) ?>
          </p>
          <p class="mb-2">
            <strong>Email:</strong><br>
            <?= e($order['customer_email']) ?>
          </p>
          <p class="mb-2">
            <strong>SĐT:</strong><br>
            <?= e($order['customer_phone']) ?>
          </p>
          <p class="mb-0">
            <strong>Địa chỉ:</strong><br>
            <?= nl2br(e($order['customer_address'])) ?>
          </p>
          <?php if (!empty($order['customer_note'])): ?>
          <hr>
          <p class="mb-0">
            <strong>Ghi chú:</strong><br>
            <em><?= nl2br(e($order['customer_note'])) ?></em>
          </p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Ghi chú admin -->
      <?php if (!empty($order['admin_note']) || !empty($order['cancelled_reason'])): ?>
      <div class="card shadow-sm">
        <div class="card-header bg-warning">
          <h6 class="mb-0"><i class="bi bi-sticky"></i> Ghi chú Admin</h6>
        </div>
        <div class="card-body">
          <?php if (!empty($order['admin_note'])): ?>
          <p class="mb-2">
            <strong>Ghi chú:</strong><br>
            <?= nl2br(e($order['admin_note'])) ?>
          </p>
          <?php endif; ?>
          <?php if (!empty($order['cancelled_reason'])): ?>
          <p class="mb-0">
            <strong>Lý do hủy:</strong><br>
            <span class="text-danger"><?= nl2br(e($order['cancelled_reason'])) ?></span>
          </p>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Modal cập nhật trạng thái -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
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
          <strong>Khách hàng:</strong> <?= e($order['customer_name']) ?><br>
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

          <div class="alert alert-warning small mb-0">
            <i class="bi bi-exclamation-triangle"></i> <strong>Lưu ý:</strong> Khi chuyển sang trạng thái "Đã xác nhận", hệ thống sẽ tự động trừ số lượng tồn kho của các sản phẩm trong đơn hàng.
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
