<?php

if (!class_exists('Database')) {
    class Database
    {
        //tạo 1 kết nối database duy nhất
        private static ?PDO $instance = null;

        // Database config
        private static string $host = '127.0.0.1';
        private static int $port = 5433;
        private static string $dbname = 'CT275_Project';
        private static string $user = 'postgres';
        private static string $password = '123456';

        private function __construct() {}
        private function __clone() {}

        public static function getInstance(): PDO
        {
            if (self::$instance === null) {
                try {
                    $dsn = sprintf(
                        "pgsql:host=%s;port=%d;dbname=%s",
                        self::$host,
                        self::$port,
                        self::$dbname
                    );

                    $options = [
                        //THROW LỖI KHI CÓ VẤN ĐỀ KẾT NỐI
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        //chống SQL injection
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ];

                    self::$instance = new PDO($dsn, self::$user, self::$password, $options);

                } catch (PDOException $e) {
                    die("❌ Lỗi kết nối database: " . $e->getMessage() .
                        "\n📍 Kiểm tra: host=" . self::$host .
                        ", port=" . self::$port .
                        ", dbname=" . self::$dbname);
                }
            }

            return self::$instance;
        }

        public static function configure(string $host, int $port, string $dbname, string $user, string $password): void
        {
            self::$host = $host;
            self::$port = $port;
            self::$dbname = $dbname;
            self::$user = $user;
            self::$password = $password;
        }
    }
}

//Không cần require lại nhiều lần
if (!isset($pdo)) {
    $pdo = Database::getInstance();
}
?>
