<?php ob_start(); ?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-6 text-center">
      <div class="card shadow-lg border-0">
        <div class="card-body p-5">
          <div class="mb-4 success-icon">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 100px;"></i>
          </div>

          <h2 class="text-success mb-3 fw-bold">Đặt hàng thành công!</h2>

          <p class="text-muted mb-4">
            Cảm ơn bạn đã mua hàng tại <strong class="text-danger">LILY & CO.</strong>.<br>
            Chúng tôi sẽ liên hệ xác nhận đơn hàng sớm nhất.
          </p>

          <?php if (isset($orderId) && $orderId): ?>
          <div class="alert alert-info d-inline-block">
            <i class="bi bi-receipt"></i> <strong>Mã đơn hàng:</strong>
            <span class="badge bg-primary fs-6">#<?= str_pad($orderId, 6, '0', STR_PAD_LEFT) ?></span>
          </div>
          <?php endif; ?>

          <div class="my-4">
            <div class="row text-start">
              <div class="col-md-6">
                <h6 class="text-muted mb-3">
                  <i class="bi bi-clock-history text-warning"></i> Tình trạng
                </h6>
                <p class="mb-0"><span class="badge bg-warning">Chờ xử lý</span></p>
              </div>
              <div class="col-md-6">
                <h6 class="text-muted mb-3">
                  <i class="bi bi-truck text-info"></i> Giao hàng
                </h6>
                <p class="mb-0">2-3 ngày</p>
              </div>
            </div>
          </div>

          <hr>

          <div class="d-grid gap-2 mt-4">
            <a href="/" class="btn btn-danger btn-lg">
              <i class="bi bi-house-door"></i> Về trang chủ
            </a>
            <a href="/products" class="btn btn-outline-secondary">
              <i class="bi bi-bag"></i> Tiếp tục mua sắm
            </a>
          </div>

          <div class="mt-4">
            <small class="text-muted">
              <i class="bi bi-info-circle"></i>
              Nếu có thắc mắc, vui lòng <a href="/contact">liên hệ</a>
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
@keyframes checkmark {
  0% {
    transform: scale(0) rotate(0deg);
    opacity: 0;
  }
  50% {
    transform: scale(1.2) rotate(180deg);
  }
  100% {
    transform: scale(1) rotate(360deg);
    opacity: 1;
  }
}

.success-icon i {
  animation: checkmark 0.6s ease-out;
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
