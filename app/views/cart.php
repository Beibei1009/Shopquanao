<?php
ob_start();
?>
<style>
.btn-checkout {
    background: #28a745;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    transition: 0.2s;
}
.btn-checkout:hover {
    background: #218838;
}
</style>

<h2>🛒 Giỏ hàng của bạn</h2>

<?php
// Kiểm tra xem giỏ hàng có dữ liệu chưa
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0):
?>
    <p>Giỏ hàng của bạn đang trống.</p>
    <p><a href="/products">Tiếp tục mua sắm</a></p>
<?php
else:
?>
    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 80%;">
        <tr style="background-color: #f44336; color: white;">
            <th>Sản phẩm</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Thành tiền</th>
            <th>Xóa</th>
        </tr>
        <?php
        $total = 0;
        foreach ($_SESSION['cart'] as $id => $item):
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
        ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($item['price']) ?>đ</td>
                <td><?= number_format($subtotal) ?>đ</td>
                <td><a href="/cart/remove?id=<?= $id ?>">X</a></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3" align="right"><strong>Tổng cộng:</strong></td>
            <td colspan="2"><strong><?= number_format($total) ?>đ</strong></td>
        </tr>
    </table>

    <p><a href="/products">🛍 Tiếp tục mua hàng</a></p>
<?php endif; ?>
<form action="/cart/checkout" method="post" style="margin-top:20px;">
    <button type="submit" class="btn-checkout">Thanh toán</button>
</form>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';