<?php
$pageTitle = 'BT WATCH — Tin tức & Sự kiện';
$currentRoute = 'news';
include VIEWS_PATH . '/header.php';
?>

  <div class="container py-5 min-vh-100">
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/" class="text-decoration-none text-secondary text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.85rem;">Trang chủ</a></li>
        <li class="breadcrumb-item active text-warning text-uppercase fw-bold" aria-current="page" style="letter-spacing: 1px; font-size: 0.85rem;">Tin tức</li>
      </ol>
    </nav>

    <div class="text-center mb-5">
      <h1 class="fw-bold mb-2 text-dark text-uppercase" style="letter-spacing: 2px;">Tạp Chí <span class="text-warning">BT WATCH</span></h1>
      <p class="text-secondary fs-5 max-w-600 mx-auto">Xu hướng đồng hồ cao cấp, kiến thức chuyên sâu và những câu chuyện di sản thương hiệu.</p>
    </div>

    <!-- Featured Article -->
    <div class="card border-0 rounded-0 shadow-sm overflow-hidden mb-5 bg-white text-dark">
      <div class="row g-0">
        <div class="col-lg-7">
          <img src="https://images.unsplash.com/photo-1547996160-81dfa63595aa?w=900&q=80" class="img-fluid w-100 h-100 object-fit-cover" style="min-height: 350px;" alt="Featured News">
        </div>
        <div class="col-lg-5 d-flex flex-column justify-content-center p-4 p-md-5">
          <span class="text-warning text-uppercase fw-bold mb-2 small" style="letter-spacing: 2px;">Xu hướng thiết kế</span>
          <h2 class="fw-bold mb-3 text-uppercase"><a href="#" class="text-dark text-decoration-none hover-text-warning transition">Nghệ Thuật Chế Tác Đồng Hồ Cơ Khí Năm 2026</a></h2>
          <p class="text-secondary mb-4">Khám phá những cải tiến vượt trội trong công nghệ chế tác bộ chuyển động cơ học tự động của các hãng đồng hồ xa xỉ trong năm nay...</p>
          <div class="d-flex align-items-center justify-content-between mt-auto">
            <span class="text-muted small"><i class="far fa-calendar-alt me-2"></i>21 Tháng 6, 2026</span>
            <a href="#" class="text-warning fw-bold text-decoration-none hover-text-dark transition">Đọc thêm <i class="fas fa-arrow-right ms-1"></i></a>
          </div>
        </div>
      </div>
    </div>

    <!-- News Grid -->
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card border-0 rounded-0 shadow-sm h-100 bg-white text-dark">
          <img src="https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?w=500&q=80" class="card-img-top rounded-0 object-fit-cover" style="height: 240px;" alt="News 1">
          <div class="card-body p-4 d-flex flex-column">
            <span class="text-warning text-uppercase fw-bold mb-2 small" style="letter-spacing: 1px;">Kiến thức đồng hồ</span>
            <h5 class="card-title fw-bold text-uppercase mb-3"><a href="#" class="text-dark text-decoration-none hover-text-warning transition">Cách Bảo Quản Đồng Hồ Cơ Khí Đúng Chuẩn</a></h5>
            <p class="card-text text-secondary small mb-4">Làm thế nào để giữ cho chiếc đồng hồ cơ đắt giá của bạn luôn hoạt động chính xác và bóng bẩy như mới mua?</p>
            <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top border-light">
              <span class="text-muted small"><i class="far fa-calendar-alt me-2"></i>18 Tháng 6, 2026</span>
              <a href="#" class="text-warning fw-bold text-decoration-none hover-text-dark transition small">Đọc thêm <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card border-0 rounded-0 shadow-sm h-100 bg-white text-dark">
          <img src="https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?w=500&q=80" class="card-img-top rounded-0 object-fit-cover" style="height: 240px;" alt="News 2">
          <div class="card-body p-4 d-flex flex-column">
            <span class="text-warning text-uppercase fw-bold mb-2 small" style="letter-spacing: 1px;">Sự kiện nổi bật</span>
            <h5 class="card-title fw-bold text-uppercase mb-3"><a href="#" class="text-dark text-decoration-none hover-text-warning transition">Triển Lãm Đồng Hồ Đẳng Cấp Thế Giới</a></h5>
            <p class="card-text text-secondary small mb-4">Hàng loạt thương hiệu cao cấp giới thiệu những siêu phẩm giới hạn trị giá triệu đô tại hội chợ triển lãm thời gian.</p>
            <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top border-light">
              <span class="text-muted small"><i class="far fa-calendar-alt me-2"></i>15 Tháng 6, 2026</span>
              <a href="#" class="text-warning fw-bold text-decoration-none hover-text-dark transition small">Đọc thêm <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card border-0 rounded-0 shadow-sm h-100 bg-white text-dark">
          <img src="https://images.unsplash.com/photo-1619134778706-7015533a6150?w=500&q=80" class="card-img-top rounded-0 object-fit-cover" style="height: 240px;" alt="News 3">
          <div class="card-body p-4 d-flex flex-column">
            <span class="text-warning text-uppercase fw-bold mb-2 small" style="letter-spacing: 1px;">Bộ sưu tập</span>
            <h5 class="card-title fw-bold text-uppercase mb-3"><a href="#" class="text-dark text-decoration-none hover-text-warning transition">Xu Hướng Đồng Hồ Thể Thao Cao Cấp</a></h5>
            <p class="card-text text-secondary small mb-4">Các mẫu đồng hồ thép không gỉ thể thao tích hợp dây đeo đang chiếm trọn trái tim của giới sưu tầm sành điệu.</p>
            <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top border-light">
              <span class="text-muted small"><i class="far fa-calendar-alt me-2"></i>10 Tháng 6, 2026</span>
              <a href="#" class="text-warning fw-bold text-decoration-none hover-text-dark transition small">Đọc thêm <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php include VIEWS_PATH . '/footer.php'; ?>
