<?php 
$banner = 'SHOP QUẦN ÁO THỜI TRANG';
ob_start();
?>

<style>
body {
  background: #f8f9fa;
  font-family: "Segoe UI", Arial, sans-serif;
  margin: 0;
  padding: 0;
}

/* --- Banner --- */
.banner{
  position: relative;
  width: 100%;
  height: 600px;          /* có thể 550–650 tuỳ ảnh */
  overflow: hidden;
  margin-bottom: 30px;
  border-bottom: 4px solid #d32f2f;
}

/* Container trượt */
.banner .slides{
  display: flex;
  width: 300%;
  height: 100%;
  animation: slide 12s infinite;
}

/* MỖI ảnh chiếm đúng 100% khung và phủ kín */
.banner .slides img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: 50% 40%; /* hạ ảnh xuống một chút */
  display: block;
  transform: scale(1.25);   /* vẫn giữ phóng ảnh để đầy khung */
}
/* Hiệu ứng chuyển ảnh */
@keyframes slide {
  0% { transform: translateX(0%); }
  33% { transform: translateX(-100%); }
  66% { transform: translateX(-200%); }
  100% { transform: translateX(0%); }
}

/* Chữ nổi trên banner */
.banner-text {
  position: absolute;
  left: 50%;
  bottom: 20px;
  transform: translateX(-50%);
  background: rgba(0, 0, 0, 0.6);
  color: #fff;
  padding: 10px 20px;
  border-radius: 6px;
  font-size: 18px;
  font-weight: 600;
  letter-spacing: 1px;
}

.banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center; /* căn giữa ảnh theo chiều dọc + ngang */
    display: block;
}
.banner-text {
    position: absolute;
    left: 50%;
    bottom: 20px;
    transform: translateX(-50%);
    background: rgba(0,0,0,0.5);
    color: #fff;
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 18px;
    font-weight: 600;
    letter-spacing: 1px;
}
/* --- Tiêu đề --- */
h2 {
  text-align: center;
  color: #d32f2f;
  text-transform: uppercase;
  margin: 30px 0 20px;
  letter-spacing: 1px;
}

/* --- Danh sách sản phẩm --- */
.product-list {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 25px;
  padding: 0 60px 60px;
  max-width: 1200px;
  margin: auto;
}

/* --- Thẻ sản phẩm --- */
.card {
  border: 1px solid #eee;
  border-radius: 10px;
  text-align: center;
  padding: 15px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
}

.card:hover {
  transform: translateY(-5px) scale(1.03);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
  border-color: #d32f2f; /* viền đỏ khi hover */
}

/* --- Ảnh sản phẩm --- */
.card img {
  width: 100%;
  height: 230px;
  object-fit: cover;
  border-radius: 8px;
  margin-bottom: 10px;
}

/* --- Tên sản phẩm --- */
.card .name {
  font-weight: 600;
  color: #333;
  margin: 5px 0;
  font-size: 16px;
}

/* --- Giá --- */
.card .price {
  color: #d32f2f;
  font-weight: bold;
  margin-bottom: 10px;
}

/* --- Nút mua --- */
.btn-buy {
  display: inline-block;
  background: #d32f2f;
  color: white;
  padding: 8px 15px;
  border-radius: 5px;
  text-decoration: none;
  font-weight: 500;
  transition: background 0.3s ease;
}

.btn-buy:hover {
  background: #b71c1c;
}
</style>

<!-- Banner -->
<div class="banner">
  <div class="slides">
<img src="/images/banner-fashion-1.jpg" alt="Banner 1" />
<img src="/images/banner-fashion-2.jpg" alt="Banner 2" />
<img src="/images/banner-fashion-3.jpg" alt="Banner 3" />
  </div>
  <div class="banner-text">
    <strong>SALE 30%</strong> - Mẫu mới mỗi tuần
  </div>
</div>
<h2>Sản phẩm mới</h2>

<div class="product-list">
  <?php foreach ($latest as $p): ?>
    <div class="card">
        <img src="/uploads/<?= htmlspecialchars($p['image']) ?>" 
             alt="<?= htmlspecialchars($p['name']) ?>">
        <div class="name"><?= htmlspecialchars($p['name']) ?></div>
        <div class="price"><?= htmlspecialchars(number_format($p['price'], 0, ',', '.')) ?>đ</div>
        <a href="/products?action=detail&id=<?= htmlspecialchars($p['id']) ?>" 
           class="btn-buy">Xem chi tiết</a>
    </div>
  <?php endforeach; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>