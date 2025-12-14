<?php
class AuthController
{
    private $pdo;

    public function __construct()
    {
        require_once __DIR__ . '/../../config/database.php';
        $this->pdo = Database::getInstance();
    }



    public function login(): void
    {
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
                    'email' => $user['email'],
                    'role' => $user['role'] ?? 'user',
                    'phone' => $user['phone'] ?? null,
                    'address' => $user['address'] ?? null
                ];
                header('Location: /');
                exit;
            } else {
                echo "<p style='color:red;'>Sai email hoặc mật khẩu</p>";
            }
        }
        include __DIR__ . '/../views/auth/login.php';
    }

    public function register(): void
    {
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
                $_SESSION['flash_error'] = 'Email đã được sử dụng, vui lòng chọn email khác';
            } else {
                $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $password]);

                $_SESSION['flash_success'] = 'Đăng ký thành công! Đang chuyển đến trang đăng nhập...';
                echo '<script>
                    setTimeout(function() {
                        window.location.href = "/auth/login";
                    }, 2000);
                </script>';
                include __DIR__ . '/../views/auth/register.php';
                return;
            }
        }

        include __DIR__ . '/../views/auth/register.php';
    }

    public function logout(): void
    {
        session_start();
        //Hủy bỏ toàn bộ session, xóa tất cả dữ liệu session hiện tại
        session_destroy();
        header('Location: /');
        exit;
    }
}
