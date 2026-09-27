<?php
/**
 * app/Views/admin/categories.php — Quản lý danh mục (Admin, Plates Template)
 * [C1] Giao diện: Bảng danh mục + Form thêm/sửa trực tiếp bên cạnh
 * [C3] Kế thừa layout 'layouts/admin'
 *
 * Biến nhận từ Admin\CategoryController:
 *   - $categories (array of objects)
 *   - $category (object, optional - khi sửa)
 *   - $isCreate (bool, optional - khi click nút thêm)
 */
$this->layout("layouts/admin", [
    "title"      => "BT WATCH — Quản lý danh mục sản phẩm",
    "activePage" => "categories"
]);
$isEditing   = isset($category);
$currentIcon = $isEditing ? $category->icon : old('icon', 'fa-clock');
?>

<?php $this->start("page") ?>

<div class="row mb-4">
  <div class="col-12 d-flex justify-content-between align-items-center">
    <div>
      <h2 class="text-uppercase fw-bold m-0" style="letter-spacing: 1px;">Quản Lý Danh Mục</h2>
      <p class="text-muted small m-0">Phân loại sản phẩm đồng hồ cho các chiến dịch bán hàng.</p>
    </div>
    <?php if ($isEditing): ?>
      <a href="/admin/categories" class="btn btn-outline-dark fw-semibold">
        <i class="fas fa-plus me-2"></i>Chế độ thêm mới
      </a>
    <?php endif; ?>
  </div>
</div>

<div class="row g-4">

  <!-- Cột bên trái: Danh sách danh mục -->
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm rounded-3 p-4">
      <div class="table-responsive">
        <table class="table table-hover datatable">
          <thead>
            <tr>
              <th style="width: 60px;">ID</th>
              <th style="width: 50px;">Icon</th>
              <th>Tên danh mục</th>
              <th>Mô tả</th>
              <th class="text-end" style="width: 120px;">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($categories as $cat): ?>
              <tr class="<?= ($isEditing && $category->id === $cat->id) ? 'table-warning' : '' ?>">
                <td><strong class="text-dark"><?= $this->e($cat->id) ?></strong></td>
                <td class="text-center">
                  <i class="fas <?= $this->e($cat->icon ?? 'fa-clock') ?> text-warning fs-5"></i>
                </td>
                <td><span class="fw-bold"><?= $this->e($cat->name) ?></span></td>
                <td class="text-secondary small"><?= $this->e($cat->description ?? '(Không có mô tả)') ?></td>
                <td class="text-end">
                  <a href="/admin/categories/<?= $cat->id ?>/edit"
                     class="btn btn-sm btn-light border-dark me-1" title="Sửa">
                    <i class="fas fa-edit text-dark"></i>
                  </a>
                  <form action="/admin/categories/<?= $cat->id ?>/delete" method="POST" class="d-inline"
                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-light border-danger" title="Xóa">
                      <i class="fas fa-trash-alt text-danger"></i>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Cột bên phải: Biểu mẫu Thêm/Sửa -->
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm rounded-3 p-4 border-top border-warning border-4">
      <h4 class="fw-bold mb-4 text-dark text-uppercase" style="font-size: 1.1rem; letter-spacing: 0.5px;">
        <?= $isEditing ? 'Cập nhật Danh mục' : 'Thêm Danh mục Mới' ?>
      </h4>

      <form action="<?= $isEditing ? '/admin/categories/' . $category->id . '/update' : '/admin/categories/store' ?>"
            method="POST">
        <?= csrf_field() ?>
        <!-- Hidden input lưu class icon được chọn -->
        <input type="hidden" id="iconInput" name="icon" value="<?= $this->e($currentIcon) ?>">

        <!-- Tên danh mục -->
        <div class="mb-3">
          <label for="name" class="form-label fw-bold text-uppercase small text-secondary">Tên danh mục *</label>
          <input type="text" class="form-control bg-light py-2" id="name" name="name" required
                 placeholder="Ví dụ: Đồng hồ Nam"
                 value="<?= $isEditing ? $this->e($category->name) : old('name') ?>">
        </div>

        <!-- Icon Picker -->
        <div class="mb-3">
          <label class="form-label fw-bold text-uppercase small text-secondary">Icon danh mục</label>

          <!-- Preview icon đang chọn -->
          <div class="d-flex align-items-center gap-3 p-3 mb-2 border rounded-3 bg-white">
            <i class="fas <?= $this->e($currentIcon) ?> text-warning fs-3" id="iconPreviewEl"></i>
            <div>
              <div class="fw-semibold text-dark small" id="iconPreviewName"><?= $this->e($currentIcon) ?></div>
              <div class="text-muted" style="font-size: 0.72rem;">Icon đang chọn</div>
            </div>
          </div>

          <!-- Lưới icon (overflow scroll, dùng Bootstrap flex + overflow-auto) -->
          <div class="border rounded-3 p-2 bg-light overflow-auto" style="max-height: 205px;">
            <div class="row row-cols-5 g-1" id="iconPickerGrid">
              <?php
              $iconList = [
                  'fa-clock'          => 'Classic',
                  'fa-stopwatch'      => 'Sport',
                  'fa-gem'            => 'Luxury',
                  'fa-compass'        => 'Minimal',
                  'fa-history'        => 'Vintage',
                  'fa-crown'          => 'Premium',
                  'fa-star'           => 'Nổi bật',
                  'fa-fire'           => 'Hot',
                  'fa-bolt'           => 'Tốc độ',
                  'fa-heart'          => 'Yêu thích',
                  'fa-shield-alt'     => 'Bền bỉ',
                  'fa-trophy'         => 'Thành tích',
                  'fa-tag'            => 'Khuyến mãi',
                  'fa-globe'          => 'Quốc tế',
                  'fa-mountain'       => 'Phiêu lưu',
                  'fa-water'          => 'Lặn biển',
                  'fa-sun'            => 'Mùa hè',
                  'fa-moon'           => 'Tối giản',
                  'fa-business-time'  => 'Công sở',
                  'fa-graduation-cap' => 'Học thuật',
              ];
              foreach ($iconList as $iconClass => $iconLabel):
                  $isActive = ($currentIcon === $iconClass);
                  $btnClass = $isActive
                      ? 'btn btn-warning border-0 w-100 p-1 d-flex flex-column align-items-center gap-1'
                      : 'btn btn-outline-secondary border-0 w-100 p-1 d-flex flex-column align-items-center gap-1';
              ?>
              <div class="col">
                <button type="button"
                        class="<?= $btnClass ?> icon-option rounded-2"
                        data-icon="<?= $iconClass ?>"
                        title="<?= $iconClass ?>">
                  <i class="fas <?= $iconClass ?>" style="font-size: 1.1rem;"></i>
                  <span style="font-size: 0.6rem; line-height: 1.2;"><?= $iconLabel ?></span>
                </button>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="text-muted mt-1" style="font-size: 0.75rem;">
            <i class="fas fa-hand-pointer me-1"></i>Nhấn vào icon để chọn.
          </div>
        </div>

        <!-- Mô tả -->
        <div class="mb-4">
          <label for="description" class="form-label fw-bold text-uppercase small text-secondary">Mô tả danh mục</label>
          <textarea class="form-control bg-light" id="description" name="description" rows="4"
                    placeholder="Nhập mô tả ngắn cho danh mục này..."><?= $isEditing ? $this->e($category->description) : old('description') ?></textarea>
        </div>

        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-warning text-dark fw-bold py-2 text-uppercase" style="letter-spacing: 0.5px;">
            <?= $isEditing ? 'Lưu thay đổi' : 'Thêm danh mục' ?>
          </button>
          <?php if ($isEditing): ?>
            <a href="/admin/categories" class="btn btn-outline-dark py-2 text-uppercase"
               style="letter-spacing: 0.5px; font-size: 0.85rem;">Hủy bỏ</a>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const options     = document.querySelectorAll('.icon-option');
    const hiddenInput = document.getElementById('iconInput');
    const previewEl   = document.getElementById('iconPreviewEl');
    const previewName = document.getElementById('iconPreviewName');

    options.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const icon = this.dataset.icon;

            // Reset tất cả về outline
            options.forEach(function (el) {
                el.classList.remove('btn-warning');
                el.classList.add('btn-outline-secondary');
            });

            // Highlight icon vừa chọn
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-warning');

            // Cập nhật hidden input & preview
            hiddenInput.value    = icon;
            previewEl.className  = 'fas ' + icon + ' text-warning fs-3';
            previewName.textContent = icon;
        });
    });
});
</script>

<?php $this->stop() ?>
