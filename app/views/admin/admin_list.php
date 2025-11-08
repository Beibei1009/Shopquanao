<<?php ob_start(); ?>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        text-align: center;
        border: 1px solid #ddd;
        padding: 10px;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #fafafa;
    }

    img {
        border-radius: 6px;
    }

    a {
        color: #007bff;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>

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
        <td>
            <img src="/uploads/<?= htmlspecialchars($p['image']) ?>" width="80">
        </td>
        <td>
            <a href="/products?action=edit&id=<?= $p['id'] ?>">Sửa</a> |
            <a href="/products?action=delete&id=<?= $p['id'] ?>">Xóa</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php $content = ob_get_clean(); include __DIR__ . '/../layout.php'; ?>