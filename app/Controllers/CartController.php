<?php
class CartController
{
    // Hiển thị giỏ hàng
    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        $cart = $_SESSION['cart'] ?? [];
        include __DIR__ . '/../views/cart.php';
    }

    // Thêm sản phẩm vào giỏ
    public function add(): void
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /products');
            exit;
        }

        require __DIR__ . '/../../config/database.php';

        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity'] += 1;
            } else {
                $product['quantity'] = 1;
                $_SESSION['cart'][$id] = $product;
            }
        }

        header('Location: /cart');
        exit;
    }

    // Xóa sản phẩm khỏi giỏ
    public function remove(): void
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        $id = $_GET['id'] ?? null;
        if ($id && isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }

        header('Location: /cart');
        exit;
    }

    // Thanh toán giỏ hàng
    public function checkout(): void
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (empty($_SESSION['cart'])) {
            header('Location: /cart');
            exit;
        }

        // Xóa giỏ hàng sau khi thanh toán
        $_SESSION['cart'] = [];

        // Hiện popup cảm ơn có hiệu ứng ❤️
        echo "
        <div id='overlay'></div>
        <div id='popup'>
            <div class='heart'>❤️</div>
            <h3>Cảm ơn bạn đã đặt hàng!</h3>
            <p>Chúng tôi sẽ liên hệ xác nhận sớm.</p>
            <button onclick=\"window.location.href='/'\">Quay lại trang chủ</button>
        </div>

        <style>
            #overlay {
                position: fixed;
                top: 0; left: 0;
                width: 100%; height: 100%;
                background: rgba(0, 0, 0, 0.3);
                z-index: 1000;
                opacity: 0;
                animation: fadeIn 0.5s forwards;
            }
            #popup {
                position: fixed;
                top: 50%; left: 50%;
                transform: translate(-50%, -50%) scale(0.8);
                background: #fff;
                padding: 25px;
                border-radius: 15px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
                text-align: center;
                z-index: 1001;
                opacity: 0;
                animation: popupIn 0.5s forwards;
            }
                #popup h3 {
                color: #28a745;
                margin-bottom: 10px;
                font-size: 22px;
            }
            #popup p {
                font-size: 15px;
                color: #333;
                margin-bottom: 15px;
            }
            #popup button {
                background: #28a745;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 6px;
                cursor: pointer;
                font-weight: bold;
                transition: 0.3s;
            }
            #popup button:hover {
                background: #218838;
            }
            .heart {
                font-size: 28px;
                animation: heartbeat 1s infinite;
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            @keyframes popupIn {
                from { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
                to { opacity: 1; transform: translate(-50%, -50%) scale(1); }
            }

            @keyframes heartbeat {
                0%, 40%, 80%, 100% { transform: scale(1); }
                20%, 60% { transform: scale(1.3); }
            }
        </style>

        <script>
            setTimeout(function(){
                window.location.href = '/';
            }, 3000);
        </script>
        ";
        exit;
    }
}
?>