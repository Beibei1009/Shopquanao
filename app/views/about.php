<?php ob_start(); ?>
<h2 class="title">Giới thiệu</h2>
<p>Fashion Shop là cửa hàng quần áo thời trang Nam & Nữ, cập nhật mẫu mới mỗi tuần.
Sứ mệnh: mang đến sản phẩm chất lượng với mức giá hợp lý.</p>
<ul><li>Địa chỉ: 123 Trần Hưng Đạo, TP. Mỹ Tho</li><li>Giờ mở cửa: 9:00–21:00 (T2–CN)</li><li>Hotline: 0900 000 000</li></ul>
<?php $content=ob_get_clean(); include __DIR__.'/layout.php'; ?>