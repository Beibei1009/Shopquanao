<?php
class CheckoutController {
    public function index() {
        // Kiểm tra giỏ hàng có sản phẩm không
        if (empty($_SESSION['cart'])) {
            echo "<script>alert('Giỏ hàng của bạn đang trống!'); window.location='/cart';</script>";
            exit;
        }

        // Xử lý thanh toán (giả lập)
        echo "<script>
            alert('🎉 Cảm ơn bạn đã đặt hàng thành công! Chúng tôi sẽ liên hệ sớm nhất.');
            window.location='/';
        </script>";

        // Xóa giỏ hàng sau khi đặt xong
        unset($_SESSION['cart']);
    }
}