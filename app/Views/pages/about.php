<?php
/**
 * app/Views/pages/about.php — Trang giới thiệu
 * [C1] Giao diện: Bootstrap 5
 * [C3] league/plates: kế thừa layouts/default
 */
$this->layout("layouts/default", [
    "title"        => "BT WATCH — Về chúng tôi",
    "currentRoute" => "about",
]);
?>

<?php $this->start("page") ?>
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.85rem;">Trang chủ</a></li>
        <li class="breadcrumb-item active text-warning text-uppercase fw-bold" aria-current="page" style="letter-spacing: 1px; font-size: 0.85rem;">Về chúng tôi</li>
      </ol>
    </nav>

    <h1 class="fw-bold mb-5">Về BT WATCH</h1>

    <div class="row g-5 mb-5">
      <div class="col-lg-6">
        <img src="/images/about.png" alt="About Us" class="img-fluid rounded-3">
      </div>
      <div class="col-lg-6">
        <h3 class="fw-bold text-warning mb-3">Câu chuyện của chúng tôi</h3>
        <p class="text-muted mb-3">
          BT WATCH là cửa hàng đồng hồ trực tuyến được xây dựng bởi hai sinh viên ngành Công nghệ Thông tin
          tại Trường Công nghệ Thông tin và Truyền thông - Đại học Cần Thơ.
        </p>
        <p class="text-muted mb-3">
          Với sứ mệnh mang những mẫu đồng hồ cao cấp nổi tiếng thế giới đến gần hơn với
          các khách hàng Việt Nam, BT WATCH cam kết cung cấp những sản phẩm chính hãng 100%, được kiểm chứng
          kỹ lưỡng và đi kèm đầy đủ giấy tờ, bảo hành.
        </p>
        <p class="text-muted">
          Đôi khi, thời gian không chỉ là một khái niệm - nó là một tác phẩm nghệ thuật, một biểu tượng của
          sự thành công và phong cách. BT WATCH luôn tôn trọng giá trị này.
        </p>
      </div>
    </div>

    <hr class="border-secondary my-5">

    <div class="mb-5">
      <h3 class="fw-bold text-warning mb-4">Tại sao chọn BT WATCH?</h3>
      <div class="row g-4">
        <div class="col-md-6">
          <div class="p-4 rounded-3" style="background: rgba(212,175,55,0.05); border: 1px solid rgba(212,175,55,0.2);">
            <h6 class="fw-bold text-warning mb-2"><i class="fas fa-check-circle me-2"></i>Chính hãng 100%</h6>
            <p class="text-muted small">Tất cả sản phẩm đều được kiểm chứng chặt chẽ và có giấy tờ đầy đủ từ nhà sản xuất.</p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-4 rounded-3" style="background: rgba(212,175,55,0.05); border: 1px solid rgba(212,175,55,0.2);">
            <h6 class="fw-bold text-warning mb-2"><i class="fas fa-shipping-fast me-2"></i>Giao hàng nhanh</h6>
            <p class="text-muted small">Miễn phí vận chuyển, giao hàng toàn quốc 2-5 ngày làm việc.</p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-4 rounded-3" style="background: rgba(212,175,55,0.05); border: 1px solid rgba(212,175,55,0.2);">
            <h6 class="fw-bold text-warning mb-2"><i class="fas fa-exchange-alt me-2"></i>Đổi trả dễ dàng</h6>
            <p class="text-muted small">30 ngày đổi trả không điều kiện, không phí cho sản phẩm nguyên vẹn.</p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-4 rounded-3" style="background: rgba(212,175,55,0.05); border: 1px solid rgba(212,175,55,0.2);">
            <h6 class="fw-bold text-warning mb-2"><i class="fas fa-headset me-2"></i>Hỗ trợ 24/7</h6>
            <p class="text-muted small">Đội ngũ nhân viên chuyên nghiệp sẵn sàng hỗ trợ bạn mọi lúc.</p>
          </div>
        </div>
      </div>
    </div>

    <hr class="border-secondary my-5">

    <div class="mb-5">
      <h3 class="fw-bold text-warning mb-4">Đội ngũ sáng lập</h3>
      <div class="row g-4">
        <div class="col-md-6">
          <div class="text-center">
            <div class="mb-3">
              <img src="/images/Binh.jpg" alt="Founder" class="rounded-circle" style="width: 120px; height: 120px;">
            </div>
            <h6 class="fw-bold">Nguyễn Thanh Bình_B2306518</h6>
            <p class="text-muted small">Sáng lập viên</p>
            <p class="text-muted small">Sinh viên Công nghệ thông tin K49 - Đại học Cần Thơ</p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="text-center">
            <div class="mb-3">
              <img src="/images/Tuan.png" alt="Founder" class="rounded-circle" style="width: 120px; height: 120px;">
            </div>
            <h6 class="fw-bold">Nguyễn Minh Tuấn_B2306598</h6>
            <p class="text-muted small">Sáng lập viên</p>
            <p class="text-muted small">Sinh viên Công nghệ thông tin K49 - Đại học Cần Thơ</p>
          </div>
        </div>
      </div>
    </div>
</div>
<?php $this->stop() ?>
