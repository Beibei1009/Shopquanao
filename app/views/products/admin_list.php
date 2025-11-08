<?php ob_start(); ?>
<h2 class="title">Quản trị sản phẩm</h2>
<p><a class="btn" href="/admin/products?action=create">+ Thêm sản phẩm</a></p>
<table class="table"><thead><tr><th>ID</th><th>Tên</th><th>Giá</th><th>Ảnh</th><th>Hành động</th></tr></thead><tbody>
<?php foreach($items as $p): ?><tr><td><?= $p['id'] ?></td><td><?= htmlspecialchars($p['name']) ?></td>
<td><?= number_format($p['price'],0,',','.') ?>đ</td>
<td><img src="/uploads/<?= htmlspecialchars($p['image']) ?>" style="height:40px"></td>
<td><a class="btn small" href="/admin/products?action=edit&id=<?= $p['id'] ?>">Sửa</a>
<a class="btn small danger" href="/admin/products?action=delete&id=<?= $p['id'] ?>" onclick="return confirm('Xóa sản phẩm này?')">Xóa</a></td></tr><?php endforeach; ?>
</tbody></table><?php $content=ob_get_clean(); include __DIR__.'/../layout.php'; ?>