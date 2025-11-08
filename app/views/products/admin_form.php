<?php $editing=isset($item); ob_start(); ?>
<h2 class="title"><?= $editing?'Sửa':'Thêm' ?> sản phẩm</h2>
<form class="form" method="post" action="/admin/products?action=<?= $editing?'edit':'create' ?>">
<?php if($editing): ?><input type="hidden" name="id" value="<?= $item['id'] ?>"><?php endif; ?>
<label>Tên sản phẩm<input name="name" required value="<?= htmlspecialchars($item['name']??'') ?>"></label>
<label>Giá<input type="number" name="price" step="1000" required value="<?= htmlspecialchars($item['price']??'0') ?>"></label>
<label>Ảnh (tên file trong /public/uploads)<input name="image" required value="<?= htmlspecialchars($item['image']??'') ?>"></label>
<label>Mô tả<textarea name="description" rows="4"><?= htmlspecialchars($item['description']??'') ?></textarea></label>
<button class="btn" type="submit">Lưu</button> <a class="btn" href="/admin/products">Hủy</a></form>
<?php $content=ob_get_clean(); include __DIR__.'/../layout.php'; ?>