<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Quần Áo Thời Trang</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #fff;
        }

        /* --- HEADER --- */
        header {
    background-color: #d32f2f; /* đỏ như trang đăng nhập */
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 30px;
    flex-wrap: wrap;
    position: sticky;
    top: 0;
    z-index: 1000;
}

        .logo h2 {
        margin: 0;
        font-weight: bold;
        font-size: 22px;
        }

        .logo a {
        color: white;
        text-decoration: none;
        letter-spacing: 1px;
        text-transform: uppercase;
        }

        .logo a:hover {
        color: #ffcccc;
        }
        /* --- Căn chỉnh menu điều hướng --- */
        nav {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px; /* khoảng cách giữa các mục menu */
        }

        nav a {
        color: white;
        text-decoration: none;
        font-weight: 500;
        padding: 8px 12px;
        border-radius: 4px;
        transition: background-color 0.3s ease;
        }

        nav a:hover {
        background-color: #b71c1c; /* màu đỏ đậm hơn khi rê chuột */
        text-decoration: none;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-weight: 500;
        }

        nav a:hover {
            text-decoration: underline;
        }

        /* --- TÌM KIẾM VÀ GIỎ HÀNG --- */
        .actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .search-box {
            display: flex;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            height: 30px;
        }

        .search-box input {
            border: none;
            outline: none;
            padding: 5px 10px;
            font-size: 14px;
        }

        .search-box button {
            border: none;
            background: #f44336;
            color: white;
            padding: 0 10px;
            cursor: pointer;
        }

        .search-box button:hover {
            background: #b71c1c;
        }

        .cart-link {
            font-size: 20px;
            text-decoration: none;
            color: white;
        }

        .cart-link:hover {
            opacity: 0.8;
        }

        main {
            padding: 25px;
        }

        footer {
            background-color: #f5f5f5;
            text-align: center;
            padding: 15px;
            margin-top: 30px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header>
  <div class="logo">
    <h2><a href="/">SHOP QUẦN ÁO THỜI TRANG</a></h2>
  </div>
        <nav>
            <a href="/">Trang chủ</a>
            <a href="/products">Sản phẩm</a>
            <a href="/about">Giới thiệu</a>
            <a href="/contact">Liên hệ</a>
        </nav>

        <div class="actions">
            <!-- Ô tìm kiếm -->
            <form action="/search" method="get" style="display: inline;">
  <input type="text" name="q" placeholder="Tìm sản phẩm..." required>
  <button type="submit">🔍</button>
</form>

            <!-- Giỏ hàng -->
             <a href="/cart" class="cart-link" title="Giỏ hàng">🛒</a>

            <!-- Đăng nhập / Đăng xuất -->
            <?php if (isset($_SESSION['user'])): ?>
                <a href="/auth/logout">Đăng xuất (<?= htmlspecialchars($_SESSION['user']['name']) ?>)</a>
            <?php else: ?>
                <a href="/auth/login">Đăng nhập</a>
            <?php endif; ?>
        </div>
    </header>

    <!-- NỘI DUNG TRANG -->
    <main>
        <?= $content ?? '' ?>
    </main>

    <!-- FOOTER -->
    <footer>
        <p>© 2025 Shop Quần Áo Thời Trang • Phong cách • Chất lượng • Uy tín</p>
    </footer>

</body>
</html>