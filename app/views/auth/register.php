<<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Đăng ký tài khoản</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    <style>
  .btn {
    border-radius: 30px !important;
    font-weight: 500;
    transition: 0.3s ease;
  }

  .btn:hover {
    opacity: 0.9;
    transform: scale(1.02);
  }

  .bi {
    margin-right: 6px;
  }
</style>
  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg bg-light border-bottom">
    <div class="container">
      <a class="navbar-brand fw-bold" href="/">SHOP QUẦN ÁO THỜI TRANG</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="/">Mới Mở</a></li>
          <li class="nav-item"><a class="nav-link" href="/products">Sản phẩm</a></li>
          <li class="nav-item"><a class="nav-link" href="/about">Giới thiệu</a></li>
          <li class="nav-item"><a class="nav-link" href="/contact">Liên hệ</a></li>
        </ul>
        <ul class="navbar-nav">
          <?php if (!empty($_SESSION['user'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="/auth/logout">Đăng xuất (<?= htmlspecialchars($_SESSION['user']['name']) ?>)</a>
            </li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link active" href="/auth/register">Đăng ký</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- DẢI ĐỎ NHẸ -->
  <div class="brand-bar py-2">
    <div class="container text-center"><strong>ĐĂNG KÝ TÀI KHOẢN</strong></div>
  </div>

  <div class="register-card container">
    <p class="text-center mb-3">
      Bạn đã có tài khoản? <a href="/auth/login" class="link-danger fw-semibold">Đăng nhập tại đây</a>
    </p>

    <h6 class="section-title mb-3">THÔNG TIN CÁ NHÂN</h6>

    <form method="POST" action="/auth/register">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label" for="firstname">Họ *</label>
          <input id="firstname" name="firstname" type="text" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label" for="lastname">Tên *</label>
          <input id="lastname" name="lastname" type="text" class="form-control" required>
        </div>
        <div class="col-12">
          <label class="form-label" for="phone">Số điện thoại *</label>
          <input id="phone" name="phone" type="text" class="form-control" required>
        </div>
        <div class="col-12">
          <label class="form-label" for="email">Email *</label>
          <input id="email" name="email" type="email" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label" for="password">Mật khẩu *</label>
          <input id="password" name="password" type="password" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label" for="confirm_password">Nhập lại mật khẩu *</label>
          <input id="confirm_password" name="confirm_password" type="password" class="form-control" required>
        </div>
      </div>

      <button class="btn btn-primary w-100 mt-3" type="submit">Đăng ký</button>

<div class="text-center text-secondary mt-3">Hoặc đăng ký bằng</div>
<div class="d-flex gap-2 mt-2 justify-content-center">

  <a href="#"
     onclick="alert('🔒 Tính năng đăng ký với Google sẽ được cập nhật sau!');"
     class="btn btn-danger w-50">
    <i class="bi bi-google"></i> Đăng ký Google
  </a>

  <a href="#"
     onclick="alert('🔒 Tính năng đăng ký với Facebook sẽ được cập nhật sau!');"
     class="btn btn-primary w-50">
    <i class="bi bi-facebook"></i> Đăng ký Facebook
  </a>

</div>
</form>

<!-- Thêm link icon ở cuối -->
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>