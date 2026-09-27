<?php
/**
 * app/Views/admin/order_detail.php — Chi tiết đơn hàng (Admin, Plates Template)
 * [C1] Giao diện: Chi tiết đơn hàng + cập nhật trạng thái
 * [C3] Kế thừa layout 'layouts/admin'
 *
 * Biến nhận từ Admin\OrderController@show:
 *   - $order (object)
 *   - $items (array of objects)
 */
$this->layout("layouts/admin", [
    "title"      => "BT WATCH — Chi tiết đơn hàng #" . ($order->id ?? ''),
    "activePage" => "order_detail"
]);
?>

<?php $this->start("page") ?>

<div class="row mb-4">
  <div class="col-12 d-flex justify-content-between align-items-center">
    <div>
      <h2 class="text-uppercase fw-bold m-0" style="letter-spacing: 1px;">Chi Tiết Đơn Hàng</h2>
      <p class="text-muted small m-0">Đơn hàng #<strong class="text-dark"><?= $this->e($order->id) ?></strong> | Đặt ngày: <?= date('d/m/Y H:i', strtotime($order->created_at)) ?></p>
    </div>
    <a href="/admin/orders" class="btn btn-outline-dark fw-semibold"><i class="fas fa-arrow-left me-2"></i> Quay lại</a>
  </div>
</div>

<div class="row g-4">
  <!-- Left Side: Order items list -->
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
      <h5 class="fw-bold text-uppercase mb-4 text-dark" style="letter-spacing: 1px;">Sản phẩm trong đơn</h5>

      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr class="text-muted text-uppercase small" style="font-size: 0.75rem;">
              <th>Sản phẩm</th>
              <th class="text-center">Đơn giá</th>
              <th class="text-center">Số lượng</th>
              <th class="text-end">Thành tiền</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $subtotal = 0;
            foreach ($items as $item):
              $itemTotal = ($item->unit_price ?? 0) * ($item->quantity ?? 0);
              $subtotal += $itemTotal;
            ?>
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <div>
                      <h6 class="m-0 fw-bold"><?= $this->e($item->product_name ?? 'Sản phẩm') ?></h6>
                    </div>
                  </div>
                </td>
                <td class="text-center"><?= vnd($item->unit_price ?? 0) ?></td>
                <td class="text-center"><?= $this->e($item->quantity ?? 0) ?></td>
                <td class="text-end fw-bold text-dark"><?= vnd($itemTotal) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="row mt-4 pt-3 border-top justify-content-end">
        <div class="col-md-5">
          <div class="d-flex justify-content-between mb-2">
            <span class="text-secondary">Tạm tính:</span>
            <span class="fw-semibold"><?= vnd($subtotal) ?></span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="text-secondary">Phí vận chuyển:</span>
            <span class="text-success fw-semibold">Miễn phí</span>
          </div>
          <div class="d-flex justify-content-between border-top pt-2">
            <span class="fw-bold fs-5">Tổng tiền:</span>
            <span class="fw-bold fs-5 text-warning"><?= vnd($order->total_amount) ?></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Side: Status + Shipping info -->
  <div class="col-lg-4">
    <!-- Status -->
    <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 bg-dark text-white">
      <h5 class="fw-bold text-uppercase mb-4 text-warning" style="letter-spacing: 1px; font-size: 1rem;">Trạng thái đơn hàng</h5>

      <div class="mb-3">
        <label class="text-secondary small text-uppercase d-block mb-1">Trạng thái hiện tại</label>
        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold text-uppercase" style="letter-spacing: 0.5px;"><?= $this->e($order->status) ?></span>
      </div>

      <!-- Form cập nhật trạng thái -->
      <form action="/admin/orders/<?= $order->id ?>/status" method="POST" class="mt-3">
        <?= csrf_field() ?>
        <label class="text-secondary small text-uppercase d-block mb-1">Đổi trạng thái</label>
        <select name="status" class="form-select form-select-sm mb-2">
          <?php
          $statuses = ['pending' => 'Chờ xác nhận', 'confirmed' => 'Đã xác nhận', 'shipping' => 'Đang giao', 'delivered' => 'Hoàn thành', 'cancelled' => 'Đã hủy'];
          foreach ($statuses as $val => $label):
          ?>
            <option value="<?= $val ?>" <?= $order->status === $val ? 'selected' : '' ?>><?= $label ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-sm btn-warning text-dark fw-bold w-100">Cập nhật</button>
      </form>
    </div>

    <!-- Shipping info -->
    <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
      <h5 class="fw-bold text-uppercase mb-4 text-dark" style="letter-spacing: 1px; font-size: 1rem;">Thông tin giao hàng</h5>

      <div class="mb-3">
        <label class="text-muted small text-uppercase d-block">Khách hàng</label>
        <div class="fw-semibold fs-6 text-dark"><?= $this->e($order->shipping_name ?? 'Khách hàng') ?></div>
      </div>

      <div class="mb-3">
        <label class="text-muted small text-uppercase d-block">Số điện thoại</label>
        <div class="fw-semibold text-dark"><?= $this->e($order->shipping_phone ?? '(Chưa cập nhật)') ?></div>
      </div>

      <div class="mb-3">
        <label class="text-muted small text-uppercase d-block">Email liên lạc</label>
        <div class="fw-semibold text-dark"><?= $this->e($order->shipping_email ?? '(Chưa cập nhật)') ?></div>
      </div>

      <div>
        <label class="text-muted small text-uppercase d-block">Địa chỉ nhận hàng</label>
        <div class="fw-semibold text-dark small" style="line-height: 1.5;"><?= $this->e($order->shipping_address ?? '') ?></div>
      </div>
    </div>
  </div>
</div>

<?php $this->stop() ?>
