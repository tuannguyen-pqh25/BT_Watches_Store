<?php
/**
 * app/Views/layouts/default.php — Layout Master (Plates Template)
 * [C1] Giao diện: Bootstrap 5 responsive, navbar, footer
 * [C3] league/plates: layout inheritance — tất cả Views con kế thừa file này
 *
 * Sections:
 *   - "page"              → nội dung chính từng trang (BẮT BUỘC)
 *   - "page_specific_css" → CSS riêng từng trang (tuỳ chọn)
 *   - "page_specific_js"  → JS riêng từng trang (tuỳ chọn)
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="BT WATCH - Nơi hội tụ những mẫu đồng hồ cao cấp">
  <meta name="keywords" content="đồng hồ, luxury watch, Rolex, Omega, Seiko">
  <title><?= $this->e($title ?? 'BT WATCH') ?></title>

  <!-- Preload logo để hiển thị nhanh -->
  <link rel="preload" href="/images/logo_opt.png" as="image">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- [C1] CSS chung cho toàn site -->
  <style>
    .hover-text-warning:hover { color: #ffc107 !important; }
    .transition { transition: all 0.3s ease; }
    .hover-shadow:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }

    /* Toast notification styling */
    .toast-container { z-index: 1080; }
  </style>

  <!-- Section CSS riêng từng trang -->
  <?= $this->section('page_specific_css') ?>
</head>
<body class="bg-light text-dark">

  <!-- ═══ NAVBAR ═══════════════════════════════════════════════════════════ -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="/">
        <img src="/images/logo_opt.png"
             alt="BT WATCH"
             width="40" height="40"
             style="object-fit: cover; border-radius: 50%; filter: drop-shadow(0 0 4px rgba(255,193,7,0.4));">
        <span class="fw-bold text-warning" style="font-size: 1.15rem; letter-spacing: 1px;">BT WATCH</span>
      </a>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
              aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item">
            <a class="nav-link text-white text-uppercase fw-semibold mx-2 <?= ($currentRoute ?? '') === 'home' ? 'text-warning' : '' ?>"
               href="/">Trang chủ</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white text-uppercase fw-semibold mx-2 <?= ($currentRoute ?? '') === 'products' ? 'text-warning' : '' ?>"
               href="/products">Sản phẩm</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white text-uppercase fw-semibold mx-2 <?= ($currentRoute ?? '') === 'about' ? 'text-warning' : '' ?>"
               href="/about">Giới thiệu</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white text-uppercase fw-semibold mx-2 <?= ($currentRoute ?? '') === 'contact' ? 'text-warning' : '' ?>"
               href="/contact">Liên hệ</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white mx-2 fs-5" href="/cart">
              <i class="fas fa-shopping-bag hover-text-warning"></i>
              <?php
              // Hiển thị số lượng sản phẩm trong giỏ hàng
              $cartCount = 0;
              if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                  foreach ($_SESSION['cart'] as $item) {
                      $cartCount += $item['quantity'] ?? 0;
                  }
              }
              if ($cartCount > 0): ?>
                <span class="badge bg-warning text-dark rounded-pill" style="font-size: 0.65rem;"><?= $cartCount ?></span>
              <?php endif; ?>
            </a>
          </li>

          <?php if (AUTHGUARD()->isUserLoggedIn()): ?>
            <?php $currentUser = AUTHGUARD()->user(); ?>
            <!-- [C4] Người dùng đã đăng nhập — hiển thị dropdown -->
            <li class="nav-item dropdown ms-lg-3 mt-2 mt-lg-0">
                <a class="nav-link dropdown-toggle text-warning fw-bold d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                  <img src="<?= $currentUser->avatar_path ?? ($currentUser->role === 'admin' ? '/images/logo_opt.png' : '/images/default_avatar.jpg') ?>" alt="User Avatar" class="rounded-circle border border-warning shadow-sm" style="width: 35px; height: 35px; object-fit: cover;">
                  <span class="ms-2 d-none d-md-inline-block"><?= $this->e($currentUser->full_name) ?></span>
                </a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                <?php if (($currentUser->role ?? '') === 'admin'): ?>
                  <li><a class="dropdown-item" href="/admin"><i class="fas fa-tachometer-alt me-2"></i>Quản trị</a></li>
                  <li><hr class="dropdown-divider"></li>
                <?php endif; ?>
                <?php if (($currentUser->role ?? '') === 'admin'): ?>
                  <li><a class="dropdown-item" href="/admin/profile"><i class="fas fa-user-cog me-2"></i>Hồ sơ</a></li>
                <?php else: ?>
                  <li><a class="dropdown-item" href="/profile"><i class="fas fa-user-cog me-2"></i>Hồ sơ</a></li>
                <?php endif; ?>
                <li><a class="dropdown-item" href="/orders"><i class="fas fa-box me-2"></i>Đơn hàng</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="/logout"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
              </ul>
            </li>
          <?php else: ?>
            <!-- Chưa đăng nhập -->
            <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
              <a class="btn btn-warning fw-bold text-dark px-4 rounded-pill shadow-sm" href="/login">Đăng nhập</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- ═══ TOAST NOTIFICATIONS (Flash Messages) ════════════════════════════ -->
  <!-- [C1] Bootstrap Toast — hiển thị thông báo thành công/lỗi từ session flash -->
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <?php if (isset($_SESSION['success'])): ?>
      <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true"
           data-bs-autohide="true" data-bs-delay="4000" id="toast-success">
        <div class="d-flex">
          <div class="toast-body fw-semibold">
            <i class="fas fa-check-circle me-2"></i><?= session_get_once('success') ?>
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true"
           data-bs-autohide="true" data-bs-delay="5000" id="toast-error">
        <div class="d-flex">
          <div class="toast-body fw-semibold">
            <i class="fas fa-exclamation-circle me-2"></i><?= session_get_once('error') ?>
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <!-- ═══ NỘI DUNG CHÍNH ════════════════════════════════════════════════════ -->
  <main class="min-vh-100">
    <?= $this->section('page') ?>
  </main>

  <!-- ═══ FOOTER ════════════════════════════════════════════════════════════ -->
  <footer class="mt-auto py-5 bg-dark text-white border-top border-warning">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-4 col-md-6">
          <div class="d-flex align-items-center gap-2 mb-4">
            <img src="/images/logo_opt.png" alt="BT WATCH" width="48" height="48" style="object-fit: cover; border-radius: 50%; filter: drop-shadow(0 0 6px rgba(255,193,7,0.5));">
            <span class="text-warning fw-bold fs-4">BT WATCH</span>
          </div>
          <p class="text-light opacity-75">Nơi hội tụ những mẫu đồng hồ cao cấp dành cho quý ông yêu thích sự chính xác, đẳng cấp và phong cách trường tồn.</p>
          <div class="mt-4">
            <a href="#" class="text-white hover-text-warning me-3 fs-5 transition"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-white hover-text-warning me-3 fs-5 transition"><i class="fab fa-instagram"></i></a>
            <a href="#" class="text-white hover-text-warning me-3 fs-5 transition"><i class="fab fa-tiktok"></i></a>
            <a href="#" class="text-white hover-text-warning fs-5 transition"><i class="fab fa-youtube"></i></a>
          </div>
        </div>
        <div class="col-lg-2 col-md-6">
          <h6 class="text-uppercase mb-4 text-warning fw-bold" style="letter-spacing: 2px;">Bộ sưu tập</h6>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="/products" class="text-light opacity-75 text-decoration-none hover-text-warning transition">Đồng hồ Nam</a></li>
            <li class="mb-2"><a href="/products" class="text-light opacity-75 text-decoration-none hover-text-warning transition">Sản phẩm mới</a></li>
            <li class="mb-2"><a href="/products" class="text-light opacity-75 text-decoration-none hover-text-warning transition">Bán chạy</a></li>
          </ul>
        </div>
        <div class="col-lg-2 col-md-6">
          <h6 class="text-uppercase mb-4 text-warning fw-bold" style="letter-spacing: 2px;">Hỗ trợ</h6>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="/contact" class="text-light opacity-75 text-decoration-none hover-text-warning transition">Liên hệ</a></li>
            <li class="mb-2"><a href="/about" class="text-light opacity-75 text-decoration-none hover-text-warning transition">Về chúng tôi</a></li>
            <li class="mb-2"><a href="/admin" class="text-light opacity-75 text-decoration-none hover-text-warning transition">Quản lý website</a></li>
            <li class="mb-2"><a href="#" class="text-light opacity-75 text-decoration-none hover-text-warning transition">FAQ</a></li>
          </ul>
        </div>
        <div class="col-lg-4 col-md-6">
          <h6 class="text-uppercase mb-4 text-warning fw-bold" style="letter-spacing: 2px;">Thông tin liên hệ</h6>
          <p class="text-light opacity-75 mb-3"><i class="fas fa-phone me-3 text-warning"></i>+84 354496983</p>
          <p class="text-light opacity-75 mb-3"><i class="fas fa-envelope me-3 text-warning"></i>btwatch@gmail.com</p>
          <p class="text-light opacity-75"><i class="fas fa-map-marker-alt me-3 text-warning"></i>Trường CNTT & Truyền thông, Đại học Cần Thơ</p>
        </div>
      </div>
      <hr class="border-secondary my-4 opacity-25">
      <div class="row">
        <div class="col-md-6 text-light opacity-50 small text-center text-md-start mb-2 mb-md-0">
          &copy; <?= date('Y') ?> BT WATCH. Nguyễn Thanh Bình và Nguyễn Minh Tuấn.
          <span class="ms-3 text-warning" style="font-size: 0.85rem;"><i class="fas fa-circle text-success me-1" style="font-size: 0.6rem;"></i><span id="online-count">1 người online</span></span>
        </div>
        <div class="col-md-6 text-md-end text-light opacity-50 small text-center text-md-end">
          BT WATCH Store - Đồ án CT27501
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- [C1] Khởi tạo Toast tự động -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Tự động hiển thị Toast notifications
      document.querySelectorAll('.toast.show').forEach(function(toastEl) {
        var toast = new bootstrap.Toast(toastEl);
        toast.show();
      });
    });
  </script>

  <!-- [C5] Presence (Ping & SSE) -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gửi ping báo online mỗi 15 giây
        const sendPing = () => {
            fetch('/ping', { method: 'POST' }).catch(() => {});
        };
        sendPing();
        setInterval(sendPing, 30000);

        // SSE nghe số người online
        if (window.EventSource) {
            const evtSource = new EventSource('/sse-online');
            evtSource.onmessage = function(event) {
                try {
                    const data = JSON.parse(event.data);
                    const el = document.getElementById('online-count');
                    if (el) el.textContent = data.count + " người online";
                } catch(e) {}
            };
        }
    });
  </script>

  <!-- Section JS riêng từng trang -->
  <?= $this->section('page_specific_js') ?>
</body>
</html>
