<?php

if (!class_exists('Database')) {
    class Database
    {
        //tạo 1 kết nối database duy nhất
        private static ?PDO $instance = null;

        // Database config - Neon PostgreSQL
        private static string $host = 'ep-steep-bar-ade1yc2b-pooler.c-2.us-east-1.aws.neon.tech';
        private static int $port = 5432;
        private static string $dbname = 'neondb';
        private static string $user = 'neondb_owner';
        private static string $password = 'npg_pjz6VnLaMh4o';

        private function __construct() {}
        private function __clone() {}

        public static function getInstance(): PDO
        {
            if (self::$instance === null) {
                try {
                    $dsn = sprintf(
                        "pgsql:host=%s;port=%d;dbname=%s;sslmode=require",
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
