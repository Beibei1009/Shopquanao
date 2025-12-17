<?php ob_start(); ?>
<!-- hiển thị mã đơn hàng -->
<div class="container my-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <h3 class="mb-0 fs-5 fs-md-4">
            <i class="bi bi-receipt"></i> <span class="d-none d-sm-inline">Chi tiết đơn hàng:</span> <?= e($order['order_code']) ?>
        </h3>
        <a href="/orders" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> <span class="d-none d-sm-inline">Quay lại</span>
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
        <div class="col-lg-8 order-2 order-lg-1">
            <!-- Thông Tin Giao Hàng -->
            <div class="card mb-3">
                <div class="card-body p-2 p-md-3">
                    <h5 class="card-title fs-6">
                        <i class="bi bi-geo-alt"></i> Thông tin giao hàng
                    </h5>
                    <hr class="my-2">
                    <p class="mb-1"><strong class="small">Họ tên:</strong> <small><?= e($order['customer_name']) ?></small></p>
                    <p class="mb-1"><strong class="small">Email:</strong> <small class="text-break"><?= e($order['customer_email']) ?></small></p>
                    <p class="mb-1"><strong class="small">SĐT:</strong> <small><?= e($order['customer_phone']) ?></small></p>
                    <p class="mb-1"><strong class="small">Địa chỉ:</strong> <small><?= e($order['customer_address']) ?></small></p>
                    <?php if (!empty($order['customer_note'])): ?>
                    <p class="mb-1"><strong class="small">Ghi chú:</strong> <small><em><?= e($order['customer_note']) ?></em></small></p>
                    <?php endif; ?>
                    <p class="mb-0">
                        <strong class="small">Ngày đặt:</strong>
                        <small><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></small>
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0 p-md-3">
                    <h5 class="fs-6 px-2 px-md-0 pt-2 pt-md-0">Sản phẩm</h5>
                    <!-- //Bảng Sản Phẩm: Liệt kê các sản phẩm trong đơn hàng với các thông tin -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-2 ps-md-3" style="min-width: 150px;">Sản phẩm</th>
                                    <th class="d-none d-sm-table-cell">Size</th>
                                    <th class="d-none d-lg-table-cell">Màu</th>
                                    <th class="d-none d-md-table-cell">Giá</th>
                                    <th class="text-center" style="min-width: 40px;">SL</th>
                                    <th class="text-end pe-2 pe-md-3" style="min-width: 90px;">Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order['items'] as $item): ?>
                                <tr>
                                    <td class="ps-2 ps-md-3">
                                        <div class="d-flex align-items-start gap-2">
                                            <?php if ($item['product_image']): ?>
                                            <img src="/uploads/<?= e($item['product_image']) ?>"
                                                 style="width: 40px; height: 40px; object-fit: cover;" class="rounded d-none d-md-block">
                                            <?php endif; ?>
                                            <div class="d-flex flex-column">
                                                <strong class="small"><?= e($item['product_name']) ?></strong>
                                                <!-- Thông tin size, màu, giá trên mobile -->
                                                <div class="d-sm-none mt-1">
                                                    <small class="text-muted" style="font-size: 0.7rem;">
                                                        <?php if (!empty($item['size'])): ?>Size: <?= e($item['size']) ?><?php endif; ?>
                                                        <?= (!empty($item['size']) && !empty($item['color'])) ? ' | ' : '' ?>
                                                        <?php if (!empty($item['color'])): ?>Màu: <?= e($item['color']) ?><?php endif; ?>
                                                    </small>
                                                </div>
                                                <div class="d-md-none mt-1">
                                                    <small class="text-muted" style="font-size: 0.75rem;">Giá: <?= format_vnd($item['product_price']) ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-sm-table-cell"><small><?= e($item['size'] ?? '-') ?></small></td>
                                    <td class="d-none d-lg-table-cell"><small><?= e($item['color'] ?? '-') ?></small></td>
                                    <td class="d-none d-md-table-cell"><small><?= format_vnd($item['product_price']) ?></small></td>
                                    <td class="text-center"><strong class="small">x<?= $item['quantity'] ?></strong></td>
                                    <td class="fw-bold text-end pe-2 pe-md-3"><small class="text-danger"><?= format_vnd($item['subtotal']) ?></small></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
         <!-- Tổng Kết Đơn Hàng                                -->
        <div class="col-lg-4 order-1 order-lg-2 mb-3 mb-lg-0">
            <div class="card">
                <div class="card-body p-2 p-md-3">
                    <h5 class="fs-6">Tổng kết</h5>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between mb-1">
                        <small>Tạm tính:</small>
                        <small><?= format_vnd($order['subtotal']) ?></small>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <small>Phí vận chuyển:</small>
                        <small><?= format_vnd($order['shipping_fee']) ?></small>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between fw-bold">
                        <span class="small">Tổng cộng:</span>
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