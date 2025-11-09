<?php
class AuthController {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO(
                "pgsql:host=localhost;dbname=ct275_project",
                "postgres",
                "thao123",
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            die('Kết nối database thất bại: ' . $e->getMessage());
        }
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email']
                ];
                header('Location: /');
                exit;
            } else {
                echo "<p style='color:red;'>Sai email hoặc mật khẩu</p>";
            }
        }
        include __DIR__ . '/../views/auth/login.php';
    }

    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['password'])) {
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $name = trim($lastname . ' ' . $firstname);
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Kiểm tra email trùng
            $check = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
            $check->execute([$email]);

            if ($check->fetch()) {
                echo "<p style='color:red;'>Email đã được sử dụng, vui lòng chọn email khác</p>";
            } else {
                $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $password]);
                echo "<p style='color:green;'>Đăng ký thành công! <a href='/auth/login'>Đăng nhập ngay</a></p>";
            }
        }

        include __DIR__ . '/../views/auth/register.php';
    }

    public function logout(): void {
        session_start();
        session_destroy();
        header('Location: /');
        exit;
    }
}
?>