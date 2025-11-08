<?php
ob_start();
?>
<div style="text-align:center; padding:50px;">
    <h2>🎉 Thanh toán thành công!</h2>
    <p>Cảm ơn bạn đã mua sắm tại <strong>Fashion Shop</strong>.</p>
    <p>Chúng tôi sẽ liên hệ với bạn để xác nhận đơn hàng sớm nhất có thể.</p>
    <a href="/products" style="
        display:inline-block;
        margin-top:20px;
        padding:10px 20px;
        background:#f44336;
        color:white;
        border-radius:6px;
        text-decoration:none;
        transition:0.2s;">
        🛍️ Tiếp tục mua sắm
    </a>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>