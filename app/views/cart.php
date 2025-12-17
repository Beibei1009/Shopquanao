<?php ob_start(); ?>

<div class="container my-4">
  <h2 class="text-center text-danger mb-3 mb-md-4 fs-5 fs-md-4 fs-lg-3">
    <i class="bi bi-cart3"></i> GIỎ HÀNG CỦA BẠN
  </h2>
  <!-- Hiển thị Giỏ Hàng Trống -->
  <?php if (empty($cart)): ?>
    <div class="alert alert-info text-center">
      <i class="bi bi-cart-x fs-1 d-block mb-3"></i>
      <h5>Giỏ hàng trống</h5>
      <p class="mb-0">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
      <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
        <div class="alert alert-warning mt-3">
          <strong>Debug:</strong> Session cart có <?= count($_SESSION['cart']) ?> items nhưng không load được từ database.
          Các sản phẩm có thể không tồn tại hoặc không active.
        </div>
      <?php endif; ?>
      <!-- //Nút đưa người dùng trở lại trang sản phẩm. -->
      <a href="/products" class="btn btn-danger mt-3">
        <i class="bi bi-bag"></i> Mua sắm ngay
      </a>
    </div>
  <?php else: ?>
    <div class="row">
      <!-- Hiển thị Giỏ Hàng và Các Sản Phẩm -->
      <div class="col-lg-8 order-2 order-lg-1">
        <div class="card shadow-sm">
          <div class="card-body p-2 p-md-3">
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <!-- Giỏ hàng được hiển thị dưới dạng bảng -->
                <thead class="table-danger">
                  <tr>
                    <th class="d-none d-md-table-cell" style="min-width: 80px;"><small>Sản phẩm</small></th>
                    <th class="ps-2 ps-md-3" style="min-width: 120px;"><small>Tên</small></th>
                    <th class="text-center" style="min-width: 100px;"><small>Số lượng</small></th>
                    <th class="text-end d-none d-sm-table-cell" style="min-width: 80px;"><small>Giá</small></th>
                    <th class="text-end pe-2 pe-md-3" style="min-width: 80px;"><small>Tổng</small></th>
                    <th class="text-center" style="min-width: 45px;"><small>Xóa</small></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Hiển thị Các Sản Phẩm trong Giỏ Hàng: Hình ảnh, tên, số lượng, giá, tổng 
                  // cộng, xoá
                  $total = 0;
                  foreach ($cart as $id => $item):
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                  ?>
                  <tr data-product-id="<?= $id ?>">
                    <td class="d-none d-md-table-cell">
                      <img src="/uploads/<?= e($item['image']) ?>"
                           class="img-thumbnail"
                           style="width: 60px; height: 60px; object-fit: cover;"
                           alt="<?= e($item['name']) ?>">
                    </td>
                    <td class="ps-2 ps-md-3">
                      <div class="d-flex align-items-start gap-2">
                        <!-- Ảnh trên mobile -->
                        <img src="/uploads/<?= e($item['image']) ?>"
                             class="img-thumbnail d-md-none"
                             style="width: 45px; height: 45px; object-fit: cover; flex-shrink: 0;"
                             alt="<?= e($item['name']) ?>">
                        <div class="d-flex flex-column">
                          <strong style="font-size: 0.8rem;"><?= e($item['name']) ?></strong>
                          <?php if (!empty($item['size'])): ?>
                            <span class="badge bg-secondary mt-1" style="font-size: 0.6rem; width: fit-content;">Size: <?= e($item['size']) ?></span>
                          <?php endif; ?>
                          <!-- Hiển thị giá trên mobile -->
                          <div class="d-sm-none mt-1">
                            <small class="text-danger fw-bold" style="font-size: 0.7rem;">
                              Giá: <?= format_vnd($item['price']) ?>
                            </small>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="text-center px-1">
                      <div class="input-group input-group-sm" style="max-width: 95px; margin: 0 auto;">
                        <button class="btn btn-outline-secondary btn-decrease" type="button" data-id="<?= $id ?>" style="padding: 0.2rem 0.3rem; font-size: 0.75rem;">
                          <i class="bi bi-dash"></i>
                        </button>
                        <input type="number"
                               class="form-control form-control-sm text-center quantity-input"
                               value="<?= $item['quantity'] ?>"
                               min="1"
                               data-id="<?= $id ?>"
                               style="font-size: 0.8rem; padding: 0.2rem 0.1rem;"
                               readonly>
                        <button class="btn btn-outline-secondary btn-increase" type="button" data-id="<?= $id ?>" style="padding: 0.2rem 0.3rem; font-size: 0.75rem;">
                          <i class="bi bi-plus"></i>
                        </button>
                      </div>
                    </td>
                    <td class="text-end text-danger fw-bold d-none d-sm-table-cell">
                      <small><?= format_vnd($item['price']) ?></small>
                    </td>
                    <td class="text-end fw-bold subtotal pe-2 pe-md-3">
                      <small class="text-danger"><?= format_vnd($subtotal) ?></small>
                    </td>
                    <td class="text-center px-1">
                      <a href="/cart/remove?id=<?= $id ?>"
                         class="btn btn-sm btn-outline-danger"
                         onclick="return confirm('Xóa sản phẩm này?')"
                         style="padding: 0.2rem 0.35rem; font-size: 0.75rem;">
                        <i class="bi bi-trash"></i>
                      </a>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <!-- Tiếp tục mua sắm -->
            <div class="mt-2 mt-md-3">
              <a href="/products" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> <span class="d-none d-sm-inline">Tiếp tục mua sắm</span><span class="d-inline d-sm-none">Mua thêm</span>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- tóm tắt đơn hàng -->
      <div class="col-lg-4 order-1 order-lg-2 mb-3 mb-lg-0">
        <div class="card shadow-sm sticky-lg-top" style="top: 20px;">
          <div class="card-header bg-danger text-white">
            <h5 class="mb-0 fs-6 fs-md-5"><i class="bi bi-receipt"></i> Tóm tắt đơn hàng</h5>
          </div>
          <div class="card-body p-2 p-md-3">
            <div class="d-flex justify-content-between mb-2">
              <small>Tạm tính:</small>
              <small class="fw-bold" id="cart-subtotal"><?= format_vnd($total) ?></small>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <small>Phí vận chuyển:</small>
              <small class="text-success">Miễn phí</small>
            </div>
            <hr class="my-2">
            <div class="d-flex justify-content-between mb-2 mb-md-3">
              <span class="fw-bold small">Tổng cộng:</span>
              <span class="fw-bold text-danger fs-6 fs-md-5" id="cart-total"><?= format_vnd($total) ?></span>
            </div>

            <div class="d-grid gap-2">
              <a href="/checkout-form" class="btn btn-danger">
                <i class="bi bi-credit-card"></i> Thanh toán COD
              </a>
            </div>

            <div class="mt-2 mt-md-3 text-center">
              <small class="text-muted" style="font-size: 0.75rem;">
                <i class="bi bi-truck"></i> Thanh toán khi nhận hàng (COD)
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<!-- Modal removed - now using /checkout-form page instead -->
<!-- Cập nhật số lượng trong giỏ hàng (AJAX 
AJAX update quantity: Khi người dùng tăng hoặc giảm số lượng sản phẩm trong giỏ hàng,
sẽ gửi POST request đến /cart/update để cập nhật số lượng và 
tổng giỏ hàng mà không cần tải lại trang.-->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get nút tăng giảm - cho phép người dùng thay đổi số lượng sản phẩm trong giỏ hàng
    const increaseButtons = document.querySelectorAll('.btn-increase');
    const decreaseButtons = document.querySelectorAll('.btn-decrease');

    // Xử lý sự kiện khi nhấn nút tăng số lượng
    increaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
            const currentQty = parseInt(input.value);
            // Gọi hàm updateQuantity để tăng số lượng lên 1.
            updateQuantity(id, currentQty + 1);
        });
    });

    // Xử lý sự kiện khi nhấn nút tăng số lượng
    decreaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
            const currentQty = parseInt(input.value);
          // số lượng không giảm xuống dưới 1
            if (currentQty > 1) {
                updateQuantity(id, currentQty - 1);
            }
        });
    });

    // Hàm AJAX cập nhật số lượng
    function updateQuantity(id, quantity) {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('quantity', quantity);

        fetch('/cart/update', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cập nhật số lượng trong ô input.
                const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
                input.value = quantity;

                // Cập nhật subtotal cho từng sản phẩm và tổng giỏ hàng
                const row = input.closest('tr');
                const subtotalCell = row.querySelector('.subtotal');
                // Giữ nguyên cấu trúc HTML với thẻ small
                const smallTag = subtotalCell.querySelector('small');
                if (smallTag) {
                    smallTag.textContent = formatVND(data.subtotal);
                } else {
                    subtotalCell.innerHTML = `<small class="text-danger">${formatVND(data.subtotal)}</small>`;
                }

                // Cập nhậttổng giỏ hàng
                document.getElementById('cart-subtotal').textContent = formatVND(data.total);
                document.getElementById('cart-total').textContent = formatVND(data.total);

                console.log('Cart updated successfully');
            } else {
                alert(data.error || 'Không thể cập nhật số lượng');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Lỗi khi cập nhật giỏ hàng');
            location.reload();
        });
    }

    // Format VND currency
    function formatVND(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
