<?php
/**
 * app/Views/product/product_list.php — Danh sách sản phẩm
 * [C1] Giao diện: Bootstrap 5, tìm kiếm, lọc danh mục
 * [C3] league/plates: kế thừa layouts/default
 *
 * Biến nhận từ Controller:
 *   - $products    : array — danh sách sản phẩm
 *   - $categories  : array — danh sách danh mục
 *   - $keyword     : string — từ khoá tìm kiếm
 *   - $categoryId  : int|null — ID danh mục đang lọc
 */
$this->layout("layouts/default", [
    "title"        => "BT WATCH — Sản phẩm",
    "currentRoute" => "products",
]);
?>

<?php $this->start("page") ?>
  <div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-secondary text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.85rem;">Trang chủ</a></li>
        <li class="breadcrumb-item active text-warning text-uppercase fw-bold" aria-current="page" style="letter-spacing: 1px; font-size: 0.85rem;">Sản phẩm</li>
      </ol>
    </nav>

    <h1 class="fw-bold mb-2 text-dark text-uppercase" style="letter-spacing: 2px;">Bộ Sưu Tập <span class="text-warning">Đồng Hồ</span></h1>
    <p class="text-secondary mb-5 fs-5">Khám phá hàng trăm mẫu đồng hồ cao cấp mang phong cách sang trọng và đẳng cấp.</p>

    <!-- Tìm kiếm + Lọc danh mục -->
    <form action="/products" method="GET" class="row mb-5">
      <div class="col-md-6 mb-3 mb-md-0">
        <input type="text" name="q" class="form-control bg-white text-dark border-secondary shadow-sm py-2"
               placeholder="Tìm kiếm đồng hồ..." value="<?= $this->e($keyword ?? '') ?>">
      </div>
      <div class="col-md-4">
        <select name="category" class="form-select bg-white text-dark border-secondary shadow-sm py-2" onchange="this.form.submit()">
          <option value="">Tất cả danh mục</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $this->e($cat->id) ?>" <?= ($categoryId ?? null) == $cat->id ? 'selected' : '' ?>>
              <?= $this->e($cat->name) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-dark w-100 py-2 fw-bold text-uppercase" style="letter-spacing: 1px;">
          <i class="fas fa-search me-1"></i> Tìm
        </button>
      </div>
    </form>

    <!-- Danh sách sản phẩm -->
    <?php if (empty($products)): ?>
      <div class="text-center py-5">
        <i class="fas fa-search fa-3x text-secondary mb-3"></i>
        <h4 class="text-secondary">Không tìm thấy sản phẩm nào</h4>
        <p class="text-muted">Thử tìm kiếm với từ khoá khác hoặc chọn danh mục khác.</p>
        <a href="/products" class="btn btn-outline-dark">Xem tất cả sản phẩm</a>
      </div>
    <?php else: ?>
      <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4 mb-4">
        <?php foreach ($products as $p): ?>
          <div class="col">
            <div class="card h-100 shadow-sm border-0 transition hover-shadow" style="transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)';" onmouseout="this.style.transform='none';">
              <div style="position: relative; overflow: hidden; height: 280px;">
                <img src="<?= $this->e($p->image_path ?? 'https://images.unsplash.com/photo-1523170335258-f5ed11844a49?w=400&q=85') ?>"
                     class="card-img-top object-fit-cover w-100 h-100" alt="<?= $this->e($p->name) ?>">
              </div>
              <div class="card-body d-flex flex-column p-4 text-center">
                <small class="text-secondary text-uppercase fw-semibold" style="letter-spacing: 1px;"><?= $this->e($p->brand ?? 'Đồng hồ cao cấp') ?></small>
                <h5 class="card-title fw-bold my-2 text-dark"><?= $this->e($p->name) ?></h5>
                <h5 class="text-warning fw-bold mt-auto mb-4"><?= vnd($p->price) ?></h5>
                <a href="/products/<?= $this->e($p->id) ?>" class="btn btn-dark w-100 fw-bold text-uppercase py-2" style="letter-spacing: 1px;">Xem chi tiết</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Phân trang -->
      <?php $this->insert('partials/pagination', [
          'paginator'   => $paginator,
          'queryParams' => $queryParams,
      ]) ?>

    <?php endif; ?>
  </div>
<?php $this->stop() ?>
