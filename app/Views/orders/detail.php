<?php
/**
 * app/Views/orders/detail.php — Chi tiết đơn hàng
 * [C1] Giao diện: Bootstrap 5
 * [C3] league/plates: kế thừa layouts/default
 *
 * Biến nhận từ Controller:
 *   - $order : array — thông tin đơn hàng
 *   - $items : array — danh sách sản phẩm trong đơn
 */
$this->layout("layouts/default", [
    "title"        => "BT WATCH — Đơn hàng #" . ($order->id ?? ''),
    "currentRoute" => "orders",
]);
?>

<?php $this->start("page") ?>
  <div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.85rem;">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="/orders" class="text-decoration-none text-secondary text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.85rem;">Đơn hàng</a></li>
        <li class="breadcrumb-item active text-warning text-uppercase fw-bold" aria-current="page" style="letter-spacing: 1px; font-size: 0.85rem;">#<?= $this->e($order->id) ?></li>
      </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-5">
      <h1 class="fw-bold text-dark text-uppercase mb-0" style="letter-spacing: 2px;">
        Đơn Hàng <span class="text-warning">#<?= $this->e($order->id) ?></span>
      </h1>
      <?php
      $statusMap = [
          'pending'    => ['Chờ xác nhận', 'bg-warning text-dark'],
          'confirmed'  => ['Đã xác nhận', 'bg-info text-white'],
          'shipping'   => ['Đang giao', 'bg-primary text-white'],
          'delivered'  => ['Đã giao', 'bg-success text-white'],
          'cancelled'  => ['Đã hủy', 'bg-danger text-white'],
      ];
      $status = $statusMap[$order->status] ?? ['Không rõ', 'bg-secondary text-white'];
      ?>
      <span class="badge <?= $status[1] ?> fs-6 px-4 py-2"><?= $status[0] ?></span>
    </div>

    <div class="row g-5">
      <!-- Thông tin đơn hàng -->
      <div class="col-lg-8">
        <!-- Danh sách sản phẩm -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-dark text-white py-3">
            <h6 class="fw-bold mb-0 text-uppercase" style="letter-spacing: 1px;">
              <i class="fas fa-box me-2"></i>Sản phẩm đã đặt
            </h6>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase" style="letter-spacing: 1px; font-size: 0.8rem;">
                  <tr>
                    <th class="py-3 px-4">Sản phẩm</th>
                    <th class="py-3 text-center">Đơn giá</th>
                    <th class="py-3 text-center">SL</th>
                    <th class="py-3 text-end px-4">Thành tiền</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($items as $item): ?>
                  <tr>
                    <td class="p-4">
                       <div class="d-flex align-items-center">
                        <img src="<?= $this->e($item->image_path ?? 'https://images.unsplash.com/photo-1523170335258-f5ed11844a49?w=100&q=85') ?>"
                             class="rounded me-3" alt="<?= $this->e($item->product_name) ?>"
                             style="width: 60px; height: 60px; object-fit: cover;">
                        <div>
                          <h6 class="fw-bold text-dark mb-0"><?= $this->e($item->product_name) ?></h6>
                        </div>
                      </div>
                    </td>
                    <td class="text-center"><?= vnd($item->unit_price) ?></td>
                    <td class="text-center fw-semibold"><?= $this->e($item->quantity) ?></td>
                    <td class="text-end fw-bold px-4"><?= vnd($item->unit_price * $item->quantity) ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Tóm tắt -->
      <div class="col-lg-4">
        <!-- Thông tin chung -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body p-4">
            <h6 class="fw-bold mb-3 text-uppercase text-dark" style="letter-spacing: 1px;">
              <i class="fas fa-info-circle me-2 text-warning"></i>Thông tin đơn hàng
            </h6>
            <div class="mb-2">
              <small class="text-muted">Mã đơn hàng</small>
              <p class="fw-bold mb-0">#<?= $this->e($order->id) ?></p>
            </div>
            <div class="mb-2">
              <small class="text-muted">Ngày đặt</small>
              <p class="fw-semibold mb-0"><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></p>
            </div>
            <?php if (!empty($order->shipping_address)): ?>
            <div class="mb-2">
              <small class="text-muted">Địa chỉ giao hàng</small>
              <p class="fw-semibold mb-0"><?= $this->e($order->shipping_address) ?></p>
            </div>
            <?php endif; ?>
            <?php if (!empty($order->shipping_phone)): ?>
            <div class="mb-2">
              <small class="text-muted">Số điện thoại</small>
              <p class="fw-semibold mb-0"><?= $this->e($order->shipping_phone) ?></p>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Tổng tiền -->
        <div class="card border-0 shadow-sm bg-light">
          <div class="card-body p-4">
            <h6 class="fw-bold mb-3 text-uppercase text-dark" style="letter-spacing: 1px;">
              <i class="fas fa-receipt me-2 text-warning"></i>Tổng cộng
            </h6>
            <div class="d-flex justify-content-between mb-2 text-secondary">
              <span>Tạm tính:</span>
              <span class="fw-semibold text-dark"><?= vnd($order->total_amount) ?></span>
            </div>
            <div class="d-flex justify-content-between mb-3 text-secondary">
              <span>Phí vận chuyển:</span>
              <span class="text-success fw-semibold">Miễn phí</span>
            </div>
            <hr class="my-3">
            <div class="d-flex justify-content-between align-items-center">
              <span class="fw-bold fs-5 text-dark text-uppercase">Tổng cộng:</span>
              <span class="text-warning fw-bold fs-3"><?= vnd($order->total_amount) ?></span>
            </div>
          </div>
        </div>

        <!-- Nút quay lại -->
        <div class="mt-4">
          <a href="/orders" class="btn btn-outline-dark w-100 fw-bold text-uppercase py-2" style="letter-spacing: 1px;">
            <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
          </a>
        </div>
      </div>
    </div>
  </div>
<?php $this->stop() ?>
