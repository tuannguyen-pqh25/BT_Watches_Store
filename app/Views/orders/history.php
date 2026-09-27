<?php
/**
 * app/Views/orders/history.php — Lịch sử đơn hàng
 * [C1] Giao diện: Bootstrap 5
 * [C3] league/plates: kế thừa layouts/default
 *
 * Biến nhận từ Controller:
 *   - $orders : array — danh sách đơn hàng của user
 */
$this->layout("layouts/default", [
    "title"        => "BT WATCH — Đơn hàng của tôi",
    "currentRoute" => "orders",
]);
?>

<?php $this->start("page") ?>
  <div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.85rem;">Trang chủ</a></li>
        <li class="breadcrumb-item active text-warning text-uppercase fw-bold" aria-current="page" style="letter-spacing: 1px; font-size: 0.85rem;">Đơn hàng</li>
      </ol>
    </nav>

    <h1 class="fw-bold mb-5 text-dark text-uppercase" style="letter-spacing: 2px;">Đơn Hàng <span class="text-warning">Của Tôi</span></h1>

    <?php if (empty($orders)): ?>
      <div class="text-center py-5">
        <i class="fas fa-box-open fa-4x text-secondary mb-4"></i>
        <h3 class="text-secondary mb-3">Chưa có đơn hàng nào</h3>
        <p class="text-muted mb-4">Hãy mua sắm và đặt hàng để xem lịch sử đơn hàng tại đây.</p>
        <a href="/products" class="btn btn-dark btn-lg fw-bold text-uppercase px-5 py-3" style="letter-spacing: 1px;">
          <i class="fas fa-shopping-bag me-2"></i>Mua sắm ngay
        </a>
      </div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($orders as $order): ?>
        <div class="col-12">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
              <div class="row align-items-center">
                <div class="col-md-3">
                  <small class="text-muted text-uppercase" style="letter-spacing: 1px;">Mã đơn hàng</small>
                  <h5 class="fw-bold text-dark mb-0">#<?= $this->e($order->id) ?></h5>
                </div>
                <div class="col-md-3">
                  <small class="text-muted text-uppercase" style="letter-spacing: 1px;">Ngày đặt</small>
                  <p class="fw-semibold text-dark mb-0"><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></p>
                </div>
                <div class="col-md-2">
                  <small class="text-muted text-uppercase" style="letter-spacing: 1px;">Tổng tiền</small>
                  <p class="fw-bold text-warning mb-0"><?= vnd($order->total_amount) ?></p>
                </div>
                <div class="col-md-2">
                  <small class="text-muted text-uppercase" style="letter-spacing: 1px;">Trạng thái</small>
                  <div>
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
                    <span class="badge <?= $status[1] ?> px-3 py-2"><?= $status[0] ?></span>
                  </div>
                </div>
                <div class="col-md-2 text-end">
                  <a href="/orders/<?= $this->e($order->id) ?>" class="btn btn-outline-dark btn-sm fw-bold text-uppercase px-3" style="letter-spacing: 1px;">
                    Chi tiết
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
<?php $this->stop() ?>
