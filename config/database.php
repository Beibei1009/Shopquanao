<?php
// Cấu hình thông tin kết nối
$host = '127.0.0.1';
$db   = 'ct275_project';
$user = 'postgres';
$pass = 'thao123';
$port = 5432;

// Biến $pdo toàn cục (chỉ tạo 1 lần)
static $pdo = null;

if ($pdo === null) {
    try {
        // Tạo chuỗi kết nối (DSN)
        $dsn = "pgsql:host=$host;port=$port;dbname=$db";

        // Thiết lập các tùy chọn cho PDO
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        // ✅ Tạo kết nối PDO đúng cú pháp
        $pdo = new PDO($dsn, $user, $pass, $options);

    } catch (PDOException $e) {
        die("❌ Lỗi kết nối CSDL: " . $e->getMessage());
    }
}