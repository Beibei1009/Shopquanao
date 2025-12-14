<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fashion Shop</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #fff;
    }
    header {
      background-color: #d32f2f; /* đỏ giống trang đăng nhập */
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 40px;
    }
    header h2 {
      margin: 0;
      font-weight: bold;
      font-size: 20px;
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
    main {
      padding: 25px;
    }
  </style>
</head>
<body>
  <header>
    <h2>FASHION SHOP</h2>
    <nav>
      <a href="/home">Trang chủ</a>
      <a href="/products">Sản phẩm</a>
      <a href="/about">Giới thiệu</a>
      <a href="/auth/login">Đăng nhập</a>
    </nav>
  </header>
  <main></main>