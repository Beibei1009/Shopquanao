<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Đăng nhập tài khoản</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .brand-bar{background:#e53935;color:#fff}
    .brand-bar a{color:#fff;text-decoration:none}
    .login-card{max-width:520px;margin:40px auto;padding:32px;border:1px solid #ddd;border-radius:8px;background:#fff}
    .btn-primary{background:#e53935;border-color:#e53935}
    .btn-primary:hover{background:#c62828;border-color:#c62828}
  </style>
</head>
<body>

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
            <li class="nav-item"><a class="nav-link active" href="/auth/login">Đăng nhập</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- DẢI ĐỎ NHẸ -->
  <div class="brand-bar py-2">
    <div class="container text-center"><strong>ĐĂNG NHẬP TÀI KHOẢN</strong></div>
  </div>

  <div class="login-card container">
    <p class="text-center mb-3">
      Bạn chưa có tài khoản? <a href="/auth/register" class="link-danger fw-semibold">Đăng ký tại đây</a>
    </p>

    <form method="POST" action="/auth/login">
      <div class="mb-3">
        <label class="form-label" for="email">Email *</label>
        <input id="email" name="email" type="email" class="form-control" required>
      </div>
      <div class="mb-2">
        <label class="form-label" for="password">Mật khẩu *</label>
        <input id="password" name="password" type="password" class="form-control" required>
      </div>
      <div class="text-end mb-3">
        <a class="small text-muted text-decoration-none" href="#">Quên mật khẩu?</a>
      </div>
      <button class="btn btn-primary w-100" type="submit">Đăng nhập</button>
    </form>

    <div class="text-center text-secondary mt-3">Hoặc đăng nhập bằng</div>
<div class="text-center text-secondary mt-3">Hoặc đăng nhập bằng</div>
<div class="d-flex gap-2 mt-2 justify-content-center">

    <a href="#"
       onclick="alert('🔒 Tính năng đăng nhập Google sẽ được cập nhật sau!');"
       class="btn btn-danger w-50">
        <i class="bi bi-google"></i> Đăng nhập Google
    </a>

    <a href="#"
       onclick="alert('🔒 Tính năng đăng nhập Facebook sẽ được cập nhật sau!');"
       class="btn btn-primary w-50">
        <i class="bi bi-facebook"></i> Đăng nhập Facebook
    </a>

</div>

<!-- Thêm dòng này để hiển thị icon -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />