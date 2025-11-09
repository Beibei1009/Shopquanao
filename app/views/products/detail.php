<?php ob_start(); ?>
<div class="product-detail">
    <?php if (!empty($product)): ?>
        <div class="product-detail__image">
            <img src="/uploads/<?php echo htmlspecialchars($product['image']); ?>" 
                 alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="product-detail__info">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <p class="price"><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</p>
            <p class="desc"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            <a class="back" href="/products">← Quay lại danh sách</a>
           
<!-- Chọn size -->
<form method="post" action="/cart/add?id=<?= htmlspecialchars($product['id']) ?>">
    <label for="size">Chọn size:</label>
    <select name="size" id="size" required>
        <option value="">-- Chọn size --</option>
        <option value="S">Size S</option>
        <option value="M">Size M</option>
        <option value="L">Size L</option>
        <option value="XL">Size XL</option>
    </select>

    <br><br>
    <button type="submit" class="btn-add">🛒 Thêm vào giỏ hàng</button>
</form>

        </div>
    <?php else: ?>
        <p>Không tìm thấy sản phẩm.</p>
    <?php endif; ?>
</div>

<style>
.product-detail {
    display: flex;
    flex-wrap: wrap;
    gap: 40px;
    margin: 40px;
}

.product-detail__image {
    flex: 1 1 400px;
    text-align: center;
}

.product-detail__image img {
    width: 100%;
    max-width: 400px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.product-detail__info {
    flex: 1 1 400px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.product-detail__info h2 {
    font-size: 28px;
    margin-bottom: 10px;
}

.price {
    color: #009900;
    font-weight: bold;
    font-size: 22px;
    margin-bottom: 10px;
}

.desc {
    line-height: 1.6;
    margin-bottom: 15px;
}

.back {
    display: inline-block;
    padding: 10px 15px;
    background: #f5f5f5;
    border-radius: 6px;
    color: #333;
    text-decoration: none;
    transition: all 0.2s ease;
}

.back:hover {
    background: #ddd;
}

/* Chọn size */
label {
    font-weight: bold;
    margin-right: 10px;
}
select {
    padding: 6px 12px;
    border-radius: 5px;
    border: 1px solid #ccc;
    margin-top: 5px;
}

</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>