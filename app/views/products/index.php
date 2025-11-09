<?php ob_start(); ?>
<h2 class="title">Danh sách sản phẩm</h2><div class="grid">
<?php foreach($items as $p): ?><a class="card" href="/product?id=<?= $p['id'] ?>">
<img src="/uploads/<?= htmlspecialchars($p['image']) ?>" alt=""><div class="card-body"><div class="name"><?= htmlspecialchars($p['name']) ?></div><div class="price"><?= number_format($p['price'],0,',','.') ?>đ</div></div></a>
<?php endforeach; ?></div><?php $content=ob_get_clean(); include __DIR__.'/../layout.php'; ?>