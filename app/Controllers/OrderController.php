<?php
class OrderController
{
    // Danh sách đơn hàng của user
    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!is_logged_in()) {
            $_SESSION['redirect_after_login'] = '/orders';
            header('Location: /auth/login');
            exit;
        }

        require_once __DIR__ . '/../../config/database.php';
        $pdo = Database::getInstance();

        $userId = $_SESSION['user']['id'];
        $stmt = $pdo->prepare(
            "SELECT * FROM orders
             WHERE user_id = ?
             ORDER BY created_at DESC"
        );
        $stmt->execute([$userId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/orders/index.php';
    }

    // Chi tiết đơn hàng
    public function detail(): void
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!is_logged_in()) {
            $_SESSION['redirect_after_login'] = '/orders';
            header('Location: /auth/login');
            exit;
        }

        $orderId = $_GET['id'] ?? null;
        if (!$orderId) {
            header('Location: /orders');
            exit;
        }

        require_once __DIR__ . '/../../config/database.php';
        $pdo = Database::getInstance();

        // Get order
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            $_SESSION['flash_error'] = 'Không tìm thấy đơn hàng';
            header('Location: /orders');
            exit;
        }

        // IDOR Prevention: Check if order belongs to current user
        if ($order['user_id'] != $_SESSION['user']['id'] && !is_admin()) {
            $_SESSION['flash_error'] = 'Bạn không có quyền xem đơn hàng này';
            header('Location: /orders');
            exit;
        }

        // Get order items
        $stmt = $pdo->prepare(
            "SELECT * FROM order_items WHERE order_id = ?"
        );
        $stmt->execute([$orderId]);
        $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $order['items'] = $orderItems;

        include __DIR__ . '/../views/orders/detail.php';
    }
}
?>
