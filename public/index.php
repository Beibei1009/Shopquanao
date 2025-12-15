<?php
// Composer Autoloader - tự động load tất cả class theo PSR-4
require_once __DIR__ . '/../vendor/autoload.php';

// Database config - vẫn cần load vì Database class chưa có namespace
require_once __DIR__ . '/../config/database.php';

// Sử dụng các class với namespace
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\AboutController;
use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\CartController;
use App\Controllers\OrderController;

$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

switch ($path) {
    // 🟩 Đăng nhập / Đăng ký / Đăng xuất
    case 'auth/login':
        (new AuthController())->login();
        break;

    case 'auth/register':
        (new AuthController())->register();
        break;

    case 'auth/logout':
        (new AuthController())->logout();
        break;

    // 🏠 Trang chủ
    case '':
        (new HomeController())->index();
        break;

    // 🛍️ Sản phẩm
    case 'products':
        $c = new ProductController();
        $a = $_GET['action'] ?? null;
        if ($a == 'detail') {
            $c->detail();
        } elseif ($a == 'adminlist') {
            $c->adminlist();
        } else {
            $c->index();
        }
        break;

    
    // ℹ️ Giới thiệu
    case 'about':
        (new AboutController())->index();
        break;

    // 🛒 Giỏ hàng
    case 'cart':
        (new CartController())->index();
        break;

    case 'cart/add':
        (new CartController())->add();
        break;

    case 'cart/remove':
        (new CartController())->remove();
        break;

    case 'cart/update':
        (new CartController())->updateQuantity();
        break;

    case 'cart/checkout':
        (new CartController())->checkout();
        break;

    case 'checkout-form':
        include __DIR__ . '/../app/views/checkout_form.php';
        break;

    // 👩‍💼 Admin quản lý sản phẩm
    case 'admin/products':
        (new ProductController())->adminlist();
        break;

    case 'admin/products/create':
        (new ProductController())->create();
        break;

    case 'admin/products/edit':
        (new ProductController())->edit();
        break;

    case 'admin/products/delete':
        (new ProductController())->delete();
        break;

    // 📦 Đơn hàng (user)
    case 'orders':
        (new OrderController())->index();
        break;

    case 'orders/detail':
        (new OrderController())->detail();
        break;

    // 📦 Admin quản lý đơn hàng
    case 'admin/orders':
        (new AdminController())->orders();
        break;

    case 'admin/orders/detail':
        (new AdminController())->orderDetail();
        break;

    case 'admin/orders/update-status':
        (new AdminController())->updateOrderStatus();
        break;

    // 🚫 Mặc định 404
    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
