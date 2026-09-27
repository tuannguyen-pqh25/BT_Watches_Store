<?php
/**
 * app/Views/admin/orders.php — Danh sách đơn hàng (Admin, Plates Template)
 * [C1] Giao diện: Bảng đơn hàng luxury
 * [C3] Kế thừa layout 'layouts/admin'
 *
 * Biến nhận từ Admin\OrderController@index:
 *   - $orders (array of objects)
 */
$this->layout("layouts/admin", [
    "title"      => "BT WATCH — Quản lý đơn hàng",
    "activePage" => "orders"
]);
?>

<?php $this->start("page") ?>

<div class="row mb-4">
  <div class="col-12">
    <h2 class="text-uppercase fw-bold m-0" style="letter-spacing: 1px;">Danh sách đơn hàng</h2>
    <p class="text-muted small m-0">Quản lý quy trình xử lý đơn hàng, theo dõi giao nhận và doanh thu.</p>
  </div>
</div>

<!-- Orders Table -->
<div class="row">
  <div class="col-12">
    <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
      <div class="table-responsive">
        <table class="table table-hover datatable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Khách hàng</th>
              <th>Ngày đặt</th>
              <th>Tổng tiền</th>
              <th>Trạng thái</th>
              <th class="text-end" style="width: 200px;">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $ord): ?>
                <tr>
                  <td><strong class="text-dark">#<?= $this->e($ord->id) ?></strong></td>
                  <td>
                    <div class="fw-semibold"><?= $this->e($ord->customer_name ?? 'Khách hàng') ?></div>
                    <small class="text-muted"><?= $this->e($ord->customer_email ?? '') ?></small>
                  </td>
                  <td><?= date('d/m/Y H:i', strtotime($ord->created_at)) ?></td>
                  <td><strong class="text-warning"><?= vnd($ord->total_amount) ?></strong></td>
                  <td>
                    <?php
                    $statusClass = 'secondary';
                    $status = $ord->status ?? 'pending';
                    if ($status === 'pending') $statusClass = 'warning';
                    elseif ($status === 'confirmed') $statusClass = 'info';
                    elseif ($status === 'shipping') $statusClass = 'primary';
                    elseif ($status === 'delivered') $statusClass = 'success';
                    elseif ($status === 'cancelled') $statusClass = 'danger';
                    ?>
                    <span class="badge bg-<?= $statusClass ?> rounded-pill px-3 py-2 small">
                      <?= $this->e($status) ?>
                    </span>
                  </td>
                  <td class="text-end">
                    <a href="/admin/orders/<?= $ord->id ?>" class="btn btn-sm btn-light border-warning">
                      <i class="far fa-eye text-warning"></i> Chi tiết
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php $this->stop() ?>
