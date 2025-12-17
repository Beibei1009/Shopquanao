<?php
namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;

class ProductController {
    // Hiển thị danh sách sản phẩm
    public function index(): void {
        $productModel = new Product();

        // Lấy tham số tìm kiếm và lọc từ URL
        $params = [
            'search' => $_GET['search'] ?? '',
            'category_id' => $_GET['category'] ?? '',
            'min_price' => $_GET['min_price'] ?? '',
            'max_price' => $_GET['max_price'] ?? '',
            'sort' => $_GET['sort'] ?? 'newest'
        ];

        // Sử dụng phương thức search nếu có bất kỳ filter nào
        if (!empty($params['search']) || !empty($params['category_id']) ||
            !empty($params['min_price']) || !empty($params['max_price'])) {
            $products = $productModel->search($params);
        } else {
            // Nếu không có filter, vẫn dùng search để có sort
            $products = $productModel->search($params);
        }

        // Lấy danh sách categories cho filter
        $categoryModel = new Category();
        $categories = $categoryModel->all();

        include __DIR__ . '/../views/products/list.php';
    }

    // Hiển thị chi tiết sản phẩm
    public function detail(): void {
        if (!isset($_GET['id'])) {
            die('Thiếu ID sản phẩm');
        }

        $productModel = new Product();
        $product = $productModel->find($_GET['id']);

        if (!$product) {
            http_response_code(404);
            die('Sản phẩm không tồn tại');
        }

        // Không cần load size stocks - mặc định tất cả size đều có sẵn

        include __DIR__ . '/../views/products/detail.php';
    }

    // Xử lý tìm kiếm từ search box header
    public function search(): void {
        $query = $_GET['q'] ?? '';

        // Redirect đến trang products với tham số search
        header('Location: /products?search=' . urlencode($query));
        exit;
    }

    // Trang quản trị sản phẩm (chỉ admin)
    public function adminlist(): void {
        require_admin(); // Check quyền admin

        $pdo = \Database::getInstance();

        // Get filter parameters
        $search = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        // Build query with filters
        $where = ['p.is_active = TRUE'];
        $params = [];

        if ($search) {
            $where[] = "(p.name ILIKE ? OR p.description ILIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if ($category) {
            $where[] = "p.category_id = ?";
            $params[] = $category;
        }

        $whereClause = implode(' AND ', $where);

        // Get total count
        $countSql = "SELECT COUNT(*) FROM products p WHERE $whereClause";
        $stmt = $pdo->prepare($countSql);
        $stmt->execute($params);
        $totalProducts = $stmt->fetchColumn();
        $totalPages = ceil($totalProducts / $perPage);

        // Get products with pagination
        $sql = "SELECT p.*, c.name as category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE $whereClause
                ORDER BY p.created_at DESC
                LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Get categories for filter
        $categoryModel = new Category();
        $categories = $categoryModel->all();

        // Get all products for statistics
        $allProductsStmt = $pdo->query("SELECT price FROM products WHERE is_active = TRUE");
        $allProducts = $allProductsStmt->fetchAll(\PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/admin/admin_list.php';
    }

    // Tạo sản phẩm mới
    public function create(): void {
        require_admin();

        // Lấy danh sách categories
        $categoryModel = new Category();
        $categories = $categoryModel->all();

        $error = null;
        $success = null;
        //Kiểm tra tính hợp lệ của token CSRF (Cross-Site Request Forgery)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_verify()) {
                $error = 'CSRF token không hợp lệ. Vui lòng tải lại trang và thử lại.';
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {

            //lấy dữ liệu từ form
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $category_id = $_POST['category_id'] ?? null;

            // Upload ảnh
            $imageName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                //Gọi hàm upload_image để xử lý và lưu ảnh
                $uploadResult = upload_image($_FILES['image']);
                if (isset($uploadResult['error'])) {
                    $error = $uploadResult['error'];
                } else {
                    $imageName = $uploadResult['filename'];
                }
            }

            if (!$error && $name && $price) {
                if (session_status() === PHP_SESSION_NONE)
                    session_start();

                $pdo = \Database::getInstance();

                // Tạo slug từ name
                $baseSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
                $slug = $baseSlug;

                // Kiểm tra tính duy nhất của slug
                $counter = 1;
                while (true) {
                    //kiểm tra xem slug đã tồn tại trong cơ sở dữ liệu chưa
                    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE slug = ?");
                    $checkStmt->execute([$slug]);
                    // Lấy kết quả kiểm tra, nếu tồn tại thì tăng counter
                    //và tạo slug mới
                    $exists = $checkStmt->fetchColumn();

                    if (!$exists) {
                        break; //slug này là duy nhất, thoát khỏi vòng lặp
                    }

                    // Tạo slug mới với counter và tăng counter lên
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                //Chèn sản phẩm vào cơ sở dữ liệu
                try {
                    $pdo->beginTransaction();

                    $stmt = $pdo->prepare("INSERT INTO products (name, slug, description, price, image, category_id) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$name, $slug, $description, $price, $imageName, $category_id]);

                    //Cam kết giao dịch và chuyển hướng
                    //xác nhận rằng tất cả các thay đổi (chèn sản phẩm, cập nhật size)
                    //đã được thực hiện thành công
                    $pdo->commit();
                    //hiển thị thông báo thành công và chuyển hướng
                    //về danh sách sản phẩm
                    $_SESSION['flash_success'] = 'Thêm sản phẩm thành công! Đang chuyển đến danh sách...';
                    header('Location: /admin/products');
                    exit;

                    //Xử lý lỗi
                } catch (\Exception $e) {
                    //rollBack() sẽ được gọi để hủy bỏ giao dịch nếu có lỗi xảy ra
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    $error = 'Lỗi khi thêm sản phẩm: ' . $e->getMessage();
                }
            }
        }

        include __DIR__ . '/../views/products/admin_form.php';
    }

    // Sửa sản phẩm
    public function edit(): void {
        require_admin();

        // Lấy thông tin sản phẩm cần sửa
        $id = $_GET['id'] ?? null;
        // Kiểm tra nếu không có ID thì dừng lại
        if (!$id) {
            die('Missing product ID');
        }

        // Lấy thông tin sản phẩm từ database
        $productModel = new Product();
        $product = $productModel->find($id);

        if (!$product) {
            die('Product not found');
        }

        // Lấy danh sách categories
        $categoryModel = new Category();
        $categories = $categoryModel->all();

        $error = null;
        $success = null;

        //Kiểm tra tính hợp lệ của token CSRF (Cross-Site Request Forgery)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {

            // /Lấy dữ liệu từ form
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $category_id = $_POST['category_id'] ?? null;
            $imageName = $product['image']; // Giữ ảnh cũ


            // Upload ảnh mới nếu có
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = upload_image($_FILES['image']);
                if (isset($uploadResult['error'])) {
                    $error = $uploadResult['error'];
                } else {
                    // Xóa ảnh cũ
                    delete_image($product['image']);
                    $imageName = $uploadResult['filename'];
                }
            }
            // Cập nhật sản phẩm nếu không có lỗi
            if (!$error && $name && $price) {
                if (session_status() === PHP_SESSION_NONE)
                    session_start();

                $pdo = \Database::getInstance();

                // Tạo slug từ name
                $baseSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
                $slug = $baseSlug;

                // Kiểm tra xem slug đã tồn tại chưa
                $counter = 1;
                while (true) {
                    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE slug = ? AND id != ?");
                    $checkStmt->execute([$slug, $id]);
                    $exists = $checkStmt->fetchColumn();

                    if (!$exists) {
                        break; // Slug này là duy nhất, thoát khỏi vòng lặp
                    }

                    // Tạo slug mới với counter và tăng counter lên
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                // Cập nhật sản phẩm trong database
                try {
                    $pdo->beginTransaction();

                    $stmt = $pdo->prepare("UPDATE products SET name = ?, slug = ?, description = ?, price = ?, image = ?, category_id = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                    $stmt->execute([$name, $slug, $description, $price, $imageName, $category_id, $id]);

                    // Cam kết giao dịch
                    $pdo->commit();
                    // Hiển thị thông báo thành công và 
                    //chuyển hướng về danh sách sản phẩm
                    $_SESSION['flash_success'] = 'Cập nhật sản phẩm thành công!';
                    header('Location: /admin/products');
                    exit;
                } catch (\Exception $e) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    $error = 'Lỗi khi cập nhật sản phẩm: ' . $e->getMessage();
                }
            }
        }
            
            // Hiển thị form sửa sản phẩm
        include __DIR__ . '/../views/products/admin_form.php';
    }

    // Xóa sản phẩm
    public function delete(): void {
        require_admin();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            die('Missing product ID');
        }

        $productModel = new Product();
        $product = $productModel->find($id);

        if ($product) {
            // Xóa ảnh
            delete_image($product['image']);

            // Xóa bản ghi khỏi cơ sở dữ liệu
            $pdo = \Database::getInstance();
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
        }
        //Chuyển hướng người dùng về trang danh sách sản phẩm quản trị
        header('Location: /admin/products');
        exit;
    }
}
