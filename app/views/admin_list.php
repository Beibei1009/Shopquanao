<?php ob_start(); ?>
<h2>Danh sách sản phẩm (Admin)</h2>

<table border="1" cellspacing="0" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Giá</th>
        <th>Hình ảnh</th>
        <th>Hành động</th>
    </tr>
    <?php foreach ($products as $p): ?>
    <tr>
        <td><?= $p['id'] ?></td>
        <td><?= htmlspecialchars($p['name']) ?></td>
        <td><?= number_format($p['price'], 0, ',', '.') ?>đ</td>
        <td><img src="uploads/<?= htmlspecialchars($p['image']) ?>" width="80"></td>
        <td>
            <a href="/products?action=edit&id=<?= $p['id'] ?>">Sửa</a> |
            <a href="/products?action=delete&id=<?= $p['id'] ?>">Xóa</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>