<?php
class CartController
{
    // Hiển thị giỏ hàng
    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        //Kiểm tra giỏ hàng trong session 
        $sessionCart = $_SESSION['cart'] ?? [];
        //Khởi tạo mảng trống $cart để lưu các sản phẩm hợp lệ trong giỏ hàng.
        $cart = [];
        //Mảng $invalidItems được sử dụng để lưu các sản phẩm không hợp lệ
        $invalidItems = [];

        if (!empty($sessionCart)) {
            require __DIR__ . '/../../config/database.php';
            //Lặp qua tất cả các sản phẩm trong giỏ hàng ($sessionCart).
            //Mỗi sản phẩm có khóa ($cartKey) và giá trị ($item) là thông tin của sản phẩm.
            foreach ($sessionCart as $cartKey => $item) {
                // Lấy product ID và size (nếu có) từ giỏ hàng
                $productId = is_array($item) ? $item['product_id'] : $cartKey;

                // Lấy size nếu có
                $size = is_array($item) ? ($item['size'] ?? null) : null;

                // Lấy thông tin chi tiết của sản phẩm từ cơ sở dữ liệu database
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND is_active = TRUE");
                $stmt->execute([$productId]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($product) {
                    // Không cần check tồn kho - mặc định luôn còn hàng
                    //Lưu thông tin hợp lệ vào giỏ hàng
                    $cart[$cartKey] = [
                        'product_id' => $productId,
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'image' => $product['image'],
                        'description' => $product['description'],
                        'quantity' => is_array($item) ? $item['quantity'] : 1,
                        'size' => $size,
                        'color' => is_array($item) ? ($item['color'] ?? null) : null
                    ];
                } else {
                    // Đánh dấu item không hợp lệ để xóa
                    $invalidItems[] = $cartKey;
                }
            }

            // Xóa các items không hợp lệ khỏi session
            if (!empty($invalidItems)) {
                //Duyệt qua các sản phẩm không hợp lệ trong giỏ hàng
                // xóa chúng khỏi session
                foreach ($invalidItems as $key) {
                    unset($_SESSION['cart'][$key]);
                }
                $_SESSION['flash_error'] = 'Một số sản phẩm không còn tồn tại và đã được xóa khỏi giỏ hàng';
            }
        }

        include __DIR__ . '/../views/cart.php';
    }

    // Thêm sản phẩm vào giỏ
    public function add(): void
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        //Lấy dữ liệu sản phẩm cần thêm vào giỏ:
        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        $size = $_GET['size'] ?? $_POST['size'] ?? null;
        $color = $_GET['color'] ?? $_POST['color'] ?? null;
        $quantity = (int)($_GET['quantity'] ?? $_POST['quantity'] ?? 1);

        //Nếu không có ID sản phẩm hoặc số lượng <= 0 → coi như dữ liệu lỗi.
        if (!$id || $quantity <= 0) {
            $_SESSION['flash_error'] = 'Dữ liệu không hợp lệ';
            header('Location: /products');
            exit;
        }

        require __DIR__ . '/../../config/database.php';

        // Kiểm tra sản phẩm có tồn tại và đang active không
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND is_active = TRUE");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        //không tìm thấy sản phẩm → lỗi.
        if (!$product) {
            $_SESSION['flash_error'] = 'Sản phẩm không tồn tại';
            header('Location: /products');
            exit;
        }

        // Check size bắt buộc
        if (!$size) {
            $_SESSION['flash_error'] = 'Vui lòng chọn size sản phẩm';
            header('Location: /products?action=detail&id=' . $id);
            exit;
        }

        // Không cần check tồn kho - mặc định luôn còn hàng

        // session chưa có cart → khởi tạo giỏ hàng là mảng rỗng.
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Tạo key duy nhất cho từng biến thể trong giỏ hàng (size, color)
        //dùng xoá sản phẩm khỏi giỏ hàng và cập nhật số lượng chính xác.
        $cartKey = $id;
        if ($size) $cartKey .= '_' . $size;
        if ($color) $cartKey .= '_' . $color;

        // Thêm số lượng khi sản phẩm đã tồn tại trong giỏ
        if (isset($_SESSION['cart'][$cartKey])) {
            //số lượng cũ + số lượng thêm
            $newQty = $_SESSION['cart'][$cartKey]['quantity'] + $quantity;
            // Không cần check tồn kho - cho phép thêm không giới hạn
            $_SESSION['cart'][$cartKey]['quantity'] = $newQty;
        } else {
            //Thêm sản phẩm mới vào giỏ hàng với thông tin chi tiết
            $_SESSION['cart'][$cartKey] = [
                'product_id' => $id,
                'quantity' => $quantity,
                'size' => $size,
                'color' => $color
            ];
        }
        //số lương sản phẩm trong giỏ hàng 
        $_SESSION['cart_count'] = count($_SESSION['cart']);
        $_SESSION['flash_success'] = 'Đã thêm sản phẩm vào giỏ hàng';
        header('Location: /cart');
        exit;
    }

    // Xóa sản phẩm khỏi giỏ
    public function remove(): void
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        //id là passkey duy nhất của sản phẩm trong giỏ hàng
        $id = $_GET['id'] ?? null;

        //xóa sản phẩm khỏi giỏ hàng nếu tồn tại
        if ($id && isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }

        header('Location: /cart');
        exit;
    }

    // Cập nhật số lượng (AJAX) - hàm này không redirect, mà trả về JSON cho frontend.
    public function updateQuantity(): void
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        // /Báo cho browser biết: response là JSON, không phải HTML
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Lấy cart key và số lượng mới từ request
                $cartKey = $_POST['id'] ?? null;
                $quantity = (int)($_POST['quantity'] ?? 1);
                // Kiểm tra dữ liệu hợp lệ : cart key tồn tại và số lượng > 0
                if ($cartKey && isset($_SESSION['cart'][$cartKey]) && $quantity > 0) {
                    require_once __DIR__ . '/../../config/database.php';
                    $pdo = Database::getInstance();

                    // Lấy item trong giỏ theo key.
                    $item = $_SESSION['cart'][$cartKey];
                    $productId = is_array($item) ? $item['product_id'] : $cartKey;
                    $size = is_array($item) ? ($item['size'] ?? null) : null;

                    // Lấy lại giá sản phẩm từ bảng products
                    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ? AND is_active = TRUE");
                    $stmt->execute([$productId]);
                    $product = $stmt->fetch(PDO::FETCH_ASSOC);
                    // Nếu sản phẩm tồn tại
                    if ($product) {
                        // Không cần check tồn kho - cho phép cập nhật số lượng tự do

                        // Update quantity in session
                        $_SESSION['cart'][$cartKey]['quantity'] = $quantity;

                        // Calculate subtotal for this item
                        $subtotal = $product['price'] * $quantity;

                        // Calculate total for entire cart
                        $total = 0;
                        foreach ($_SESSION['cart'] as $key => $cartItem) {
                            $pid = is_array($cartItem) ? $cartItem['product_id'] : $key;
                            $qty = is_array($cartItem) ? $cartItem['quantity'] : 1;

                            $stmtPrice = $pdo->prepare("SELECT price FROM products WHERE id = ?");
                            $stmtPrice->execute([$pid]);
                            $productPrice = $stmtPrice->fetchColumn();

                            if ($productPrice) {
                                $total += $productPrice * $qty;
                            }
                        }

                        echo json_encode([
                            'success' => true,
                            'cart_count' => count($_SESSION['cart']),
                            'total' => $total,
                            'subtotal' => $subtotal
                        ]);
                        exit;
                    }
                }
            }

            echo json_encode(['success' => false, 'error' => 'Invalid request']);
            exit;
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
            exit;
        }
    }

    // Thanh toán giỏ hàng
    public function checkout(): void
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        //Check giỏ hàng rỗng
        if (empty($_SESSION['cart'])) {
            error_log('ERROR: Cart is empty!');
            $_SESSION['flash_error'] = 'Giỏ hàng trống. Vui lòng thêm sản phẩm trước khi thanh toán.';
            //Không cho vào màn thanh toán nếu giỏ hàng không có gì
            header('Location: /cart');
            exit;
        }

        // Bắt buộc phải đăng nhập
        // Gọi is_logged_in() (hàm helper) để kiểm tra user đã login chưa
        if (!is_logged_in()) {
            //Lưu redirect_after_login = '/cart' vào session →
            //sau khi login xong hệ thống có thể redirect user về lại giỏ hàng.
            $_SESSION['redirect_after_login'] = '/cart';
            header('Location: /auth/login');
            exit;
        }

        // xử lý nếu request là POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log('ERROR: Not a POST request');
            $_SESSION['flash_error'] = 'Yêu cầu không hợp lệ (không phải POST)';
            header('Location: /cart');
            exit;
        }
        //Xác thực token CSRF để chống tấn công CSRF
        if (!csrf_verify()) {
            error_log('ERROR: CSRF token invalid');
            error_log('Session CSRF: ' . ($_SESSION['csrf_token'] ?? 'NONE'));
            error_log('POST CSRF: ' . ($_POST['csrf_token'] ?? 'NONE'));
            $_SESSION['flash_error'] = 'Yêu cầu không hợp lệ (CSRF token không đúng)';
            header('Location: /cart');
            exit;
        }

        // . Lấy & validate thông tin khách hàng từ form
        //Tên, điện thoại, địa chỉ là bắt buộc
        //Dùng trim() để loại bỏ khoảng trắng đầu & cuối.
        $customerName = trim($_POST['customer_name'] ?? '');
        $customerPhone = trim($_POST['customer_phone'] ?? '');
        $customerAddress = trim($_POST['customer_address'] ?? '');
        $customerNote = trim($_POST['customer_note'] ?? '');

        // Validate
        // Bắt buộc phải có tên, số điện thoại, địa chỉ
        if (empty($customerName) || empty($customerPhone) || empty($customerAddress)) {
            $_SESSION['flash_error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc';
            header('Location: /cart');
            exit;
        }

        if (!preg_match('/^[0-9]{10,11}$/', $customerPhone)) {
            $_SESSION['flash_error'] = 'Số điện thoại không hợp lệ (10-11 số)';
            header('Location: /cart');
            exit;
        }

        require_once __DIR__ . '/../../config/database.php';
        $pdo = Database::getInstance();

        try {
            //Mở  transaction tất cả insert/update hoặc sẽ thành công hết
            //hoặc nếu lỗi → rollback hết.
            $pdo->beginTransaction();


            // Tính tổng tiền và load product details
            //Khởi tạo biến tổng tiền
            $subtotal = 0;
            $orderItems = [];
            $invalidItems = [];
            //DUyệt qua tất cả sản phẩm trong giỏ hàng
            foreach ($_SESSION['cart'] as $cartKey => $item) {
                $productId = is_array($item) ? $item['product_id'] : $cartKey;
                $quantity = is_array($item) ? $item['quantity'] : 1;
                $size = is_array($item) ? ($item['size'] ?? null) : null;
                $color = is_array($item) ? ($item['color'] ?? null) : null;

                // Lấy dữ liệu mới nhất của sản phẩm từ DB (tránh đổi giá, đổi tồn kho mà giỏ chưa update.)
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND is_active = TRUE");
                $stmt->execute([$productId]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                // Nếu sản phẩm không tồn tại nữa
                if (!$product) {
                    // Thêm vào danh sách xóa thay vì vứt lỗi ngay
                    $invalidItems[] = $cartKey;
                    continue;
                }

                // Không cần check tồn kho - mặc định luôn còn hàng
                //Tính thành tiền của từng item
                $itemSubtotal = $product['price'] * $quantity;
                //Cộng dồn vào tổng tiền
                $subtotal += $itemSubtotal;
                // Lưu thông tin chi tiết của item vào mảng order items
                $orderItems[] = [
                    'product_id' => $productId,
                    'product_name' => $product['name'],
                    'product_image' => $product['image'],
                    'product_price' => $product['price'],
                    'size' => $size,
                    'color' => $color,
                    'quantity' => $quantity,
                    'subtotal' => $itemSubtotal
                ];
            }

            // Nếu có sản phẩm không hợp lệ, xóa khỏi cart, rollback và thông báo
            //$invalidItems không rỗng
            if (!empty($invalidItems)) {
                $pdo->rollBack();
                foreach ($invalidItems as $key) {
                    // Xóa các item không hợp lệ khỏi session cart.
                    unset($_SESSION['cart'][$key]);
                }
                $_SESSION['flash_error'] = 'Một số sản phẩm trong giỏ hàng không còn tồn tại. Vui lòng kiểm tra lại giỏ hàng.';
                header('Location: /cart');
                exit;
            }

            // Kiểm tra giỏ hàng có rỗng không sau khi xóa invalid items
            //sau khi xử lý xong không còn sản phẩm hợp lệ → coi như giỏ trống:
            if (empty($orderItems)) {
                // Rollback transaction
                $pdo->rollBack();
                $_SESSION['flash_error'] = 'Giỏ hàng trống hoặc không có sản phẩm hợp lệ';
                header('Location: /cart');
                exit;
            }

            // Tính phí vận chuyển (miễn phí nếu >= 500k)
            $shippingFee = 0;
            $totalAmount = $subtotal + $shippingFee;

            // Lấy thông tin user khi login
            $user = $_SESSION['user'];

            // Sinh mã đơn hàng bằng PHP: ORD-YYYYMMDD-XXXX
            $orderCode = 'ORD-' . date('Ymd') . '-' . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

            // Tạo đơn hàng với thông tin từ form
            $stmt = $pdo->prepare(
                //Insert đơn hàng vào bảng orders - Bảng orders: lưu tổng thể đơn hàng.
                "INSERT INTO orders (
                    order_code, user_id, customer_name, customer_email, customer_phone,
                    customer_address, customer_note, subtotal, shipping_fee, total_amount,
                    payment_method, status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'COD', 'pending') RETURNING id"
            );
            $stmt->execute([
                $orderCode,
                $user['id'],
                $customerName,
                $user['email'],
                $customerPhone,
                $customerAddress,
                $customerNote,
                $subtotal,
                $shippingFee,
                $totalAmount
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $orderId = $result['id'];

            // Thêm order items
            $stmt = $pdo->prepare(
                //Duyệt từng item trong $orderItems - Bảng order_items: chi tiết từng sản phẩm trong đơn.
                "INSERT INTO order_items (
                    order_id, product_id, product_name, product_image,
                    product_price, quantity, subtotal
                ) VALUES (?, ?, ?, ?, ?, ?, ?)"
            );

            foreach ($orderItems as $item) {
                $stmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['product_name'],
                    $item['product_image'],
                    $item['product_price'],
                    $item['quantity'],
                    $item['subtotal']
                ]);
            }
            // /Xác nhận toàn bộ các thao tác insert/update vừa làm.
            $pdo->commit();

            // Xóa giỏ hàng
            $_SESSION['cart'] = [];
            $_SESSION['cart_count'] = 0;

            // Thông báo thành công và chuyển đến trang order success
            $_SESSION['flash_success'] = 'Đặt hàng thành công! Mã đơn: ' . $orderCode;
            header('Location: /orders');
            exit;

            // Nếu có lỗi xảy ra trong quá trình xử lý - Rollback toàn bộ transaction
            // thông báo lỗi và chuyển về trang giỏ hàng
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['flash_error'] = 'Lỗi thanh toán: ' . $e->getMessage();
            header('Location: /cart');
            exit;
        }
    }

    // Trang checkout success
    //Chỉ nhận thông tin mã đơn / id đơn, rồi load view để hiển thị thông tin thành công
    public function checkoutSuccess(): void
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        //Lấy order ID từ query string
        // nếu không có thì gán null
        $orderId = $_GET['order'] ?? null;

        include __DIR__ . '/../views/checkout_success.php';
    }
}
