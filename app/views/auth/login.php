<?php ob_start(); ?>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-lg border-0">
        <div class="card-header bg-danger text-white text-center py-4">
          <h3 class="mb-0">
            <i class="bi bi-box-arrow-in-right"></i> ĐĂNG NHẬP
          </h3>
        </div>

        <div class="card-body p-4">
          <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
              <i class="bi bi-exclamation-triangle-fill"></i> <?= e($error) ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          <?php endif; ?>

          <p class="text-center text-muted mb-4">
            Chưa có tài khoản? <a href="/auth/register" class="text-danger fw-bold text-decoration-none">Đăng ký ngay</a>
          </p>

          <form method="POST" action="/auth/login">
            <div class="mb-3">
              <label class="form-label fw-bold" for="email">
                <i class="bi bi-envelope"></i> Email
              </label>
              <input id="email" name="email" type="email"
                class="form-control form-control-lg"
                placeholder="example@email.com"
                value="<?= e($_POST['email'] ?? '') ?>"
                required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold" for="password">
                <i class="bi bi-lock"></i> Mật khẩu
              </label>
              <input id="password" name="password" type="password"
                class="form-control form-control-lg"
                placeholder="Nhập mật khẩu"
                required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember">
                <label class="form-check-label small" for="remember">
                  Ghi nhớ đăng nhập
                </label>
              </div>
              <a href="#" class="small text-muted text-decoration-none">Quên mật khẩu?</a>
            </div>

            <div class="d-grid">
              <button class="btn btn-danger btn-lg" type="submit">
                <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
              </button>
            </div>
          </form>

          <hr class="my-4">

          <div class="text-center">
            <p class="text-muted small mb-3">Hoặc đăng nhập bằng</p>
            <div class="d-grid gap-2">

              <a class="btn btn-outline-danger"
                href="/auth/google">
                <i class="bi bi-google"></i> Google

              </a>

            </div>
          </div>
        </div>

        <div class="card-footer bg-light text-center py-3">
          <small class="text-muted">
            <i class="bi bi-shield-check"></i> Thông tin của bạn được bảo mật
          </small>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>