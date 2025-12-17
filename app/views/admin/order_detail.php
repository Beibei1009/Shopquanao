<?php ob_start(); ?>

<div class="container my-4">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
    <h2 class="text-danger mb-0 fs-4 fs-md-2">
      <i class="bi bi-receipt"></i> <span class="d-none d-sm-inline">Chi tiết đơn hàng</span> #<?= e($order['order_code'] ?? $order['id']) ?>
    </h2>
    <a href="/admin/orders" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-arrow-left"></i> <span class="d-none d-sm-inline">Quay lại danh sách</span><span class="d-inline d-sm-none">Quay lại</span>
    </a>
  </div>

  <div class="row">
    <!-- Thông tin đơn hàng -->
    <div class="col-lg-8 order-2 order-lg-1">
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-danger text-white">
          <h5 class="mb-0 fs-6 fs-md-5"><i class="bi bi-box-seam"></i> Sản phẩm trong đơn hàng</h5>
        </div>
        <div class="card-body p-0 p-md-3">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th style="width: 80px;" class="d-none d-md-table-cell">Ảnh</th>
                  <th style="min-width: 140px;" class="ps-2 ps-md-3">Sản phẩm</th>
                  <th class="text-center d-none d-sm-table-cell">Size</th>
                  <th class="text-center d-none d-lg-table-cell">Màu</th>
                  <th class="text-end d-none d-md-table-cell">Đơn giá</th>
                  <th class="text-center" style="min-width: 40px;">SL</th>
                  <th class="text-end pe-2 pe-md-3" style="min-width: 90px;">Tổng</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($orderItems as $item): ?>
                <tr>
                  <td class="d-none d-md-table-cell">
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
                  <td class="ps-2 ps-md-3">
                    <div class="d-flex align-items-start flex-column">
                      <strong class="small"><?= e($item['product_name']) ?></strong>
                      <?php if (!empty($item['product_sku'])): ?>
                        <small class="text-muted" style="font-size: 0.7rem;">SKU: <?= e($item['product_sku']) ?></small>
                      <?php endif; ?>
                      <!-- Hiển thị thông tin size, màu trên mobile -->
                      <div class="d-sm-none mt-1">
                        <small class="text-muted" style="font-size: 0.7rem; line-height: 1.3;">
                          <?php if (!empty($item['size'])): ?>Size: <?= e($item['size']) ?><?php endif; ?>
                          <?= (!empty($item['size']) && !empty($item['color'])) ? ' | ' : '' ?>
                          <?php if (!empty($item['color'])): ?>Màu: <?= e($item['color']) ?><?php endif; ?>
                        </small>
                      </div>
                      <!-- Hiển thị đơn giá trên mobile -->
                      <div class="d-md-none mt-1">
                        <small class="text-muted" style="font-size: 0.75rem;">Giá: <?= format_vnd($item['product_price']) ?></small>
                      </div>
                    </div>
                  </td>
                  <td class="text-center d-none d-sm-table-cell">
                    <small><?= !empty($item['size']) ? e($item['size']) : '-' ?></small>
                  </td>
                  <td class="text-center d-none d-lg-table-cell">
                    <small><?= !empty($item['color']) ? e($item['color']) : '-' ?></small>
                  </td>
                  <td class="text-end d-none d-md-table-cell"><small><?= format_vnd($item['product_price']) ?></small></td>
                  <td class="text-center"><strong class="small">x<?= e($item['quantity']) ?></strong></td>
                  <td class="text-end text-danger fw-bold pe-2 pe-md-3"><small><?= format_vnd($item['subtotal']) ?></small></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
              <tfoot class="table-light">
                <tr>
                  <td colspan="2" class="d-md-none text-end pe-2"><small><strong>Tạm tính:</strong></small></td>
                  <td colspan="6" class="d-none d-md-table-cell text-end"><strong>Tạm tính:</strong></td>
                  <td class="text-end pe-2 pe-md-3"><?= format_vnd($order['subtotal']) ?></td>
                </tr>
                <tr>
                  <td colspan="2" class="d-md-none text-end pe-2"><small><strong>Phí ship:</strong></small></td>
                  <td colspan="6" class="d-none d-md-table-cell text-end"><strong>Phí vận chuyển:</strong></td>
                  <td class="text-end pe-2 pe-md-3"><?= format_vnd($order['shipping_fee']) ?></td>
                </tr>
                <?php if (!empty($order['discount_amount']) && $order['discount_amount'] > 0): ?>
                <tr>
                  <td colspan="2" class="d-md-none text-end pe-2"><small><strong>Giảm giá:</strong></small></td>
                  <td colspan="6" class="d-none d-md-table-cell text-end"><strong>Giảm giá:</strong></td>
                  <td class="text-end text-success pe-2 pe-md-3">-<?= format_vnd($order['discount_amount']) ?></td>
                </tr>
                <?php endif; ?>
                <tr class="table-danger">
                  <td colspan="2" class="d-md-none text-end pe-2"><strong class="small">Tổng:</strong></td>
                  <td colspan="6" class="d-none d-md-table-cell text-end"><strong>Tổng cộng:</strong></td>
                  <td class="text-end fw-bold pe-2 pe-md-3" style="font-size: 1.1rem;"><?= format_vnd($order['total_amount']) ?></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Thông tin khách hàng và trạng thái -->
    <div class="col-lg-4 order-1 order-lg-2">
      <!-- Trạng thái đơn hàng -->
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-danger text-white">
          <h6 class="mb-0 small"><i class="bi bi-info-circle"></i> Trạng thái</h6>
        </div>
        <div class="card-body p-2 p-md-3">
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
            <strong class="small">Trạng thái:</strong><br>
            <span class="badge bg-<?= $class ?> small mt-1"><?= $text ?></span>
          </p>
          <p class="mb-2">
            <strong class="small">Thanh toán:</strong><br>
            <small><?= $order['payment_method'] === 'cod' ? 'COD (Tiền mặt)' : e($order['payment_method']) ?></small>
          </p>
          <p class="mb-0">
            <strong class="small">Ngày đặt:</strong><br>
            <small><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></small>
          </p>
          <?php if (!empty($order['confirmed_at'])): ?>
          <p class="mb-0 mt-2">
            <strong class="small">Ngày xác nhận:</strong><br>
            <small><?= date('d/m/Y H:i', strtotime($order['confirmed_at'])) ?></small>
          </p>
          <?php endif; ?>

          <?php if (!in_array($order['status'], ['completed', 'cancelled'])): ?>
          <hr class="my-2">
          <button type="button" class="btn btn-sm btn-outline-primary w-100"
                  data-bs-toggle="modal"
                  data-bs-target="#statusModal"
                  style="font-size: 0.85rem;">
            <i class="bi bi-pencil"></i> Cập nhật trạng thái
          </button>
          <?php endif; ?>
        </div>
      </div>

      <!-- Thông tin khách hàng -->
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-danger text-white">
          <h6 class="mb-0 small"><i class="bi bi-person"></i> Khách hàng</h6>
        </div>
        <div class="card-body p-2 p-md-3">
          <p class="mb-2">
            <strong class="small">Tên:</strong><br>
            <small><?= e($order['customer_name']) ?></small>
          </p>
          <p class="mb-2">
            <strong class="small">Email:</strong><br>
            <small class="text-break"><?= e($order['customer_email']) ?></small>
          </p>
          <p class="mb-2">
            <strong class="small">SĐT:</strong><br>
            <small><?= e($order['customer_phone']) ?></small>
          </p>
          <p class="mb-0">
            <strong class="small">Địa chỉ:</strong><br>
            <small><?= nl2br(e($order['customer_address'])) ?></small>
          </p>
          <?php if (!empty($order['customer_note'])): ?>
          <hr class="my-2">
          <p class="mb-0">
            <strong class="small">Ghi chú:</strong><br>
            <small><em><?= nl2br(e($order['customer_note'])) ?></em></small>
          </p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Ghi chú admin -->
      <?php if (!empty($order['admin_note']) || !empty($order['cancelled_reason'])): ?>
      <div class="card shadow-sm">
        <div class="card-header bg-warning">
          <h6 class="mb-0 small"><i class="bi bi-sticky"></i> Ghi chú Admin</h6>
        </div>
        <div class="card-body p-2 p-md-3">
          <?php if (!empty($order['admin_note'])): ?>
          <p class="mb-2">
            <strong class="small">Ghi chú:</strong><br>
            <small><?= nl2br(e($order['admin_note'])) ?></small>
          </p>
          <?php endif; ?>
          <?php if (!empty($order['cancelled_reason'])): ?>
          <p class="mb-0">
            <strong class="small">Lý do hủy:</strong><br>
            <small class="text-danger"><?= nl2br(e($order['cancelled_reason'])) ?></small>
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
