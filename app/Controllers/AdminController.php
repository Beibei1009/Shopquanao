<?php
namespace App\Controllers;

class AdminController
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = \Database::getInstance();
    }
    // Dashboard admin
    public function index()
    {
        require_admin();
        include __DIR__ . '/../views/admin_list.php';
    }
    // Quản lý đơn hàng
    public function orders()
    {
        require_admin();

        // Lấy tất cả đơn hàng, join với users để biết user nào đặt.
        // cột của bảng orders và thông tin user
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC";
        $stmt = $this->pdo->query($sql);
        //Lấy toàn bộ kết quả thành một mảng các dòng.
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/admin/order.php';
    }

    // Chi tiết đơn hàng
    public function orderDetail()
    {
        require_admin();
        // Lấy order ID từ query string
        $orderId = $_GET['id'] ?? null;
        // Nếu không có order ID thì báo lỗi
        if (!$orderId) {
            die('Missing order ID');
        }

        // Lấy thông tin đơn hàng
        $stmt = $this->pdo->prepare(
            "SELECT o.*, u.name as user_name, u.email as user_email
             FROM orders o
             LEFT JOIN users u ON o.user_id = u.id
             WHERE o.id = ?"
        );
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        // Nếu không tìm thấy đơn hàng
        if (!$order) {
            die('Order not found');
        }

        // Lấy các items trong đơn hàng
        $stmt = $this->pdo->prepare(
            "SELECT oi.*, p.image as product_image
             FROM order_items oi
             LEFT JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id = ?"
        );
        $stmt->execute([$orderId]);
        $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/admin/order_detail.php';
    }

    // Cập nhật trạng thái đơn hàng
    public function updateOrderStatus()
    {
        require_admin();
        // Xử lý chỉ khi có POST và token CSRF hợp lệ
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
            $orderId = $_POST['order_id'] ?? null;
            $newStatus = $_POST['status'] ?? 'pending';

            if ($orderId) {
                if (session_status() === PHP_SESSION_NONE)
                    session_start();

                try {
                    // Bắt đầu transaction
                    $this->pdo->beginTransaction();

                    // Lấy thông tin đơn hàng hiện tại
                    $stmt = $this->pdo->prepare("SELECT status FROM orders WHERE id = ?");
                    $stmt->execute([$orderId]);
                    $order = $stmt->fetch(PDO::FETCH_ASSOC);
                    // Nếu đơn hàng không tồn tại
                    if (!$order) {
                        throw new Exception('Đơn hàng không tồn tại');
                    }
                    // Trạng thái hiện tại của đơn hàng
                    $currentStatus = $order['status'];

                    // Ngăn cập nhật nếu đơn hàng đã hoàn thành hoặc đã hủy - K cho phép thay đổi trạng thái nữa
                    // -> Rollback transaction -> redirect
                    if (in_array($currentStatus, ['completed', 'cancelled'])) {
                        $_SESSION['flash_error'] = 'Không thể cập nhật trạng thái đơn hàng đã hoàn thành hoặc đã hủy';
                        $this->pdo->rollBack();
                        header('Location: /admin/orders');
                        exit;
                    }

                    // Cập nhật trạng thái đơn hàng
                    $updateFields = ['status = ?'];
                    $updateParams = [$newStatus];

                    // Thêm confirmed_at nếu chuyển sang trạng thái confirmed 
                    if ($newStatus === 'confirmed' && $currentStatus !== 'confirmed') {
                        $updateFields[] = 'confirmed_at = CURRENT_TIMESTAMP';
                    }
                    $sql = "UPDATE orders SET " . implode(', ', $updateFields) . " WHERE id = ?";
                    $updateParams[] = $orderId;
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute($updateParams);

                    // Không cần trừ kho - mặc định sản phẩm luôn còn hàng

                    $this->pdo->commit();
                    $_SESSION['flash_success'] = 'Cập nhật trạng thái đơn hàng thành công';
                } catch (Exception $e) {
                    $this->pdo->rollBack();
                    $_SESSION['flash_error'] = 'Lỗi: ' . $e->getMessage();
                }
            }
        }

        header('Location: /admin/orders');
        exit;
    }
}
