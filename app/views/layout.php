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
    <title>LILY & CO.</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Toastify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        /* Smooth transitions for links */
        a {
            transition: all 0.3s ease;
        }

        /* Navbar custom styles */
        .navbar-brand {
            font-size: 32px;
            letter-spacing: 1px;
            margin-left: 50px;
        }

        .nav-link {
            font-weight: 500;
            color: #fff;
            margin-left: 24px;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        /* Footer link hover */
        footer a:hover {
            color: #fff !important;
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {
            .navbar-collapse {
                background-color: #c62828;
                padding: 15px;
                border-radius: 8px;
                margin-top: 10px;
            }
        }
    </style>
</head>

<body>

    <!-- HEADER - Responsive Bootstrap Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger sticky-top shadow-sm">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="/">
                LILY & CO.
            </a>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Content -->
            <!-- /collapse navbar-collapse: mặc định ẩn trên mobile -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Main Menu -->
                <!-- me-auto: marginend auto để đẩy phần còn lại sang phải -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/products">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">Giới thiệu</a>
                    </li>
                    <!-- Menu Admin chỉ hiện khi là admin -->
                    <?php if (is_admin()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-gear"></i> Admin
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/admin/products"><i class="bi bi-box-seam"></i> Sản phẩm</a></li>
                                <li><a class="dropdown-item" href="/admin/orders"><i class="bi bi-cart-check"></i> Đơn hàng</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>

                <!-- Khối bên phải navbar -->
                <div class="d-flex align-items-center gap-3">
                    <!-- Form tìm kiếm sản phẩm -->
                    <form action="/products" method="get" class="d-flex" role="search">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control" placeholder="Tìm sản phẩm...">
                            <button class="btn btn-light" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Giỏ hàng -->
                    <a href="/cart" class="btn btn-outline-light position-relative" title="Giỏ hàng">
                        <i class="bi bi-cart3"></i>
                        <?php
                        //Tính tổng số lượng trong giỏ hàng
                        $cartTotalQty = 0;
                        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $item) {
                                $cartTotalQty += is_array($item) ? ($item['quantity'] ?? 1) : 1;
                            }
                        }
                        //Hiện badge số lượng
                        if ($cartTotalQty > 0):
                        ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
                                <?= $cartTotalQty ?>
                            </span>
                        <?php endif; ?>
                    </a>

                    <!-- User Menu -->
                    <!-- Nếu user đã đăng nhập -->
                    <?php if (isset($_SESSION['user'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <!--  Hiển thị tên user đã đăng nhập với htmlspecialchars để tránh XSS -->
                                <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['user']['name']) ?>
                            </button>
                            <!-- Menu con -->
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/orders"><i class="bi bi-box-seam"></i> Đơn hàng của tôi</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="/auth/logout"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a></li>
                            </ul>
                        </div>
                        <!-- Nếu user chưa đăng nhập: -->
                    <?php else: ?>
                        <a href="/auth/login" class="btn btn-outline-light">
                            <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- NỘI DUNG TRANG -->
    <main>
        <?= $content ?? '' ?>
    </main>

    <!-- FOOTER - Responsive Bootstrap -->
    <footer class="bg-dark text-white mt-5">
        <div class="container py-4">
            <div class="row">
                <!-- About -->
                <div class="col-md-4 mb-3">
                    <h5 class="text-danger"><i class="bi bi-shop"></i> LILY & CO.</h5>
                    <p class="text-whitesmall">
                        Phong cách - Chất lượng - Uy tín<br>
                        Mang đến những sản phẩm thời trang đẹp nhất cho bạn.
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="col-md-4 mb-3">
                    <h6 class="text-danger">Liên kết</h6>
                    <ul class="list-unstyled">
                        <li><a href="/products" class="text-white text-decoration-none small"></i> Sản phẩm</a></li>
                        <li><a href="/about" class="text-white text-decoration-none small"></i> Giới thiệu</a></li>
                        <li><a href="/cart" class="text-white text-decoration-none small"></i> Giỏ hàng</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-md-4 mb-3">
                    <h6 class="text-danger">Liên hệ</h6>
                    <p class="text-whitesmall mb-1">
                        <i class="bi bi-geo-alt-fill"></i> 123 Đường ABC, Quận 1, TP.HCM
                    </p>
                    <p class="text-whitesmall mb-1">
                        <i class="bi bi-telephone-fill"></i> 0123 456 789
                    </p>
                    <p class="text-whitesmall mb-1">
                        <i class="bi bi-envelope-fill"></i> shop@fashion.com
                    </p>
                </div>
            </div>

            <hr class="border-secondary">

            <div class="text-center">
                <small class="text-muted">© 2025 LILY & CO. All rights reserved.</small>
            </div>
        </div>
    </footer>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Toastify JS -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <!-- Flash Messages với Toastify -->
    <!-- //Hiển thị thông báo dạng Toast bằng Toastify -->
    <!-- // Hiển thị thông báo thành công -->
    <!-- Kiểm tra trong $_SESSION có key flash_success không -->
    <?php if (isset($_SESSION['flash_success'])): ?>
        <script>
            // Gọi Toastify để show toast
            Toastify({
                // dùng json_encode , k echo thẳng vào JS để tránh lỗi XSS,
                // đặc biệt khi thông báo có chứa ký tự đặc biệt như dấu nháy đơn, dấu ngoặc kép, dấu <, >, &
                text: <?= json_encode($_SESSION['flash_success'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
                duration: 3000,
                gravity: "top",
                position: "right",
                style: {
                    background: "linear-gradient(to right, #00b09b, #96c93d)",
                }
            }).showToast();
        </script>
        <!-- Xóa flash sau khi hiển thị -->
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>
    <!-- //Hiển thị flash error -->
    <?php if (isset($_SESSION['flash_error'])): ?>
        <script>
            // Lưu thông báo error vào biến JS, cũng thông qua json_encode + flags để chống XSS
            const errorMsg = <?= json_encode($_SESSION['flash_error'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

            Toastify({
                text: errorMsg,
                duration: 10000,
                gravity: "top",
                position: "right",
                style: {
                    background: "linear-gradient(to right, #ff5f6d, #ffc371)",
                }
            }).showToast();

            // lỗi checkout thì hiện alert chi tiết hơn
            //strpos Kiểm tra xem trong message lỗi có chứa từ "Giỏ hàng" không.
            <?php if (
                strpos($_SESSION['flash_error'], 'Giỏ hàng') !== false ||
                strpos($_SESSION['flash_error'], 'CSRF') !== false ||
                strpos($_SESSION['flash_error'], 'không hợp lệ') !== false
            ): ?>
                alert("LỖI CHECKOUT:\n\n" + errorMsg);
            <?php endif; ?>
        </script>
        <!-- //Xóa flash error sau khi dùng -->
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>
</body>

</html>