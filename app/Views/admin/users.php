<?php
/**
 * app/Views/admin/users.php — Danh sách người dùng (Admin, Plates Template)
 * [C1] Giao diện: Bảng người dùng, tìm kiếm, phân trang DataTables
 * [C3] Kế thừa layout 'layouts/admin' qua league/plates
 *
 * Biến nhận từ Admin\UserController@index:
 *   - $users (array of objects)
 */
$this->layout("layouts/admin", [
    "title"      => "BT WATCH — Quản lý người dùng",
    "activePage" => "users"
]);
?>

<?php $this->start("page") ?>

<div class="row mb-4">
  <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <h2 class="text-uppercase fw-bold m-0" style="letter-spacing: 1px;">Danh sách người dùng</h2>
      <p class="text-muted small m-0">Quản lý tài khoản khách hàng và quản trị viên trong hệ thống.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <span class="badge bg-warning text-dark fs-6 px-3 py-2">
        <i class="fas fa-users me-1"></i> <?= count($users) ?> người dùng
      </span>
    </div>
  </div>
</div>

<!-- Users Table -->
<div class="row">
  <div class="col-12">
    <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
      <div class="table-responsive">
        <table class="table table-hover datatable">
          <thead>
            <tr>
              <th style="width: 60px;">ID</th>
              <th style="width: 60px;">Avatar</th>
              <th>Họ tên</th>
              <th>Email</th>
              <th>Số điện thoại</th>
              <th>Vai trò</th>
              <th>Ngày đăng ký</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                  <td><strong class="text-dark"><?= $this->e($u->id) ?></strong></td>
                  <td>
                    <?php
                    if (!empty($u->avatar_path)) {
                        $avatarUrl = $u->avatar_path;
                    } else {
                        $avatarUrl = $u->role === 'admin' ? '/images/logo_opt.png' : '/images/default_avatar.jpg';
                    }
                    ?>
                    <img src="<?= $this->e($avatarUrl) ?>" class="rounded-circle shadow-sm border border-light" style="width: 40px; height: 40px; object-fit: cover;">
                  </td>
                  <td>
                    <div class="fw-bold text-dark"><?= $this->e($u->full_name) ?></div>
                  </td>
                  <td>
                    <a href="mailto:<?= $this->e($u->email) ?>" class="text-decoration-none text-primary">
                      <?= $this->e($u->email) ?>
                    </a>
                  </td>
                  <td>
                    <?php if (!empty($u->phone)): ?>
                      <span class="text-dark"><?= $this->e($u->phone) ?></span>
                    <?php else: ?>
                      <span class="text-muted fst-italic">Chưa cập nhật</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($u->role === 'admin'): ?>
                      <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                        <i class="fas fa-shield-alt me-1"></i> Admin
                      </span>
                    <?php else: ?>
                      <span class="badge bg-info text-white rounded-pill px-3 py-2">
                        <i class="fas fa-user me-1"></i> Khách hàng
                      </span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <span class="text-muted small">
                      <?= date('d/m/Y H:i', strtotime($u->created_at)) ?>
                    </span>
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
