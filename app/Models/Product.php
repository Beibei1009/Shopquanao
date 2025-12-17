<?php
namespace App\Models;

class Product {
    private $pdo;

    public function __construct() {
        $this->pdo = \Database::getInstance();
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
    public function latest($limit = 8): mixed {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE (is_active = TRUE OR is_active IS NULL) ORDER BY id DESC LIMIT ?");
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Tìm kiếm và lọc sản phẩm với các tham số
     * @param array $params - Mảng chứa các tham số tìm kiếm
     *   - search: Tên sản phẩm cần tìm
     *   - category_id: ID danh mục
     *   - min_price: Giá tối thiểu
     *   - max_price: Giá tối đa
     *   - sort: Cách sắp xếp (newest, oldest, price_asc, price_desc)
     * @return array Danh sách sản phẩm
     */
    public function search($params = []): mixed {
        $sql = "SELECT p.*, c.name as category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE (p.is_active = TRUE OR p.is_active IS NULL)";

        $bindings = [];

        // Tìm kiếm theo tên (convert sang uppercase trong PHP để hỗ trợ tiếng Việt)
        if (!empty($params['search'])) {
            $sql .= " AND (UPPER(p.name) LIKE ? OR UPPER(p.description) LIKE ?)";
            $searchTerm = '%' . mb_strtoupper($params['search'], 'UTF-8') . '%';
            $bindings[] = $searchTerm;
            $bindings[] = $searchTerm;
        }

        // Lọc theo danh mục
        if (!empty($params['category_id'])) {
            $sql .= " AND p.category_id = ?";
            $bindings[] = $params['category_id'];
        }


        // Lọc theo khoảng giá
        if (!empty($params['min_price']) && is_numeric($params['min_price'])) {
            $sql .= " AND p.price >= ?";
            $bindings[] = $params['min_price'];
        }

        if (!empty($params['max_price']) && is_numeric($params['max_price'])) {
            $sql .= " AND p.price <= ?";
            $bindings[] = $params['max_price'];
        }

        // Sắp xếp
        $sort = $params['sort'] ?? 'newest';
        switch ($sort) {
            case 'oldest':
                $sql .= " ORDER BY p.created_at ASC";
                break;
            case 'price_asc':
                $sql .= " ORDER BY p.price ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY p.price DESC";
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY p.created_at DESC";
                break;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchAll();
    }

    //Trả về thông tin chi tiết của một sản phẩm với tên danh mục, dựa trên id sản phẩm
    public function getWithCategory($id): mixed {
        $stmt = $this->pdo->prepare("
            SELECT p.*, c.name as category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    //Trả về danh sách tất cả các sản phẩm với tên danh mục, sắp xếp theo thứ tự giảm dần của id.
    public function allWithCategory(): mixed {
        $stmt = $this->pdo->query("
            SELECT p.*, c.name as category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            ORDER BY p.id DESC
        ");
        return $stmt->fetchAll();
    }
}