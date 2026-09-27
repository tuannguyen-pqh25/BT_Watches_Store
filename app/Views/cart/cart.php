<?php
/**
 * app/Views/cart/cart.php — Trang giỏ hàng
 * [C1] Giao diện: Bootstrap 5
 * [C3] league/plates: kế thừa layouts/default
 * [C4] CSRF: csrf_field() trong mọi form POST
 *
 * Biến nhận từ Controller:
 *   - $cartItems : array — danh sách item trong giỏ [{product, quantity}]
 *   - $total     : float — tổng tiền
 */
$this->layout("layouts/default", [
    "title"        => "BT WATCH — Giỏ hàng",
    "currentRoute" => "cart",
]);
?>

<?php $this->start("page") ?>
  <div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.85rem;">Trang chủ</a></li>
        <li class="breadcrumb-item active text-warning text-uppercase fw-bold" aria-current="page" style="letter-spacing: 1px; font-size: 0.85rem;">Giỏ hàng</li>
      </ol>
    </nav>

    <h1 class="fw-bold mb-5 text-dark text-uppercase" style="letter-spacing: 2px;">Giỏ Hàng <span class="text-warning">Của Bạn</span></h1>

    <?php if (empty($cartItems)): ?>
      <!-- Giỏ hàng trống -->
      <div class="text-center py-5">
        <i class="fas fa-shopping-cart fa-4x text-secondary mb-4"></i>
        <h3 class="text-secondary mb-3">Giỏ hàng trống</h3>
        <p class="text-muted mb-4">Hãy thêm sản phẩm vào giỏ hàng để tiến hành mua sắm.</p>
        <a href="/products" class="btn btn-dark btn-lg fw-bold text-uppercase px-5 py-3" style="letter-spacing: 1px;">
          <i class="fas fa-shopping-bag me-2"></i>Mua sắm ngay
        </a>
      </div>
    <?php else: ?>
      <div class="row g-5 mb-5">
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="table-light text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">
                    <tr>
                      <th scope="col" class="py-3 px-4">Sản phẩm</th>
                      <th scope="col" class="py-3 text-center">Đơn giá</th>
                      <th scope="col" class="py-3 text-center">Số lượng</th>
                      <th scope="col" class="py-3 text-end px-4">Thành tiền</th>
                      <th scope="col" class="py-3"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($cartItems as $item): ?>
                    <tr>
                      <td class="p-4">
                        <div class="d-flex align-items-center">
                          <img src="<?= $this->e($item['product']['image_path'] ?? 'https://images.unsplash.com/photo-1523170335258-f5ed11844a49?w=200&q=85') ?>"
                               class="img-fluid rounded border border-light me-3" alt="<?= $this->e($item['product']['name']) ?>"
                               style="width: 80px; height: 80px; object-fit: cover;">
                          <div>
                            <h6 class="fw-bold text-dark mb-1">
                              <a href="/products/<?= $this->e($item['product']['id']) ?>" class="text-decoration-none text-dark">
                                <?= $this->e($item['product']['name']) ?>
                              </a>
                            </h6>
                          </div>
                        </div>
                      </td>
                      <td class="text-center fw-semibold"><?= vnd($item['product']['price']) ?></td>
                      <td class="text-center" style="width: 150px;">
                        <form action="/cart/update" method="POST" class="d-inline">
                          <?= csrf_field() ?>
                          <input type="hidden" name="product_id" value="<?= $this->e($item['product']['id']) ?>">
                          <div class="input-group input-group-sm">
                            <button type="submit" name="quantity" value="<?= $item['quantity'] - 1 ?>" class="btn btn-outline-secondary" <?= $item['quantity'] <= 1 ? 'disabled' : '' ?>>−</button>
                            <input type="text" class="form-control text-center" value="<?= $this->e($item['quantity']) ?>" readonly>
                            <button type="submit" name="quantity" value="<?= $item['quantity'] + 1 ?>" class="btn btn-outline-secondary">+</button>
                          </div>
                        </form>
                      </td>
                      <td class="text-end fw-bold px-4"><?= vnd($item['product']['price'] * $item['quantity']) ?></td>
                      <td class="text-center">
                        <form action="/cart/remove" method="POST" class="d-inline">
                          <?= csrf_field() ?>
                          <input type="hidden" name="product_id" value="<?= $this->e($item['product']['id']) ?>">
                          <button type="submit" class="btn btn-sm btn-outline-danger border-0"><i class="fas fa-times"></i></button>
                        </form>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <a href="/products" class="btn btn-outline-dark fw-bold text-uppercase px-4" style="letter-spacing: 1px;"><i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm</a>
        </div>

        <div class="col-lg-4">
          <div class="card border-0 shadow-sm bg-light">
            <div class="card-body p-4">
              <h5 class="fw-bold mb-4 text-uppercase text-dark" style="letter-spacing: 1px;">Tổng Đơn Hàng</h5>

              <div class="d-flex justify-content-between mb-3 text-secondary">
                <span>Tạm tính:</span>
                <span class="fw-semibold text-dark"><?= vnd($total) ?></span>
              </div>
              <div class="d-flex justify-content-between mb-3 text-secondary">
                <span>Phí vận chuyển:</span>
                <span class="text-success fw-semibold">Miễn phí</span>
              </div>

              <hr class="my-4">

              <div class="d-flex justify-content-between mb-4 align-items-center">
                <span class="fw-bold fs-5 text-dark text-uppercase">Tổng cộng:</span>
                <span class="text-warning fw-bold fs-3"><?= vnd($total) ?></span>
              </div>

              <a href="/checkout" class="btn btn-dark btn-lg w-100 fw-bold py-3 mb-3 text-uppercase" style="letter-spacing: 1px;">
                Tiến Hành Thanh Toán
              </a>

              <div class="text-center mt-4">
                <p class="text-secondary small mb-2 text-uppercase" style="letter-spacing: 1px;">Thanh toán an toàn</p>
                <i class="fab fa-cc-visa text-secondary fs-3 me-2"></i>
                <i class="fab fa-cc-mastercard text-secondary fs-3 me-2"></i>
                <i class="fab fa-cc-paypal text-secondary fs-3"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
<?php $this->stop() ?>
