<?php ob_start(); ?>

<h2>Danh sách sản phẩm</h2>

<div class="product-grid">
    <?php foreach ($products as $p): ?>
        <div class="product-card">
            <a href="/products?action=detail&id=<?php echo $p['id']; ?>">
                <img src="uploads/<?= htmlspecialchars($p['image']) ?>"
     alt="<?= htmlspecialchars($p['name']) ?>">
            </a>
            <h3><?php echo htmlspecialchars($p['name']); ?></h3>
            <p class="price"><?php echo number_format($p['price'], 0, ',', '.'); ?>đ</p>
        </div>
    <?php endforeach; ?>
</div>

<style>
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
.product-card {
    text-align: center;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 10px;
    background: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.product-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 10px;
}
.price {
    color: #009900;
    font-weight: bold;
    margin-top: 5px;
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>