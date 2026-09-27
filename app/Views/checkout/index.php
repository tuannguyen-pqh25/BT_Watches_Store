<?php
/**
 * app/Views/checkout/index.php — Trang thanh toán
 * [C1] Giao diện: Bootstrap 5, form địa chỉ giao hàng
 * [C3] league/plates: kế thừa layouts/default
 * [C4] CSRF: csrf_field() trong form
 *
 * Biến nhận từ Controller:
 *   - $cartItems : array — danh sách item trong giỏ
 *   - $total     : float — tổng tiền
 */
$this->layout("layouts/default", [
    "title"        => "BT WATCH — Thanh toán",
    "currentRoute" => "checkout",
]);
?>

<?php $this->start("page") ?>
  <div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.85rem;">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="/cart" class="text-decoration-none text-secondary text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.85rem;">Giỏ hàng</a></li>
        <li class="breadcrumb-item active text-warning text-uppercase fw-bold" aria-current="page" style="letter-spacing: 1px; font-size: 0.85rem;">Thanh toán</li>
      </ol>
    </nav>

    <h1 class="fw-bold mb-5 text-dark text-uppercase" style="letter-spacing: 2px;">Thanh <span class="text-warning">Toán</span></h1>

    <form action="/checkout" method="POST">
      <?= csrf_field() ?>

      <div class="row g-5">
        <!-- Thông tin giao hàng -->
        <div class="col-lg-7">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
              <h5 class="fw-bold mb-4 text-uppercase text-dark" style="letter-spacing: 1px;">
                <i class="fas fa-truck me-2 text-warning"></i>Thông tin giao hàng
              </h5>

              <div class="row g-3">
                <div class="col-12">
                  <label for="shipping_name" class="form-label text-dark fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Họ và tên người nhận</label>
                  <input type="text" name="shipping_name" id="shipping_name" class="form-control bg-light border-0 shadow-none py-2 px-3"
                         value="<?= $this->e(old('shipping_name') ?: (AUTHGUARD()->user()->full_name ?? '')) ?>" required>
                </div>

                <div class="col-md-6">
                  <label for="shipping_phone" class="form-label text-dark fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Số điện thoại</label>
                  <input type="tel" name="shipping_phone" id="shipping_phone" class="form-control bg-light border-0 shadow-none py-2 px-3"
                         value="<?= $this->e(old('shipping_phone')) ?>" placeholder="0123 456 789" required>
                </div>

                <div class="col-md-6">
                  <label for="shipping_email" class="form-label text-dark fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Email</label>
                  <input type="email" name="shipping_email" id="shipping_email" class="form-control bg-light border-0 shadow-none py-2 px-3"
                         value="<?= $this->e(old('shipping_email') ?: (AUTHGUARD()->user()->email ?? '')) ?>" required>
                </div>

                <div class="col-12">
                  <label for="shipping_address" class="form-label text-dark fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Địa chỉ giao hàng</label>
                  <textarea name="shipping_address" id="shipping_address" class="form-control bg-light border-0 shadow-none py-2 px-3" rows="3"
                            placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố" required><?= $this->e(old('shipping_address')) ?></textarea>
                </div>

                <div class="col-12">
                  <label for="note" class="form-label text-dark fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Ghi chú (tuỳ chọn)</label>
                  <textarea name="note" id="note" class="form-control bg-light border-0 shadow-none py-2 px-3" rows="2"
                            placeholder="Ghi chú về đơn hàng..."><?= $this->e(old('note')) ?></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tóm tắt đơn hàng -->
        <div class="col-lg-5">
          <div class="card border-0 shadow-sm bg-light">
            <div class="card-body p-4">
              <h5 class="fw-bold mb-4 text-uppercase text-dark" style="letter-spacing: 1px;">
                <i class="fas fa-receipt me-2 text-warning"></i>Đơn hàng của bạn
              </h5>

              <?php foreach ($cartItems as $item): ?>
              <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                <img src="<?= $this->e($item['product']['image_path'] ?? 'https://images.unsplash.com/photo-1523170335258-f5ed11844a49?w=100&q=85') ?>"
                     class="rounded me-3" alt="<?= $this->e($item['product']['name']) ?>"
                     style="width: 60px; height: 60px; object-fit: cover;">
                <div class="flex-grow-1">
                  <h6 class="fw-bold text-dark mb-0 small"><?= $this->e($item['product']['name']) ?></h6>
                  <small class="text-muted">SL: <?= $this->e($item['quantity']) ?></small>
                </div>
                <span class="fw-bold"><?= vnd($item['product']['price'] * $item['quantity']) ?></span>
              </div>
              <?php endforeach; ?>

              <div class="d-flex justify-content-between mb-2 text-secondary">
                <span>Tạm tính:</span>
                <span class="fw-semibold text-dark"><?= vnd($total) ?></span>
              </div>
              <div class="d-flex justify-content-between mb-3 text-secondary">
                <span>Phí vận chuyển:</span>
                <span class="text-success fw-semibold">Miễn phí</span>
              </div>

              <hr class="my-3">

              <div class="d-flex justify-content-between mb-4 align-items-center">
                <span class="fw-bold fs-5 text-dark text-uppercase">Tổng cộng:</span>
                <span class="text-warning fw-bold fs-3"><?= vnd($total) ?></span>
              </div>

              <button type="submit" class="btn btn-dark btn-lg w-100 fw-bold py-3 text-uppercase" style="letter-spacing: 1px;">
                <i class="fas fa-lock me-2"></i>Xác Nhận Đặt Hàng
              </button>

              <div class="text-center mt-3">
                <a href="/cart" class="text-secondary text-decoration-none small">
                  <i class="fas fa-arrow-left me-1"></i>Quay lại giỏ hàng
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
<?php $this->stop() ?>
