<?php
/**
 * app/Views/auth/login.php — Trang đăng nhập
 * [C1] Giao diện: Bootstrap 5
 * [C3] league/plates: kế thừa layouts/default
 * [C4] CSRF: csrf_field() trong form
 */
$this->layout("layouts/default", [
    "title"        => "BT WATCH — Đăng nhập",
    "currentRoute" => "login",
]);
?>

<?php $this->start("page") ?>
  <div class="container py-5 min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row justify-content-center w-100">
      <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-lg">
          <div class="card-body p-5">
            <div class="text-center mb-4">
              <i class="fas fa-clock text-dark fa-3x mb-3"></i>
              <h2 class="fw-bold text-dark text-uppercase" style="letter-spacing: 2px;">Đăng Nhập</h2>
              <p class="text-secondary mt-2">Chào mừng trở lại với BT WATCH</p>
            </div>

            <form action="/login" method="POST">
              <?= csrf_field() ?>

              <div class="mb-4">
                <label for="email" class="form-label text-dark fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Email</label>
                <input type="email" name="email" class="form-control bg-light border-0 shadow-none py-2 px-3" id="email" value="<?= $this->e(old('email')) ?>" required>
              </div>

              <div class="mb-4">
                <label for="password" class="form-label text-dark fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Mật khẩu</label>
                <input type="password" name="password" class="form-control bg-light border-0 shadow-none py-2 px-3" id="password" required>
              </div>

              <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="remember" checked>
                  <label class="form-check-label text-secondary small" for="remember">
                    Ghi nhớ đăng nhập
                  </label>
                </div>
                <a href="#" class="text-dark fw-bold text-decoration-none small hover-text-warning transition">Quên mật khẩu?</a>
              </div>

              <button type="submit" class="btn btn-dark btn-lg w-100 fw-bold mb-4 py-3 text-uppercase" style="letter-spacing: 1px;">
                Đăng Nhập
              </button>

              <div class="text-center">
                <p class="text-secondary small mb-0">Chưa có tài khoản? <a href="/register" class="text-warning fw-bold text-decoration-none">Đăng ký ngay</a></p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php $this->stop() ?>
