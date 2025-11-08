<?php
require_once __DIR__ . '/../Models/Product.php';

class ProductController {
    // Hiển thị danh sách sản phẩm
    public function index(): void {
        $productModel = new Product();
        $products = $productModel->all();
        include __DIR__ . '/../views/products/list.php';
    }

    // Hiển thị chi tiết sản phẩm
    public function detail(): void {
        if (!isset($_GET['id'])) {
            die('Thiếu ID sản phẩm');
        }

        $productModel = new Product();
        $product = $productModel->find($_GET['id']);
        include __DIR__ . '/../views/products/detail.php';
    }

    // Trang quản trị sản phẩm (chỉ admin)
    public function adminlist(): void {
        // Chỉ cho phép admin đã đăng nhập mới xem được
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: /auth/login');
            exit;
        }

        $productModel = new Product();
        $products = $productModel->all();
        include __DIR__ . '/../views/admin/admin_list.php';
    }
}