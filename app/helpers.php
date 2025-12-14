<?php
//File chứa các hàm tiện ích
//Escape html để phòng XSS 

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
//Tạo CSRF (Cross-Site Request Forgery) token
function csrf_token() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

//Kiểm tra CSRF token
function csrf_verify() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    //lấy token từ form
    $token = $_POST['csrf_token'] ?? '';

    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    //so sánh token lấy từ form và token trong session
    return hash_equals($_SESSION['csrf_token'], $token);
}
//Kiểm tra user có phải admin không
function is_admin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['user']) &&
           ($_SESSION['user']['role'] ?? 'user') === 'admin';
}

//Kiểm tra user đã đăng nhập chưa
function is_logged_in() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['user']);
}

//Yêu cầu user phải đăng nhập, Nếu chưa login → redirect về trang login
function require_login($redirect_to = '/auth/login') {
    if (!is_logged_in()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: ' . $redirect_to);
        exit;
    }
}

//Yêu cầu user phải là admin
function require_admin() {
    require_login(); //// Phải login trước
    if (!is_admin()) {
        http_response_code(403);
        die('Access Denied. Admin only.');
    }
}

//upload ảnh sản phẩm

function upload_image($file, $upload_dir = 'uploads') {
     // 1. Kiểm tra file có được upload không
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    //2. Kiểm tra kích thước file
    $maxSize = 5 * 1024 * 1024; // 5MB = 5 * 1024KB * 1024 bytes
    if ($file['size'] > $maxSize) {
        return ['error' => 'File quá lớn (max 5MB)'];
    }

    // 3. Kiểm tra định dạng file
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedMimes)) {
        return ['error' => 'Chỉ chấp nhận file ảnh (JPG, PNG, GIF, WEBP)'];
    }

    //4. Tạo tên file mới để tránh trùng lặp
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    // Dùng uniqid()tạo ID unique dựa vào timestamp
    $filename = uniqid() . '.' . $extension;

    //5. Tạo thư mục upload nếu chưa có
    $uploadDir = __DIR__ . '/../public/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);

    }

    //6. Di chuyển file từ thư mục tạm sang thư mục upload
        $destination = $uploadDir . $filename;
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['filename' => $filename];
    }
    return ['error' => 'Lỗi khi upload file'];
}

//Xóa ảnh sản phẩm
function delete_image($filename)
{
    if (empty($filename)) return;

    $path = __DIR__ . '/../public/uploads/' . $filename;

    // Kiểm tra file tồn tại trước khi xóa
    if (file_exists($path)) {
        unlink($path); // Xóa file
    }
}

// Format số tiền VND
function format_vnd($amount)
{
    return number_format($amount, 0, ',', '.') . 'đ';
}

//Format ngày tháng
function format_date($date)
{
    // strtotime() = chuyển string sang timestamp
    return date('d/m/Y', strtotime($date));
}

//Format ngày giờ
function format_datetime($datetime)
{
    return date('d/m/Y H:i', strtotime($datetime));
}

