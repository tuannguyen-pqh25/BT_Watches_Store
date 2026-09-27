<?php
/**
 * app/Views/admin/dashboard.php — Trang tổng quan quản trị (Plates Template)
 * [C1] Giao diện: Stat cards, biểu đồ Chart.js, bảng đơn hàng mới
 * [C3] Kế thừa layout 'layouts/admin' qua league/plates
 *
 * Biến nhận từ DashboardController:
 *   - $totalProducts   (int)
 *   - $totalCategories (int)
 *   - $products        (array)
 *   - $categories      (array)
 */
$this->layout("layouts/admin", [
    "title"      => "BT WATCH — Bảng điều khiển quản trị",
    "activePage" => "dashboard"
]);
?>

<?php $this->start("page") ?>

<div class="row mb-4">
  <div class="col-12">
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <h2 class="text-uppercase fw-bold m-0" style="letter-spacing: 1px;">Bảng điều khiển</h2>
        <p class="text-muted small m-0">Chào mừng trở lại, <?= $this->e(AUTHGUARD()->user()->full_name ?? 'Admin') ?>. Đây là tổng quan hoạt động kinh doanh.</p>
      </div>
      <a href="/admin/products/create" class="btn btn-warning text-dark fw-bold"><i class="fas fa-plus me-2"></i> Thêm sản phẩm</a>
    </div>
  </div>
</div>

<!-- Stat Cards -->
<div class="row">
  <div class="col-md-3 col-sm-6">
    <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 border-start border-warning border-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-muted text-uppercase small fw-semibold" style="letter-spacing:1px;">Tổng sản phẩm</span>
        <i class="fas fa-box-open text-warning fs-4"></i>
      </div>
      <div class="fs-1 fw-bold"><?= $this->e($totalProducts) ?></div>
      <small class="text-success fw-semibold"><i class="fas fa-arrow-up me-1"></i> Đang hiển thị</small>
    </div>
  </div>

  <div class="col-md-3 col-sm-6">
    <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 border-start border-warning border-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-muted text-uppercase small fw-semibold" style="letter-spacing:1px;">Danh mục</span>
        <i class="fas fa-tags text-warning fs-4"></i>
      </div>
      <div class="fs-1 fw-bold"><?= $this->e($totalCategories) ?></div>
      <small class="text-muted fw-semibold">Phân loại sản phẩm</small>
    </div>
  </div>

  <div class="col-md-3 col-sm-6">
    <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 border-start border-warning border-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-muted text-uppercase small fw-semibold" style="letter-spacing:1px;">Đơn hàng</span>
        <i class="fas fa-shopping-cart text-warning fs-4"></i>
      </div>
      <div class="fs-1 fw-bold"><?= $this->e($totalOrders ?? 0) ?></div>
      <small class="text-muted fw-semibold">Tổng đơn hàng</small>
    </div>
  </div>

  <div class="col-md-3 col-sm-6">
    <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 border-start border-warning border-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-muted text-uppercase small fw-semibold" style="letter-spacing:1px;">Người dùng</span>
        <i class="fas fa-users text-warning fs-4"></i>
      </div>
      <div class="fs-1 fw-bold"><?= $this->e($totalUsers ?? 0) ?></div>
      <small class="text-muted fw-semibold"><a href="/admin/users" class="text-decoration-none text-success fw-semibold">Quản lý người dùng</a></small>
    </div>
  </div>
</div>

<!-- Revenue Card -->
<div class="row">
  <div class="col-md-6 col-sm-12">
    <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 border-start border-warning border-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-muted text-uppercase small fw-semibold" style="letter-spacing:1px;">Doanh thu</span>
        <i class="fas fa-coins text-warning fs-4"></i>
      </div>
      <div class="fs-1 fw-bold text-nowrap"><?= vnd($totalRevenue ?? 0) ?></div>
      <small class="text-muted fw-semibold">Tổng doanh thu (Hoàn thành)</small>
    </div>
  </div>
</div>

<?php $this->stop() ?>

