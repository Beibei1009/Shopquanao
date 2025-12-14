<?php 
$banner = 'LILY & CO.';
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
    0% {
      transform: translateX(0);
    }

    33% {
      transform: translateX(-33.33%);
    }

    66% {
      transform: translateX(-66.66%);
    }

    100% {
      transform: translateX(0);
    }
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
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
  position: relative;
}

.card:hover {
  transform: translateY(-5px) scale(1.03);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
  border-color: #d32f2f; /* viền đỏ khi hover */
}

/* --- Ảnh sản phẩm --- */
.card .product-img-wrapper {
  position: relative;
  width: 100%;
  height: 230px;
  margin-bottom: 10px;
}

.card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 8px;
}

.card .out-of-stock-badge {
  position: absolute;
  top: 8px;
  right: 8px;
  background: #6c757d;
  color: white;
  padding: 4px 12px;
  border-radius: 4px;
  font-size: 13px;
  font-weight: 500;
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
<a href="/products">
<img src="/images/banner-fashion-1.jpg" alt="Banner 1" />
</a>
<a href="/products">
<img src="/images/banner-fashion-2.jpg" alt="Banner 2" />
</a>
<a href="/products">
<img src="/images/banner-fashion-3.jpg" alt="Banner 3" />
</a>
  </div>
  <div class="banner-text">
    <strong>SALE 30%</strong> - Mẫu mới mỗi tuần
  </div>
</div>
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-danger mb-0">
            <i class="bi bi-stars"></i> Sản phẩm mới
        </h2>
        <a href="/products" class="btn btn-outline-danger">
            Xem tất cả <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php
        $count = 0;
        foreach ($latest as $p):
            if ($count >= 8) break;
            $count++;
        ?>
            <div class="col">
                <div class="card h-100 shadow-sm product-card">
                    <a href="/products?action=detail&id=<?= $p['id'] ?>" class="text-decoration-none">
                        <div class="card-img-wrapper position-relative">
                            <img src="/uploads/<?= e($p['image']) ?>"
                                 class="card-img-top"
                                 alt="<?= e($p['name']) ?>"
                                 onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">
                        </div>
                        <div class="card-body text-center">
                            <h6 class="card-title text-dark mb-2"><?= e($p['name']) ?></h6>
                            <p class="card-text fw-bold text-danger mb-2">
                                <?= format_vnd($p['price']) ?>
                            </p>
                        </div>
                    </a>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="/products?action=detail&id=<?= $p['id'] ?>" class="btn btn-danger btn-sm w-100">
                            <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
}

.card-img-wrapper {
    overflow: hidden;
    height: 400px;
    background: #f8f9fa;
}

.card-img-top {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .card-img-top {
    transform: scale(1.05);
}

.card-title {
    font-size: 0.95rem;
    font-weight: 600;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.card-text {
    font-size: 1.1rem;
}

@media (max-width: 576px) {
    .card-img-wrapper {
        height: 200px;
    }
    .card-title {
        font-size: 0.85rem;
        min-height: 35px;
    }
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>