<?php ob_start(); ?>

<div class="container my-4">
    <h2 class="text-danger mb-4 fs-4 fs-md-3">
        <i class="bi bi-cart-check"></i> Đơn hàng của tôi
    </h2>
    <!-- Kiểm Tra Có Đơn Hàng Không -->
    <?php if (empty($orders)): ?>
        <div class="alert alert-info">
            Bạn chưa có đơn hàng nào. <a href="/products">Mua sắm ngay</a>
        </div>
    <?php else: ?>
        <!-- Danh Sách Đơn Hàng -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-danger">
                    <tr>
                        <th style="min-width: 100px;">Mã đơn</th>
                        <th class="d-none d-md-table-cell" style="min-width: 140px;">Ngày đặt</th>
                        <th style="min-width: 100px;">Tổng tiền</th>
                        <th style="min-width: 110px;">Trạng thái</th>
                        <th style="min-width: 90px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Duyệt qua từng đơn hàng trong mảng $orders -->
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td class="fw-bold small"><?= e($order['order_code']) ?></td>
                        <td class="d-none d-md-table-cell"><small><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></small></td>
                        <td>
                            <div class="text-danger fw-bold small"><?= format_vnd($order['total_amount']) ?></div>
                            <!-- Hiển thị ngày đặt trên mobile -->
                            <div class="d-md-none">
                                <small class="text-muted" style="font-size: 0.7rem;"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></small>
                            </div>
                        </td>
                        <td>
                            <?php
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
                            <span class="badge bg-<?= $class ?> small"><?= $text ?></span>
                        </td>
                        <td>
                            <a href="/orders/detail?id=<?= $order['id'] ?>"
                               class="btn btn-sm btn-outline-primary"
                               style="font-size: 0.8rem; padding: 0.25rem 0.5rem;">
                                <i class="bi bi-eye"></i> <span class="d-none d-lg-inline">Chi tiết</span>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>