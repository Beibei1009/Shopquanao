<?php
class SearchController
{
    public function index(): void
    {
        // Nhận từ khóa tìm kiếm
        $keyword = $_GET['q'] ?? '';

        // Nếu chưa nhập gì thì quay lại trang sản phẩm
        if (trim($keyword) === '') {
            header('Location: /products');
            exit;
        }

        // Kết nối cơ sở dữ liệu
        require __DIR__ . '/../../config/database.php';

        // Tìm kiếm sản phẩm có tên chứa từ khóa
        $stmt = $pdo->prepare("SELECT * FROM products WHERE unaccent(name) ILIKE unaccent(?)");
        $stmt->execute(['%' . $keyword . '%']);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Hiển thị view
        include __DIR__ . '/../views/search.php';
    }
}