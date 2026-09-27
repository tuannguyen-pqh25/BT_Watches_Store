<?php
/**
 * app/Views/product/detail.php — Chi tiết sản phẩm
 * [C1] Giao diện: Bootstrap 5, tabs, thông số, đánh giá
 * [C3] league/plates: kế thừa layouts/default
 * [C4] CSRF: csrf_field() trong form thêm giỏ hàng
 * [C4] Đề phòng XSS: escape toàn bộ dữ liệu động bằng $this->e()
 *
 * Biến nhận từ Controller:
 *   - $product : object — thông tin sản phẩm
 */
$this->layout("layouts/default", [
    "title"        => "BT WATCH — " . ($product->name ?? 'Chi tiết sản phẩm'),
    "currentRoute" => "products",
]);
?>

<?php $this->start("page") ?>
  <div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.85rem;">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="/products" class="text-decoration-none text-secondary text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.85rem;">Sản phẩm</a></li>
        <li class="breadcrumb-item active text-warning text-uppercase fw-bold" aria-current="page" style="letter-spacing: 1px; font-size: 0.85rem;"><?= $this->e($product->name) ?></li>
      </ol>
    </nav>

    <div class="row g-5 mb-5">
      <!-- Hình ảnh sản phẩm -->
      <div class="col-lg-6">
        <img src="<?= $this->e($product->image_path ?? 'https://images.unsplash.com/photo-1523170335258-f5ed11844a49?w=600&q=85') ?>"
             alt="<?= $this->e($product->name) ?>"
             class="img-fluid rounded-0 object-fit-cover w-100 mb-3 shadow-sm" style="height: 500px;">
      </div>

      <!-- Thông tin sản phẩm -->
      <div class="col-lg-6 text-dark py-lg-3">
        <?php if ($product->stock_quantity > 0): ?>
          <span class="badge bg-success text-white mb-3 px-3 py-2 text-uppercase" style="letter-spacing: 1px;">Còn hàng</span>
        <?php else: ?>
          <span class="badge bg-danger text-white mb-3 px-3 py-2 text-uppercase" style="letter-spacing: 1px;">Hết hàng</span>
        <?php endif; ?>

        <h1 class="fw-bold mb-2 text-uppercase display-5" style="letter-spacing: 2px;"><?= $this->e($product->name) ?></h1>
        <p class="text-secondary mb-4 fs-5">Danh mục: <span class="text-dark fw-bold text-uppercase"><?= $this->e($product->category_name ?? 'Đồng hồ') ?></span></p>

        <h2 class="text-warning fw-bold mb-5 display-5"><?= vnd($product->price) ?></h2>

        <?php if (!empty($product->description)): ?>
          <p class="text-secondary mb-4"><?= $this->e($product->description) ?></p>
        <?php endif; ?>

        <!-- Form thêm giỏ hàng -->
        <?php if ($product->stock_quantity > 0): ?>
        <form action="/cart/add" method="POST" class="mb-5">
          <?= csrf_field() ?>
          <input type="hidden" name="product_id" value="<?= $this->e($product->id) ?>">

          <div class="row mb-4 g-4">
            <div class="col-md-6">
              <label class="form-label text-dark fw-bold text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Số lượng</label>
              <div class="input-group">
                <button class="btn btn-outline-secondary rounded-0" type="button" onclick="changeQty(-1)">−</button>
                <input type="number" name="quantity" id="qty-input" class="form-control bg-white text-dark border-secondary text-center shadow-none rounded-0"
                       value="1" min="1" max="<?= $this->e($product->stock_quantity) ?>">
                <button class="btn btn-outline-secondary rounded-0" type="button" onclick="changeQty(1)">+</button>
              </div>
              <small class="text-muted">Còn <?= $this->e($product->stock_quantity) ?> sản phẩm</small>
            </div>
          </div>

          <div class="d-grid gap-3">
            <button type="submit" class="btn btn-dark btn-lg fw-bold py-3 text-uppercase rounded-0" style="letter-spacing: 1px;">
              <i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ hàng
            </button>
          </div>
        </form>
        <?php endif; ?>

        <div class="mb-3 d-flex flex-column gap-3 pt-4 border-top border-secondary">
          <div class="d-flex align-items-center text-secondary"><i class="fas fa-check-circle text-dark fs-5 me-3"></i> <span>Cam kết 100% chính hãng, đền bù 200% nếu phát hiện hàng giả</span></div>
          <div class="d-flex align-items-center text-secondary"><i class="fas fa-truck text-dark fs-5 me-3"></i> <span>Giao hàng miễn phí toàn quốc (2-4 ngày)</span></div>
          <div class="d-flex align-items-center text-secondary"><i class="fas fa-shield-alt text-dark fs-5 me-3"></i> <span>Bảo hành chính hãng</span></div>
        </div>
      </div>
    </div>

    <!-- Product Details Tabs -->
    <div class="mb-5 mt-5">
      <ul class="nav nav-tabs border-bottom border-dark" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active bg-dark text-white fw-bold rounded-0 px-4 py-3 text-uppercase" style="letter-spacing: 1px; border: 1px solid #212529;" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button" role="tab">Thông số kỹ thuật</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link text-dark fw-bold rounded-0 px-4 py-3 text-uppercase hover-text-warning" style="letter-spacing: 1px; border: none; border-bottom: 1px solid transparent;" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc" type="button" role="tab">Mô tả</button>
        </li>
      </ul>

      <div class="tab-content text-dark border border-top-0 border-secondary p-4 bg-white">
        <div class="tab-pane fade show active" id="specs" role="tabpanel">
          <div class="row">
            <div class="col-lg-8">
              <table class="table table-bordered border-light table-hover mt-3">
                <tbody>
                  <tr>
                    <td class="text-secondary fw-semibold w-25 bg-light p-3">Tên sản phẩm</td>
                    <td class="p-3 fw-bold"><?= $this->e($product->name) ?></td>
                  </tr>
                  <tr>
                    <td class="text-secondary fw-semibold bg-light p-3">Danh mục</td>
                    <td class="p-3"><?= $this->e($product->category_name ?? 'N/A') ?></td>
                  </tr>
                  <tr>
                    <td class="text-secondary fw-semibold bg-light p-3">Giá</td>
                    <td class="p-3 text-warning fw-bold"><?= vnd($product->price) ?></td>
                  </tr>
                  <tr>
                    <td class="text-secondary fw-semibold bg-light p-3">Tồn kho</td>
                    <td class="p-3"><?= $this->e($product->stock_quantity) ?> sản phẩm</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="desc" role="tabpanel">
          <div class="mt-3">
            <p class="text-secondary"><?= $this->e($product->description ?? 'Chưa có mô tả cho sản phẩm này.') ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php $this->stop() ?>

<?php $this->start("page_specific_js") ?>
<script>
// Tăng/giảm số lượng
function changeQty(delta) {
  var input = document.getElementById('qty-input');
  var val = parseInt(input.value) + delta;
  var max = parseInt(input.max);
  if (val >= 1 && val <= max) input.value = val;
}
</script>
<?php $this->stop() ?>
