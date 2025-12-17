<?php ob_start(); ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-danger text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="bi bi-person-plus"></i> ĐĂNG KÝ TÀI KHOẢN
                    </h3>
                </div>

                <div class="card-body p-4">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle-fill"></i> <?= e($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle-fill"></i> <?= e($success) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <p class="text-center text-muted mb-4">
                        Đã có tài khoản? <a href="/auth/login" class="text-danger fw-bold text-decoration-none">Đăng nhập ngay</a>
                    </p>

                    <form method="POST" action="/auth/register">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="firstname">
                                    <i class="bi bi-person"></i> Họ
                                </label>
                                <input id="firstname" name="firstname" type="text"
                                    class="form-control"
                                    placeholder="Nguyễn"
                                    value="<?= e($_POST['firstname'] ?? '') ?>"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="lastname">
                                    <i class="bi bi-person"></i> Tên
                                </label>
                                <input id="lastname" name="lastname" type="text"
                                    class="form-control"
                                    placeholder="Văn A"
                                    value="<?= e($_POST['lastname'] ?? '') ?>"
                                    required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold" for="email">
                                    <i class="bi bi-envelope"></i> Email
                                </label>
                                <input id="email" name="email" type="email"
                                    class="form-control"
                                    placeholder="example@email.com"
                                    value="<?= e($_POST['email'] ?? '') ?>"
                                    required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold" for="phone">
                                    <i class="bi bi-telephone"></i> Số điện thoại
                                </label>
                                <input id="phone" name="phone" type="text"
                                    class="form-control"
                                    placeholder="0123456789"
                                    value="<?= e($_POST['phone'] ?? '') ?>"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="password">
                                    <i class="bi bi-lock"></i> Mật khẩu
                                </label>
                                <input id="password" name="password" type="password"
                                    class="form-control"
                                    placeholder="Tối thiểu 6 ký tự"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="confirm_password">
                                    <i class="bi bi-lock-fill"></i> Xác nhận mật khẩu
                                </label>
                                <input id="confirm_password" name="confirm_password" type="password"
                                    class="form-control"
                                    placeholder="Nhập lại mật khẩu"
                                    required>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label small" for="terms">
                                        Tôi đồng ý với <a href="#" class="text-danger text-decoration-none">Điều khoản sử dụng</a>
                                        và <a href="#" class="text-danger text-decoration-none">Chính sách bảo mật</a>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button class="btn btn-danger btn-lg" type="submit">
                                <i class="bi bi-person-plus"></i> Đăng ký
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted small mb-3">Hoặc đăng ký bằng</p>
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