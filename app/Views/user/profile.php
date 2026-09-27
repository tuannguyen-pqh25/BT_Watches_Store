<?php
/**
 * app/Views/user/profile.php — Trang hồ sơ cá nhân (Customer)
 * [C1] Giao diện: Bootstrap 5
 * [C3] league/plates: kế thừa layouts/default
 */
$this->layout("layouts/default", [
    "title"        => "BT WATCH — Hồ sơ cá nhân",
    "currentRoute" => "profile",
]);
?>

<?php $this->start("page") ?>
<div class="container py-5">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold">Hồ sơ cá nhân</li>
    </ol>
  </nav>

  <!-- Profile Header -->
  <div class="card bg-dark text-white rounded-4 border-0 mb-4 overflow-hidden position-relative shadow-sm" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%) !important;">
    <div class="card-body p-4 p-lg-5">
      <div class="d-flex flex-column flex-md-row align-items-center gap-4">
        <div class="position-relative d-inline-block">
          <img src="<?= $this->e($user->avatar_path ?? '/images/default_avatar.jpg') ?>"
               alt="Avatar" id="avatar-preview"
               class="rounded-circle border border-warning border-3 shadow-sm"
               style="width: 100px; height: 100px; object-fit: cover;">
          <label for="avatar-input" class="position-absolute bottom-0 end-0 bg-warning rounded-circle d-flex align-items-center justify-content-center border border-white border-2" title="Đổi ảnh đại diện" style="width: 32px; height: 32px; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
            <i class="fas fa-camera text-dark small"></i>
          </label>
        </div>
        <div class="text-center text-md-start">
          <h3 class="fw-bold mb-1"><?= $this->e($user->full_name) ?></h3>
          <p class="mb-2 text-white-50"><?= $this->e($user->email) ?></p>
          <span class="badge px-3 py-2 border border-warning text-warning rounded-pill bg-dark bg-opacity-50">
            <i class="fas fa-user me-1"></i> Khách hàng
          </span>
        </div>
        <div class="ms-md-auto d-flex gap-3 mt-3 mt-md-0">
          <div class="text-center bg-white bg-opacity-10 rounded p-3">
            <small class="d-block text-white-50 mb-1">Tham gia</small>
            <strong class="text-white"><?= date('d/m/Y', strtotime($user->created_at)) ?></strong>
          </div>
        </div>
      </div>
    </div>

    <!-- Form ẩn upload avatar -->
    <form action="/profile/avatar" method="POST" enctype="multipart/form-data" id="avatar-form" class="d-none">
      <?= csrf_field() ?>
      <input type="file" name="avatar" id="avatar-input" accept="image/*">
    </form>
  </div>

  <div class="row g-4">
    <!-- Cột trái: Thông tin cá nhân -->
    <div class="col-lg-7">
      <div class="card shadow-sm border-0 rounded-4 h-100">
        <div class="card-header bg-white border-bottom py-3 rounded-top-4">
          <h6 class="mb-0 fw-bold text-dark">
            <i class="fas fa-user-edit me-2 text-warning"></i>Thông tin cá nhân
          </h6>
        </div>
        <div class="card-body p-4">
          <form action="/profile/update" method="POST">
            <?= csrf_field() ?>
            <div class="row g-4">
              <div class="col-md-6">
                <label class="form-label text-secondary small fw-bold text-uppercase">Họ và tên</label>
                <input type="text" name="full_name" class="form-control bg-light"
                       value="<?= $this->e($user->full_name) ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label text-secondary small fw-bold text-uppercase">Email</label>
                <input type="email" class="form-control bg-secondary bg-opacity-10" 
                       value="<?= $this->e($user->email) ?>" disabled>
                <div class="form-text"><i class="fas fa-lock me-1"></i>Không thể thay đổi</div>
              </div>
              <div class="col-md-6">
                <label class="form-label text-secondary small fw-bold text-uppercase">Số điện thoại</label>
                <input type="text" name="phone" class="form-control bg-light"
                       value="<?= $this->e($user->phone ?? '') ?>"
                       placeholder="Nhập số điện thoại">
              </div>
              <div class="col-md-6">
                <label class="form-label text-secondary small fw-bold text-uppercase">Ngày tạo tài khoản</label>
                <input type="text" class="form-control bg-secondary bg-opacity-10"
                       value="<?= date('d/m/Y H:i', strtotime($user->created_at)) ?>" disabled>
              </div>
            </div>
            <div class="mt-4 text-end">
              <button type="submit" class="btn btn-warning fw-bold px-4 py-2 rounded">
                <i class="fas fa-save me-2"></i>Lưu thay đổi
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Cột phải: Đổi mật khẩu -->
    <div class="col-lg-5">
      <div class="card shadow-sm border-0 rounded-4 h-100">
        <div class="card-header bg-white border-bottom py-3 rounded-top-4">
          <h6 class="mb-0 fw-bold text-dark">
            <i class="fas fa-shield-alt me-2 text-warning"></i>Đổi mật khẩu
          </h6>
        </div>
        <div class="card-body p-4">
          <form action="/profile/password" method="POST">
            <?= csrf_field() ?>
            <div class="mb-3">
              <label class="form-label text-secondary small fw-bold text-uppercase">Mật khẩu hiện tại</label>
              <div class="input-group">
                <input type="password" name="current_password" class="form-control bg-light"
                       placeholder="Nhập mật khẩu hiện tại" required>
                <button class="btn btn-outline-secondary toggle-password bg-light" type="button">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label text-secondary small fw-bold text-uppercase">Mật khẩu mới</label>
              <div class="input-group">
                <input type="password" name="new_password" class="form-control bg-light"
                       placeholder="Ít nhất 8 ký tự" required minlength="8">
                <button class="btn btn-outline-secondary toggle-password bg-light" type="button">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>
            <div class="mb-4">
              <label class="form-label text-secondary small fw-bold text-uppercase">Xác nhận mật khẩu mới</label>
              <div class="input-group">
                <input type="password" name="confirm_password" class="form-control bg-light"
                       placeholder="Nhập lại mật khẩu mới" required minlength="8">
                <button class="btn btn-outline-secondary toggle-password bg-light" type="button">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>
            <button type="submit" class="btn btn-warning fw-bold w-100 py-2 rounded">
              <i class="fas fa-key me-2"></i>Đổi mật khẩu
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->stop() ?>

<?php $this->start("page_specific_js") ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Avatar upload: tự động submit khi chọn file
  const avatarInput = document.getElementById('avatar-input');
  if (avatarInput) {
    avatarInput.addEventListener('change', function() {
      if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('avatar-preview').src = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);
        document.getElementById('avatar-form').submit();
      }
    });
  }

  // Toggle hiện mật khẩu
  document.querySelectorAll('.toggle-password').forEach(function(btn) {
    btn.addEventListener('click', function() {
      const input = this.parentElement.querySelector('input');
      const icon = this.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    });
  });
});
</script>
<?php $this->stop() ?>
