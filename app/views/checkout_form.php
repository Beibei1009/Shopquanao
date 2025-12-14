<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm Tra Đăng Nhập Người Dùng - Authorization
if (!is_logged_in()) {
    //$_SESSION['redirect_after_login']: Lưu URL cần chuyển hướng sau khi người dùng đăng nhập thành công.
    // Sau khi đăng nhập xong, người dùng sẽ được đưa trở lại trang checkout.
    $_SESSION['redirect_after_login'] = '/checkout-form';
    header('Location: /auth/login');
    exit;
}

// Kiểm Tra Giỏ Hàng - Authorization
if (empty($_SESSION['cart'])) {
    $_SESSION['flash_error'] = 'Giỏ hàng trống';
    header('Location: /cart');
    exit;
}
//ob_start output buffering 
ob_start();
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="bi bi-clipboard-check"></i> Thông tin nhận hàng</h4>
                </div>
                <div class="card-body">
                    <!-- //Form Thanh Toán - Token CSRF-->
                    <form method="POST" action="/cart/checkout">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <!-- Thông Tin Người Nhận -->
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">
                                Họ và tên <span class="text-danger">*</span>
                            </label>
                            <!-- htmlspecialchars- Đảm bảo tên người dùng được an toàn trước XSS -->
                            <input type="text"
                                   class="form-control"
                                   id="customer_name"
                                   Đảm bảo tên người dùng được an toàn trước XSS
                                   name="customer_name"
                                   Đảm bảo tên người dùng được an toàn trước XSS
                                   value="<?= htmlspecialchars($_SESSION['user']['name'] ?? '', ENT_QUOTES) ?>"
                                   required>
                        </div>
                        <!-- //Thông Tin Liên Hệ (Số điện thoại, Địa chỉ) -->
                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">
                                Số điện thoại <span class="text-danger">*</span>
                            </label>
                            <!-- required: Cũng yêu cầu trường này phải được điền. -->
                            <input type="text"
                                   class="form-control"
                                   id="customer_phone"
                                   name="customer_phone"
                                   value=""
                                   placeholder="0123456789"
                                   required>
                            <small class="text-muted">Nhập 10-11 chữ số</small>
                        </div>
                            <!-- !-- required: Cũng yêu cầu trường này phải được điền. -->
                        <div class="mb-3">
                            <label for="customer_address" class="form-label">
                                Địa chỉ nhận hàng <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control"
                                      id="customer_address"
                                      name="customer_address"
                                      rows="3"
                                      required></textarea>
                        </div>
                        <!-- Ghi chú người dùng -->
                        <div class="mb-3">
                            <label for="customer_note" class="form-label">Ghi chú (tùy chọn)</label>
                            <textarea class="form-control"
                                      id="customer_note"
                                      name="customer_note"
                                      rows="2"
                                      placeholder="Ví dụ: Giao giờ hành chính..."></textarea>
                        </div>
                        <!-- Thông Tin Đơn Hàng -->
                        <div class="alert alert-info">
                            <h6><i class="bi bi-info-circle"></i> Thông tin đơn hàng</h6>
                            <p class="mb-1">Số sản phẩm: <strong><?= count($_SESSION['cart']) ?> items</strong></p>
                            <p class="mb-0">Phương thức: <strong>Thanh toán khi nhận hàng (COD)</strong></p>
                        </div>
                        <!-- Nút Xác Nhận và Quay Lại Giỏ Hàng -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="bi bi-check-circle"></i> Xác nhận đặt hàng
                            </button>
                            <a href="/cart" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Quay lại giỏ hàng
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Kết thúc Output Buffering và Gửi Nội Dung
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
