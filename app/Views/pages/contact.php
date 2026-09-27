<?php
/**
 * app/Views/pages/contact.php — Trang liên hệ
 * [C1] Giao diện: Bootstrap 5
 * [C3] league/plates: kế thừa layouts/default
 */
$this->layout("layouts/default", [
    "title"        => "BT WATCH — Liên hệ",
    "currentRoute" => "contact",
]);
?>

<?php $this->start("page") ?>
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.85rem;">Trang chủ</a></li>
        <li class="breadcrumb-item active text-warning text-uppercase fw-bold" aria-current="page" style="letter-spacing: 1px; font-size: 0.85rem;">Liên hệ</li>
      </ol>
    </nav>

    <h1 class="fw-bold mb-5">Liên Hệ Với Chúng Tôi</h1>

    <div class="row g-5 mb-5">
      <div class="col-lg-6">
        <h5 class="fw-bold text-warning mb-3">Thông tin liên hệ</h5>

        <div class="mb-4">
          <h6 class="fw-bold mb-2"><i class="fas fa-map-marker-alt text-warning me-2"></i>Địa chỉ</h6>
          <p class="text-muted">
            Trường Công nghệ Thông tin và Truyền thông<br>
            Đại học Cần Thơ, Khu II, Đường 3/2<br>
            Ninh Kiều, Cần Thơ
          </p>
        </div>

        <div class="mb-4">
          <h6 class="fw-bold mb-2"><i class="fas fa-phone text-warning me-2"></i>Điện thoại</h6>
          <p class="text-muted">0123 456 789 (Hỗ trợ 24/7)</p>
        </div>

        <div class="mb-4">
          <h6 class="fw-bold mb-2"><i class="fas fa-envelope text-warning me-2"></i>Email</h6>
          <p class="text-muted">
            btwatch@ctu.edu.vn<br>
            support@btwatch.com
          </p>
        </div>

        <div class="mb-4">
          <h6 class="fw-bold mb-3">Mạng xã hội</h6>
          <div class="d-flex gap-3">
            <a href="#" class="btn btn-outline-warning btn-sm"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="btn btn-outline-warning btn-sm"><i class="fab fa-instagram"></i></a>
            <a href="#" class="btn btn-outline-warning btn-sm"><i class="fab fa-tiktok"></i></a>
            <a href="#" class="btn btn-outline-warning btn-sm"><i class="fab fa-youtube"></i></a>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <h5 class="fw-bold text-warning mb-3">Gửi tin nhắn</h5>
        <form>
          <div class="mb-3">
            <label class="form-label">Họ và tên</label>
            <input type="text" class="form-control" placeholder="Nhập họ và tên của bạn" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" placeholder="email@example.com" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tiêu đề</label>
            <input type="text" class="form-control" placeholder="Tiêu đề tin nhắn" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nội dung</label>
            <textarea class="form-control" rows="5" placeholder="Nội dung tin nhắn..." required></textarea>
          </div>
          <button type="submit" class="btn btn-warning fw-bold w-100">
            <i class="fas fa-paper-plane me-2"></i>Gửi tin nhắn
          </button>
        </form>
      </div>
    </div>
</div>
<?php $this->stop() ?>
