<?php
require_once __DIR__ . '/../../config/database.php';

class Category
{
    private $pdo;
    //Constructor - Khởi tạo kết nối database
    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }
    //Lấy tất cả danh mục
    public function all(): mixed
    {
        $stmt = $this->pdo->query("SELECT * FROM categories WHERE is_active = TRUE ORDER BY name ASC");
        return $stmt->fetchAll();
    }
    //Tìm category theo ID
    public function find($id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM categories WHERE id = ?"
        );

        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    //Tìm category theo slug
    public function findBySlug($slug)
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM categories WHERE slug = ?"
        );

        $stmt->execute([$slug]);

        return $stmt->fetch();
    }
    // Tạo category mới
    public function create($data): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO categories (name, slug, description, is_active)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['name'],
            $data['slug'],
            $data['description'] ?? null,
            $data['is_active'] ?? true
        ]);
    }
    //Cập nhật category

    public function update($id, $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE categories
            SET name = ?, slug = ?, description = ?, parent_id = ?, display_order = ?, is_active = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['name'],
            $data['slug'],
            $data['description'] ?? null,
            $data['parent_id'] ?? null,
            $data['display_order'] ?? 0,
            $data['is_active'] ?? true,
            $id
        ]);
    }

    //Xóa category

        public function delete($id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }

    //Đếm số sản phẩm trong category

        public function getProductCount($categoryId): int {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
        $stmt->execute([$categoryId]);
        return (int) $stmt->fetchColumn();
    }
}
