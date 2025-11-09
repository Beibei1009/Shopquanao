<?php
require_once __DIR__ . '/../../config/database.php';

class Product {
    private $pdo;

    public function __construct() {
        require __DIR__ . '/../../config/database.php'; // nạp file kết nối DB
        global $pdo; // dùng biến toàn cục $pdo
        $this->pdo = $pdo;
    }

    public function all(): mixed {
        $stmt = $this->pdo->query("SELECT * FROM products ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    public function find($id): mixed {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Lấy sản phẩm mới nhất
    public function latest($limit = 4): mixed {
        $stmt = $this->pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}