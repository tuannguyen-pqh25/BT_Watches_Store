<?php
/**
 * app/Views/pages/home.php — Trang chủ BT WATCH
 * [C1] Giao diện: Hero section, danh mục, sản phẩm nổi bật, CTA
 * [C3] league/plates: kế thừa layouts/default
 */
$this->layout("layouts/default", [
    "title"        => "BT WATCH — Trang chủ",
    "currentRoute" => "home",
]);
?>

<?php $this->start("page") ?>

<!-- [C1] Hero Banner Carousel (3 slider, tự động chuyển 5s, thuần Bootstrap) -->
<div id="heroCarousel" class="carousel slide mb-5" data-bs-ride="carousel" data-bs-interval="5000">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>

  <div class="carousel-inner">
    <!-- Slide 1 -->
    <div class="carousel-item active" data-bs-interval="5000">
      <div class="p-5 text-white bg-dark position-relative d-flex align-items-center" style="min-height: 60vh;">
        <img src="/images/home1.jpg" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover opacity-50" alt="Banner 1">
        <div class="container py-5 text-center text-md-start position-relative z-1">
          <div class="row align-items-center">
            <div class="col-lg-8">
              <span class="badge bg-warning text-dark mb-3 fs-6 px-3 py-2 text-uppercase" style="letter-spacing: 2px;">Bộ sưu tập 2026</span>
              <h1 class="display-3 fw-bold mb-3 text-white">
                Đồng Hồ Cao Cấp<br><span class="text-warning">Cho Quý Ông Hiện Đại</span>
              </h1>
              <p class="lead text-light mb-5 fs-4" style="font-weight: 300;">
                Nơi hội tụ những mẫu đồng hồ cao cấp dành cho người yêu thích sự chính xác, đẳng cấp và phong cách trường tồn.
              </p>
              <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-md-start">
                <a href="/products" class="btn btn-warning btn-lg fw-bold px-4 py-3 text-uppercase" style="letter-spacing: 1px;">
                  Mua ngay
                </a>
                <a href="/products" class="btn btn-outline-light btn-lg px-4 py-3 text-uppercase" style="letter-spacing: 1px;">
                  Xem sản phẩm
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Slide 2 -->
    <div class="carousel-item" data-bs-interval="5000">
      <div class="p-5 text-white bg-dark position-relative d-flex align-items-center" style="min-height: 60vh;">
        <img src="/images/home2.jpg" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover opacity-50" alt="Banner 2">
        <div class="container py-5 text-center text-md-start position-relative z-1">
          <div class="row align-items-center">
            <div class="col-lg-8">
              <span class="badge bg-warning text-dark mb-3 fs-6 px-3 py-2 text-uppercase" style="letter-spacing: 2px;">Phong Cách Thụy Sĩ</span>
              <h1 class="display-3 fw-bold mb-3 text-white">
                Kiệt Tác Cơ Học<br><span class="text-warning">Sang Trọng & Đẳng Cấp</span>
              </h1>
              <p class="lead text-light mb-5 fs-4" style="font-weight: 300;">
                Chế tác tinh xảo từ những thương hiệu đồng hồ hàng đầu thế giới với độ chính xác tuyệt hảo.
              </p>
              <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-md-start">
                <a href="/products" class="btn btn-warning btn-lg fw-bold px-4 py-3 text-uppercase" style="letter-spacing: 1px;">
                  Khám phá ngay
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Slide 3 -->
    <div class="carousel-item" data-bs-interval="5000">
      <div class="p-5 text-white bg-dark position-relative d-flex align-items-center" style="min-height: 60vh;">
        <img src="/images/home3.jpg" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover opacity-50" alt="Banner 3">
        <div class="container py-5 text-center text-md-start position-relative z-1">
          <div class="row align-items-center">
            <div class="col-lg-8">
              <span class="badge bg-warning text-dark mb-3 fs-6 px-3 py-2 text-uppercase" style="letter-spacing: 2px;">Dịch Vụ Hàng Đầu</span>
              <h1 class="display-3 fw-bold mb-3 text-white">
                Bảo Hành Chính Hãng<br><span class="text-warning">Giao Hàng Toàn Quốc</span>
              </h1>
              <p class="lead text-light mb-5 fs-4" style="font-weight: 300;">
                Cam kết 100% sản phẩm chính hãng cùng dịch vụ hậu mãi và hỗ trợ kỹ thuật trọn đời.
              </p>
              <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-md-start">
                <a href="/products" class="btn btn-warning btn-lg fw-bold px-4 py-3 text-uppercase" style="letter-spacing: 1px;">
                  Xem bộ sưu tập
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<div class="container">
  <!-- Stats -->
  <div class="row mb-5 text-center g-4">
    <div class="col-6 col-md-3">
      <div class="p-4 bg-white shadow-sm rounded border-0 h-100">
        <h3 class="text-dark fw-bold display-6 mb-2">500+</h3>
        <p class="text-secondary text-uppercase mb-0 fw-semibold" style="letter-spacing: 1px;">Sản phẩm</p>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="p-4 bg-white shadow-sm rounded border-0 h-100">
        <h3 class="text-dark fw-bold display-6 mb-2">1,847</h3>
        <p class="text-secondary text-uppercase mb-0 fw-semibold" style="letter-spacing: 1px;">Khách hàng</p>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="p-4 bg-white shadow-sm rounded border-0 h-100">
        <h3 class="text-dark fw-bold display-6 mb-2">3,264</h3>
        <p class="text-secondary text-uppercase mb-0 fw-semibold" style="letter-spacing: 1px;">Đơn hàng</p>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="p-4 bg-white shadow-sm rounded border-0 h-100">
        <h3 class="text-dark fw-bold display-6 mb-2">4.8★</h3>
        <p class="text-secondary text-uppercase mb-0 fw-semibold" style="letter-spacing: 1px;">Đánh giá</p>
      </div>
    </div>
  </div>

  <!-- Features Strip -->
  <div class="row mb-5 py-5 bg-light rounded shadow-sm">
    <div class="col-md-3 text-center mb-4 mb-md-0">
      <i class="fas fa-shield-alt text-warning fa-2x mb-3"></i>
      <h6 class="fw-bold text-dark text-uppercase" style="letter-spacing: 1px;">Cam kết Chính hãng</h6>
      <p class="text-secondary small mb-0">Có giấy tờ và serial đầy đủ</p>
    </div>
    <div class="col-md-3 text-center mb-4 mb-md-0">
      <i class="fas fa-truck text-warning fa-2x mb-3"></i>
      <h6 class="fw-bold text-dark text-uppercase" style="letter-spacing: 1px;">Giao hàng miễn phí</h6>
      <p class="text-secondary small mb-0">Đơn từ 5 triệu đồng</p>
    </div>
    <div class="col-md-3 text-center mb-4 mb-md-0">
      <i class="fas fa-undo-alt text-warning fa-2x mb-3"></i>
      <h6 class="fw-bold text-dark text-uppercase" style="letter-spacing: 1px;">Đổi trả 30 ngày</h6>
      <p class="text-secondary small mb-0">Không câu hỏi, không phí</p>
    </div>
    <div class="col-md-3 text-center">
      <i class="fas fa-tools text-warning fa-2x mb-3"></i>
      <h6 class="fw-bold text-dark text-uppercase" style="letter-spacing: 1px;">Bảo hành chính hãng</h6>
      <p class="text-secondary small mb-0">Hỗ trợ kỹ thuật trọn đời</p>
    </div>
  </div>

  <!-- Categories -->
  <div class="mb-5 py-4">
    <div class="text-center mb-5">
      <h2 class="fw-bold text-dark text-uppercase" style="letter-spacing: 2px;">Danh Mục Nổi Bật</h2>
      <div class="mx-auto bg-warning mt-3" style="width: 60px; height: 3px;"></div>
    </div>
    <div class="row g-4">
      <?php foreach($categories as $cat): ?>
      <div class="col-6 col-md-3">
        <a href="/products?category=<?= $this->e($cat->id) ?>" class="text-decoration-none">
          <div class="card bg-white border-0 shadow-sm text-center h-100 py-4 transition" style="transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='none';">
            <div class="card-body">
              <i class="fas <?= $this->e($cat->icon ?? 'fa-clock') ?> fa-3x text-dark mb-4"></i>
              <h5 class="card-title text-dark fw-bold"><?= $this->e($cat->name) ?></h5>
            </div>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Featured Products -->
  <div class="mb-5 py-4">
    <div class="text-center mb-5">
      <h2 class="fw-bold text-dark text-uppercase" style="letter-spacing: 2px;">Sản Phẩm Nổi Bật</h2>
      <div class="mx-auto bg-warning mt-3" style="width: 60px; height: 3px;"></div>
    </div>
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
      <?php foreach($products as $p): ?>
      <div class="col">
        <div class="card h-100 shadow-sm border-0" style="transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='none';">
          <div class="position-relative">
            <img src="<?= $this->e($p->image_path ?? 'https://images.unsplash.com/photo-1523170335258-f5ed11844a49?w=400&q=85') ?>"
                 class="card-img-top object-fit-cover" alt="<?= $this->e($p->name) ?>" style="height: 280px;">
          </div>
          <div class="card-body d-flex flex-column text-center p-4">
            <h5 class="card-title fw-bold text-dark mb-2"><?= $this->e($p->name) ?></h5>
            <p class="text-secondary small mb-3 text-uppercase" style="letter-spacing: 1px;">Đồng hồ cao cấp</p>
            <h5 class="text-warning fw-bold mt-auto mb-4"><?= vnd($p->price) ?></h5>
            <a href="/products/<?= $this->e($p->id) ?>" class="btn btn-dark w-100 fw-bold text-uppercase py-2" style="letter-spacing: 1px;">Xem chi tiết</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-5">
      <a href="/products" class="btn btn-outline-dark btn-lg px-5 py-3 text-uppercase fw-bold" style="letter-spacing: 1px;">
        Xem tất cả sản phẩm
      </a>
    </div>
  </div>

  <!-- CTA Section -->
  <div class="py-5 px-4 rounded bg-dark text-white text-center mb-5 shadow" style="background-image: linear-gradient(45deg, #000, #212529);">
    <h2 class="fw-bold mb-3 text-uppercase" style="letter-spacing: 2px;">Trải Nghiệm Sự Đẳng Cấp</h2>
    <p class="text-light opacity-75 mb-4 fs-5" style="font-weight: 300;">Khám phá hơn 300 mẫu đồng hồ cao cấp và sang trọng nhất</p>
    <a href="/products" class="btn btn-warning btn-lg fw-bold px-5 py-3 text-uppercase text-dark" style="letter-spacing: 1px;">
      Bộ sưu tập 2026
    </a>
  </div>
</div>

<?php $this->stop() ?>
