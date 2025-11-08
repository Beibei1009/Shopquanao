<?php
ob_start();
?>
<style>
img {
  border-radius: 10px;
  box-shadow: 0 0 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s ease;
}

img:hover {
  transform: scale(1.05);
}
</style>

<h2>Kết quả tìm kiếm cho: <em>"<?= htmlspecialchars($_GET['q'] ?? '') ?>"</em></h2>

<?php if (empty($products)): ?>
    <p>Không tìm thấy sản phẩm nào phù hợp.</p>
<?php else: ?>
    <div style="display: flex; flex-wrap: wrap; gap: 20px;">
        <?php foreach ($products as $p): ?>
            <div style="width: 180px; text-align: center; border: 1px solid #ddd; padding: 10px;">
                <img src="/uploads/<?= htmlspecialchars($p['image']) ?>" width="160" height="160" alt="">
                <p><strong><?= htmlspecialchars($p['name']) ?></strong></p>
                <p><?= number_format($p['price']) ?>đ</p>
                <a href="/products?action=detail&id=<?= $p['id'] ?>">Xem chi tiết</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';