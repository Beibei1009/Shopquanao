<?php ob_start(); ?>

<div class="container my-4">
  <h2 class="text-center text-danger mb-4">
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
      <div class="col-lg-8">
        <div class="card shadow-sm">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table align-middle">
                <!-- Giỏ hàng được hiển thị dưới dạng bảng -->
                <thead class="table-danger">
                  <tr>
                    <th>Sản phẩm</th>
                    <th>Tên</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-end">Giá</th>
                    <th class="text-end">Tổng</th>
                    <th class="text-center">Xóa</th>
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
                    <td>
                      <img src="/uploads/<?= e($item['image']) ?>"
                           class="img-thumbnail"
                           style="width: 80px; height: 80px; object-fit: cover;"
                           alt="<?= e($item['name']) ?>">
                    </td>
                    <td>
                      <strong><?= e($item['name']) ?></strong>
                      <?php if (!empty($item['size'])): ?>
                        <br>
                        <span class="badge bg-secondary">Size: <?= e($item['size']) ?></span>
                      <?php endif; ?>
                      <?php if (!empty($item['description'])): ?>
                        <br>
                        <small class="text-muted"><?= e($item['description']) ?></small>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <div class="input-group input-group-sm" style="max-width: 130px; margin: 0 auto;">
                        <button class="btn btn-outline-secondary btn-decrease" type="button" data-id="<?= $id ?>">
                          <i class="bi bi-dash"></i>
                        </button>
                        <input type="number"
                               class="form-control form-control-sm text-center quantity-input"
                               value="<?= $item['quantity'] ?>"
                               min="1"
                               data-id="<?= $id ?>"
                               readonly>
                        <button class="btn btn-outline-secondary btn-increase" type="button" data-id="<?= $id ?>">
                          <i class="bi bi-plus"></i>
                        </button>
                      </div>
                    </td>
                    <td class="text-end text-danger fw-bold">
                      <?= format_vnd($item['price']) ?>
                    </td>
                    <td class="text-end fw-bold subtotal">
                      <?= format_vnd($subtotal) ?>
                    </td>
                    <td class="text-center">
                      <a href="/cart/remove?id=<?= $id ?>"
                         class="btn btn-sm btn-outline-danger"
                         onclick="return confirm('Xóa sản phẩm này?')">
                        <i class="bi bi-trash"></i>
                      </a>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <!-- Tiếp tục mua sắm -->
            <div class="mt-3">
              <a href="/products" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- tóm tắt đơn hàng -->
      <div class="col-lg-4">
        <div class="card shadow-sm sticky-top" style="top: 20px;">
          <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="bi bi-receipt"></i> Tóm tắt đơn hàng</h5>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
              <span>Tạm tính:</span>
              <span class="fw-bold" id="cart-subtotal"><?= format_vnd($total) ?></span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Phí vận chuyển:</span>
              <span class="text-success">Miễn phí</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between mb-3">
              <span class="fw-bold fs-5">Tổng cộng:</span>
              <span class="fw-bold text-danger fs-4" id="cart-total"><?= format_vnd($total) ?></span>
            </div>

            <div class="d-grid gap-2">
              <a href="/checkout-form" class="btn btn-danger btn-lg">
                <i class="bi bi-credit-card"></i> Thanh toán COD
              </a>
            </div>

            <div class="mt-3 text-center">
              <small class="text-muted">
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
                subtotalCell.textContent = formatVND(data.subtotal);

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
