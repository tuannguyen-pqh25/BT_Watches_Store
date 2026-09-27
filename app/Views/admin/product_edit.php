<?php
/**
 * app/Views/admin/product_edit.php — Form chỉnh sửa sản phẩm (Plates Template)
 * [C1] Giao diện: Form 2 cột (thông tin + kho/ảnh)
 * [C3] Kế thừa layout 'layouts/admin'
 *
 * Biến nhận từ Admin\ProductController@edit:
 *   - $product    (object)
 *   - $categories (array of objects)
 */
$this->layout("layouts/admin", [
    "title"      => "BT WATCH — Sửa sản phẩm",
    "activePage" => "product_edit"
]);
?>

<?php $this->start("page") ?>

<div class="row mb-4">
  <div class="col-12 d-flex justify-content-between align-items-center">
    <div>
      <h2 class="text-uppercase fw-bold m-0" style="letter-spacing: 1px;">Sửa sản phẩm</h2>
      <p class="text-muted small m-0">Thay đổi thông tin chiếc đồng hồ: ID #<?= $this->e($product->id) ?></p>
    </div>
    <a href="/admin/products" class="btn btn-outline-dark fw-semibold"><i class="fas fa-arrow-left me-2"></i> Quay lại</a>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
      <form action="/admin/products/<?= $this->e($product->id) ?>/update" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="row g-4">
          <!-- Left columns fields -->
          <div class="col-lg-8">
            <div class="mb-3">
              <label for="name" class="form-label fw-bold text-uppercase small text-secondary">Tên đồng hồ *</label>
              <input type="text" class="form-control bg-light py-2" id="name" name="name" required
                     value="<?= $this->e($product->name) ?>">
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label for="category_id" class="form-label fw-bold text-uppercase small text-secondary">Danh mục *</label>
                <select class="form-select bg-light py-2" id="category_id" name="category_id" required>
                  <?php foreach ($categories as $cat): ?>
                    <option value="<?= $this->e($cat->id) ?>"
                      <?= $product->category_id == $cat->id ? 'selected' : '' ?>>
                      <?= $this->e($cat->name) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label for="brand" class="form-label fw-bold text-uppercase small text-secondary">Thương hiệu</label>
                <input type="text" class="form-control bg-light py-2" id="brand" name="brand"
                       value="<?= $this->e($product->brand ?? '') ?>">
              </div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-12">
                <label for="price" class="form-label fw-bold text-uppercase small text-secondary">Giá bán lẻ (₫) *</label>
                <input type="number" step="1000" class="form-control bg-light py-2" id="price" name="price" required
                       value="<?= $this->e(intval($product->price)) ?>">
              </div>
            </div>

            <div class="mb-3">
              <label for="description" class="form-label fw-bold text-uppercase small text-secondary">Mô tả chi tiết sản phẩm</label>
              <textarea class="form-control bg-light" id="description" name="description" rows="8"><?= $this->e($product->description ?? '') ?></textarea>
            </div>
          </div>

          <!-- Right columns fields -->
          <div class="col-lg-4">
            <div class="card bg-light border-0 p-4 mb-4">
              <h5 class="fw-bold text-uppercase mb-4 text-dark" style="font-size: 0.9rem; letter-spacing: 1px;">Kho hàng & Trạng thái</h5>

              <div class="mb-4">
                <label for="stock_quantity" class="form-label fw-bold text-uppercase small text-secondary">Số lượng tồn kho *</label>
                <input type="number" class="form-control bg-white py-2" id="stock_quantity" name="stock_quantity" required min="0"
                       value="<?= $this->e($product->stock_quantity ?? 0) ?>">
              </div>

              <div class="mb-2">
                <label class="form-label fw-bold text-uppercase small text-secondary d-block">Trạng thái hiển thị</label>

                <div class="form-check form-check-inline me-4">
                  <input class="form-check-input" type="radio" name="status" id="status-show" value="show"
                    <?= $product->status === 'show' ? 'checked' : '' ?>>
                  <label class="form-check-label" for="status-show">Hiển thị</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="status" id="status-hide" value="hide"
                    <?= $product->status === 'hide' ? 'checked' : '' ?>>
                  <label class="form-check-label" for="status-hide">Ẩn sản phẩm</label>
                </div>
              </div>
            </div>

            <div class="card bg-light border-0 p-4">
              <h5 class="fw-bold text-uppercase mb-3 text-dark" style="font-size: 0.9rem; letter-spacing: 1px;">Hình ảnh sản phẩm</h5>
              
              <!-- Ảnh hiện tại -->
              <?php if (!empty($product->image_path)): ?>
                <div class="mb-3 text-center bg-white p-2 border rounded">
                  <img src="<?= $this->e($product->image_path) ?>" class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                </div>
              <?php endif; ?>

              <!-- Upload file ảnh mới -->
              <div class="mb-3">
                <label for="image" class="form-label fw-bold text-uppercase small text-secondary">Upload ảnh mới (Thay thế ảnh cũ)</label>
                <input type="file" class="form-control bg-white py-2" id="image" name="image"
                       accept="image/jpeg,image/png,image/gif,image/webp"
                       onchange="previewImage(this)">
                <small class="text-muted d-block mt-1">JPG, PNG, GIF, WebP — tối đa 2MB</small>
              </div>

              <!-- Preview ảnh -->
              <div id="image-preview" class="mb-3 text-center bg-white p-2 border rounded" style="display: none;">
                <img id="preview-img" src="" class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
              </div>

              <!-- Hoặc nhập URL ảnh -->
              <div class="mb-3">
                <label for="image_url" class="form-label fw-bold text-uppercase small text-secondary">Hoặc nhập URL ảnh</label>
                <input type="text" class="form-control bg-white py-2" id="image_url" name="image_url"
                       placeholder="https://example.com/image.jpg" value="">
                <small class="text-muted d-block mt-1">Giữ trống nếu không đổi ảnh hoặc đã upload file</small>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-4 pt-3 border-top d-flex gap-3 justify-content-end">
          <a href="/admin/products" class="btn btn-outline-dark fw-semibold">Hủy bỏ</a>
          <button type="submit" class="btn btn-warning text-dark fw-bold px-4 py-2">Lưu thay đổi</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $this->stop() ?>

<?php $this->start("page_specific_js") ?>
<script>
// [C5] Preview ảnh trước khi upload — tránh upload nhầm file
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const img = document.getElementById('preview-img');
    if (input.files && input.files[0]) {
        if (input.files[0].size > 2 * 1024 * 1024) {
            alert('Ảnh vượt quá 2MB. Vui lòng chọn ảnh nhỏ hơn.');
            input.value = '';
            preview.style.display = 'none';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}
</script>
<?php $this->stop() ?>
