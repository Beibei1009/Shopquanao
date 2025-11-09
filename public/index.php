<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/ProductController.php';
require_once __DIR__ . '/../app/Controllers/ContactController.php';
require_once __DIR__ . '/../app/Controllers/AboutController.php';
require_once __DIR__ . '/../app/Controllers/AdminController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/CartController.php';
require_once __DIR__ . '/../app/Controllers/SearchController.php';

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

    // 📞 Liên hệ
    case 'contact':
        (new ContactController())->index();
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
        case 'cart/checkout':
    (new CartController())->checkout();
    break;

    // 👩‍💼 Admin quản lý sản phẩm
    case 'admin/products':
        (new ProductController())->adminlist();
        break;

    // 🔍 Tìm kiếm sản phẩm
    case 'search':
        (new SearchController())->index();
        break;

    // 🚫 Mặc định 404
    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}