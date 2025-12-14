<?php ob_start(); ?>

<div class="container my-4">
    <h2 class="text-danger mb-4">
        <i class="bi bi-cart-check"></i> Đơn hàng của tôi
    </h2>
    <!-- Kiểm Tra Có Đơn Hàng Không -->
    <?php if (empty($orders)): ?>
        <div class="alert alert-info">
            Bạn chưa có đơn hàng nào. <a href="/products">Mua sắm ngay</a>
        </div>
    <?php else: ?>
        <!-- Danh Sách Đơn Hàng -->
        <table class="table table-hover">
            <thead class="table-danger">
                <tr>
                    <th>Mã đơn</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <!-- Duyệt qua từng đơn hàng trong mảng $orders -->
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td class="fw-bold"><?= e($order['order_code']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                    <td class="text-danger fw-bold"><?= format_vnd($order['total_amount']) ?></td>
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
                        <span class="badge bg-<?= $class ?>"><?= $text ?></span>
                    </td>
                    <td>
                        <a href="/orders/detail?id=<?= $order['id'] ?>"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Chi tiết
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>