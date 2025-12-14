<?php ob_start(); ?>
<!-- hiển thị mã đơn hàng -->
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">
            <i class="bi bi-receipt"></i> Chi tiết đơn hàng: <?= e($order['order_code']) ?>
        </h3>
        <a href="/orders" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
<!-- Trạng Thái Đơn Hàng -->
    <?php
    // hệ thống sẽ hiển thị màu sắc và văn bản tương ứng với trạng thái đơn hàng
    $statusClass = [
        'pending' => 'warning',
        'confirmed' => 'primary',
        'processing' => 'info',
        'shipping' => 'info',
        'completed' => 'success',
        'cancelled' => 'danger'
    ];
    $statusText = [
        'pending' => 'Chờ xử lý',
        'confirmed' => 'Đã xác nhận',
        'processing' => 'Đang chuẩn bị',
        'shipping' => 'Đang giao',
        'completed' => 'Hoàn thành',
        'cancelled' => 'Đã hủy'
    ];
    $class = $statusClass[$order['status']] ?? 'secondary';
    $text = $statusText[$order['status']] ?? ucfirst($order['status']);
    ?>

    <div class="alert alert-<?= $class ?> mb-4">
        <strong>Trạng thái đơn hàng:</strong>
        <span class="badge bg-<?= $class ?> ms-2"><?= $text ?></span>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Thông Tin Giao Hàng -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-geo-alt"></i> Thông tin giao hàng
                    </h5>
                    <hr>
                    <p><strong>Họ tên:</strong> <?= e($order['customer_name']) ?></p>
                    <p><strong>Email:</strong> <?= e($order['customer_email']) ?></p>
                    <p><strong>SĐT:</strong> <?= e($order['customer_phone']) ?></p>
                    <p><strong>Địa chỉ:</strong> <?= e($order['customer_address']) ?></p>
                    <?php if (!empty($order['customer_note'])): ?>
                    <p><strong>Ghi chú:</strong> <em><?= e($order['customer_note']) ?></em></p>
                    <?php endif; ?>
                    <p class="mb-0">
                        <strong>Ngày đặt:</strong>
                        <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5>Sản phẩm</h5>
                    <!-- //Bảng Sản Phẩm: Liệt kê các sản phẩm trong đơn hàng với các thông tin -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Size</th>
                                <th>Màu</th>
                                <th>Giá</th>
                                <th>SL</th>
                                <th>Tổng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order['items'] as $item): ?>
                            <tr>
                                <td>
                                    <?php if ($item['product_image']): ?>
                                    <img src="/uploads/<?= e($item['product_image']) ?>"
                                         style="width: 50px" class="me-2">
                                    <?php endif; ?>
                                    <?= e($item['product_name']) ?>
                                </td>
                                <td><?= e($item['size'] ?? '-') ?></td>
                                <td><?= e($item['color'] ?? '-') ?></td>
                                <td><?= format_vnd($item['product_price']) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td class="fw-bold"><?= format_vnd($item['subtotal']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
         <!-- Tổng Kết Đơn Hàng                                -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Tổng kết</h5>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>Tạm tính:</span>
                        <span><?= format_vnd($order['subtotal']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Phí vận chuyển:</span>
                        <span><?= format_vnd($order['shipping_fee']) ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Tổng cộng:</span>
                        <span class="text-danger"><?= format_vnd($order['total_amount']) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>