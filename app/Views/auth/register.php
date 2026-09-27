<?php
/**
 * app/Views/auth/register.php — Trang đăng ký
 * [C1] Giao diện: Bootstrap 5
 * [C3] league/plates: kế thừa layouts/default
 * [C4] CSRF: csrf_field() trong form
 */
$this->layout("layouts/default", [
    "title"        => "BT WATCH — Đăng ký",
    "currentRoute" => "register",
]);
?>

<?php $this->start("page") ?>
  <div class="container py-5 min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row justify-content-center w-100">
      <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-lg mt-4 mb-5">
          <div class="card-body p-5">
            <div class="text-center mb-4">
              <i class="fas fa-user-plus text-dark fa-3x mb-3"></i>
              <h2 class="fw-bold text-dark text-uppercase" style="letter-spacing: 2px;">Đăng Ký</h2>
              <p class="text-secondary mt-2">Tạo tài khoản mới tại BT WATCH</p>
            </div>

            <form action="/register" method="POST">
              <?= csrf_field() ?>

              <div class="mb-3">
                <label for="fullname" class="form-label text-dark fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Họ và tên</label>
                <input type="text" name="full_name" class="form-control bg-light border-0 shadow-none py-2 px-3" id="fullname" value="<?= $this->e(old('full_name')) ?>" placeholder="Nhập họ và tên của bạn" required>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label text-dark fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Email</label>
                <input type="email" name="email" class="form-control bg-light border-0 shadow-none py-2 px-3" id="email" value="<?= $this->e(old('email')) ?>" placeholder="Nhập địa chỉ email" required>
              </div>

              <div class="mb-3">
                <label for="phone" class="form-label text-dark fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Số điện thoại</label>
                <input type="text" name="phone" class="form-control bg-light border-0 shadow-none py-2 px-3" id="phone" value="<?= $this->e(old('phone')) ?>" placeholder="Nhập số điện thoại" required>
              </div>

              <div class="mb-3">
                <label for="password" class="form-label text-dark fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Mật khẩu</label>
                <input type="password" name="password" class="form-control bg-light border-0 shadow-none py-2 px-3" id="password" placeholder="Tạo mật khẩu" required>
              </div>

              <div class="mb-3">
                <label for="repassword" class="form-label text-dark fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" class="form-control bg-light border-0 shadow-none py-2 px-3" id="repassword" placeholder="Nhập lại mật khẩu" required>
              </div>

              <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label text-secondary small" for="terms">
                  Tôi đồng ý với <a href="#" class="text-dark fw-bold text-decoration-none hover-text-warning transition">Điều khoản &amp; Dịch vụ</a>
                </label>
              </div>

              <button type="submit" class="btn btn-dark btn-lg w-100 fw-bold mb-4 py-3 text-uppercase" style="letter-spacing: 1px;">
                Đăng Ký
              </button>

              <div class="text-center">
                <p class="text-secondary small mb-0">Đã có tài khoản? <a href="/login" class="text-warning fw-bold text-decoration-none">Đăng nhập</a></p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php $this->stop() ?>
