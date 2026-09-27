<?php
/**
 * app/Views/layouts/admin.php — Layout Master cho Admin Panel (Plates Template)
 * [C1] Giao diện: Sidebar + Topbar + Content area (Bootstrap 5 utilities)
 * [C3] league/plates: layout inheritance — tất cả admin Views kế thừa file này
 *
 * Sections:
 *   - "page"              → nội dung chính từng trang admin (BẮT BUỘC)
 *   - "page_specific_css" → CSS riêng từng trang (tuỳ chọn)
 *   - "page_specific_js"  → JS riêng từng trang (tuỳ chọn)
 *
 * Variables:
 *   - $title       → Tiêu đề trang (mặc định: 'Admin Dashboard — BT WATCH')
 *   - $activePage  → Trang đang active trên sidebar (ví dụ: 'dashboard', 'products')
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $this->e($title ?? 'Admin Dashboard — BT WATCH') ?></title>

  <!-- Google Fonts: Outfit -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

  <!-- [C1] Minimal Custom CSS — chỉ những gì Bootstrap KHÔNG có sẵn -->
  <style>
    :root { --sidebar-w: 260px; }
    body { font-family: 'Outfit', sans-serif; }
    /* Sidebar fixed width */
    #sidebar { min-width: var(--sidebar-w); max-width: var(--sidebar-w); transition: margin-left .3s; }
    /* Sidebar nav active gold border */
    #sidebar .nav-link { border-left: 3px solid transparent; transition: all .3s; }
    #sidebar .nav-link:hover { color: #D4AF37 !important; background: rgba(212,175,55,.05); border-left-color: #D4AF37; }
    #sidebar .nav-link.active { color: #fff !important; background: rgba(212,175,55,.1); border-left-color: #D4AF37; font-weight: 600; }
    #sidebar .nav-link.active i { color: #D4AF37; }
    /* Profile avatar */
    .profile-avatar { width: 38px; height: 38px; object-fit: cover; border: 2px solid #D4AF37; }
    /* Responsive sidebar collapse */
    @media (max-width: 991.98px) {
      #sidebar { margin-left: calc(-1 * var(--sidebar-w)); }
      #sidebar.show { margin-left: 0; }
      .menu-toggle { display: block !important; }
    }
  </style>

  <!-- Section CSS riêng từng trang -->
  <?= $this->section('page_specific_css') ?>
</head>
<body class="bg-light">

  <div class="d-flex min-vh-100" id="wrapper">

    <!-- ═══ SIDEBAR ════════════════════════════════════════════════════════ -->
    <nav id="sidebar" class="bg-dark text-white">
      <div class="sidebar-header px-4 py-3 border-bottom border-secondary border-opacity-25">
        <a href="/admin" class="text-decoration-none d-flex align-items-center gap-2">
          <img src="/images/logo_opt.png"
               alt="BT WATCH"
               width="38" height="38"
               style="object-fit: cover; border-radius: 50%; filter: drop-shadow(0 0 4px rgba(255,193,7,0.4));">
          <span class="fs-5 fw-bold text-warning text-uppercase" style="letter-spacing: 1px;">BT WATCH</span>
        </a>
      </div>

      <?php $active = $activePage ?? ''; ?>
      <ul class="nav flex-column py-3">
        <li class="nav-item">
          <a class="nav-link text-white-50 d-flex align-items-center px-4 py-2 <?= $active === 'dashboard' ? 'active' : '' ?>"
             href="/admin">
            <i class="fas fa-chart-line me-3" style="width:20px;text-align:center;"></i>
            <span>Dashboard</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white-50 d-flex align-items-center px-4 py-2 <?= $active === 'users' ? 'active' : '' ?>"
             href="/admin/users">
            <i class="fas fa-users me-3" style="width:20px;text-align:center;"></i>
            <span>Người dùng</span>
          </a>
        </li>


        <li class="nav-item">
          <a class="nav-link text-white-50 d-flex align-items-center px-4 py-2 <?= in_array($active, ['products', 'product_add', 'product_edit']) ? 'active' : '' ?>"
             href="/admin/products">
            <i class="fas fa-box-open me-3" style="width:20px;text-align:center;"></i>
            <span>Sản phẩm</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white-50 d-flex align-items-center px-4 py-2 <?= in_array($active, ['categories', 'category_add', 'category_edit']) ? 'active' : '' ?>"
             href="/admin/categories">
            <i class="fas fa-tags me-3" style="width:20px;text-align:center;"></i>
            <span>Danh mục</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white-50 d-flex align-items-center px-4 py-2 <?= in_array($active, ['orders', 'order_detail']) ? 'active' : '' ?>"
             href="/admin/orders">
            <i class="fas fa-shopping-cart me-3" style="width:20px;text-align:center;"></i>
            <span>Đơn hàng</span>
          </a>
        </li>

        <li class="nav-item mt-4 border-top border-secondary border-opacity-25 pt-3">
          <a class="nav-link text-white-50 d-flex align-items-center px-4 py-2" href="/" target="_blank">
            <i class="fas fa-external-link-alt me-3" style="width:20px;text-align:center;"></i>
            <span>Xem cửa hàng</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-danger d-flex align-items-center px-4 py-2" href="/logout">
            <i class="fas fa-sign-out-alt me-3" style="width:20px;text-align:center;"></i>
            <span>Đăng xuất</span>
          </a>
        </li>
      </ul>
    </nav>

    <div class="d-flex flex-column w-100" id="content-wrapper">

      <!-- ═══ TOPBAR ═══════════════════════════════════════════════════════ -->
      <nav class="bg-white border-bottom shadow-sm d-flex align-items-center justify-content-between px-4" style="height: 70px;">
        <div class="d-flex align-items-center">
          <button type="button" id="sidebarCollapse" class="btn btn-link text-dark fs-5 p-0 me-3 d-none menu-toggle">
            <i class="fas fa-bars"></i>
          </button>

          <div class="position-relative d-none d-md-block" style="width: 300px;">
            <i class="fas fa-search position-absolute text-muted" style="left:15px;top:50%;transform:translateY(-50%);"></i>
            <input type="text" class="form-control bg-light border-0 rounded-pill ps-5"
                   placeholder="Tìm kiếm sản phẩm, đơn hàng...">
          </div>
        </div>

        <div class="d-flex align-items-center">
          <!-- Online Status Badge -->
          <div class="me-3 d-flex align-items-center bg-light px-3 py-1 rounded-pill border shadow-sm">
            <span class="spinner-grow spinner-grow-sm text-success me-2" role="status" style="width: 0.5rem; height: 0.5rem;"></span>
            <small class="fw-semibold text-dark" style="font-size: 0.8rem;"><span id="admin-online-count">1</span> online</small>
          </div>

          <!-- Notification Bell -->
          <a href="/admin/orders" class="text-dark me-4 position-relative fs-5">
            <i class="fas fa-bell"></i>
            <span id="pending-orders-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; display: none;">
              0
            </span>
          </a>

          <!-- Store link -->
          <a href="/" target="_blank" class="btn btn-sm btn-outline-dark me-3 d-none d-md-inline-block fw-semibold text-uppercase" style="font-size:.8rem;letter-spacing:.5px;">
            <i class="fas fa-external-link-alt me-1"></i> Xem cửa hàng
          </a>

          <!-- User dropdown -->
          <?php $currentUser = AUTHGUARD()->user(); ?>
          <div class="dropdown">
            <a class="d-flex align-items-center text-decoration-none text-dark" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="me-2 d-none d-md-inline-block fw-semibold"><?= $this->e($currentUser->full_name ?? 'Admin') ?></span>
              <img src="<?= $this->e($currentUser->avatar_path ?? '/images/logo_opt.png') ?>" alt="Admin Avatar" class="rounded-circle profile-avatar">
            </a>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-3" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="/admin"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
              <li><a class="dropdown-item" href="/admin/profile"><i class="fas fa-user-cog me-2"></i> Hồ sơ</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="/logout"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
            </ul>
          </div>
        </div>
      </nav>

      <!-- ═══ FLASH MESSAGES ═══════════════════════════════════════════════ -->
      <div class="p-4 flex-grow-1">
        <?php if (isset($_SESSION['success'])): ?>
          <div class="alert alert-success border-0 py-3 mb-4 shadow-sm rounded-3 alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i><?= session_get_once('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger border-0 py-3 mb-4 shadow-sm rounded-3 alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i><?= session_get_once('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <!-- ═══ NỘI DUNG TRANG ════════════════════════════════════════════ -->
        <?= $this->section('page') ?>

      </div> <!-- End Main Content -->

      <!-- Footer Copyright Bar -->
      <footer class="bg-white border-top py-3 text-center mt-auto">
        <div class="container-fluid">
          <p class="mb-0 text-muted small">&copy; <?= date('Y') ?> <strong class="text-dark">BT WATCH</strong>. Designed & Developed by Nguyễn Thanh Bình & Nguyễn Minh Tuấn. Can Tho University.</p>
        </div>
      </footer>

    </div> <!-- End Content Wrapper -->
  </div> <!-- End Wrapper -->

  <!-- Bootstrap 5 Bundle JS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- jQuery & DataTables JS -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <!-- Khởi tạo DataTables cho class .datatable -->
  <script>
    $(document).ready(function() {
        $('.datatable').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/vi.json"
            }
        });
    });
  </script>

  <!-- Sidebar Toggle Script -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const sidebarCollapse = document.getElementById("sidebarCollapse");
      const sidebar = document.getElementById("sidebar");

      if (sidebarCollapse && sidebar) {
        sidebarCollapse.addEventListener("click", function() {
          sidebar.classList.toggle("show");
        });
      }
    });
  </script>

  <!-- Section JS riêng từng trang -->
  <?= $this->section('page_specific_js') ?>

  <!-- [C5] SSE Admin: Đơn hàng mới & Người dùng Online -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gửi ping báo online mỗi 30 giây để Admin cũng được tính là online
        const sendPing = () => {
            fetch('/ping', { method: 'POST' }).catch(() => {});
        };
        sendPing();
        setInterval(sendPing, 30000);

        if (window.EventSource) {
            // 1. SSE Nghe số đơn hàng pending (Cái chuông)
            const orderEvtSource = new EventSource('/admin/sse-pending');
            orderEvtSource.onmessage = function(event) {
                try {
                    const data = JSON.parse(event.data);
                    const badge = document.getElementById('pending-orders-badge');
                    if (badge && data.pending_orders !== undefined) {
                        if (data.pending_orders > 0) {
                            badge.textContent = data.pending_orders;
                            badge.style.display = 'block';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                } catch(e) {}
            };

            // 2. SSE Nghe số người đang online (Topbar badge)
            const onlineEvtSource = new EventSource('/sse-online');
            onlineEvtSource.onmessage = function(event) {
                try {
                    const data = JSON.parse(event.data);
                    const countEl = document.getElementById('admin-online-count');
                    if (countEl && data.count !== undefined) {
                        countEl.textContent = data.count;
                    }
                } catch(e) {}
            };
        }
    });
  </script>
</body>
</html>
