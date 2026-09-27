<?php
/**
 * app/Views/admin/products.php — Danh sách sản phẩm (Admin, Plates Template)
 * [C1] Giao diện: Bảng sản phẩm luxury, tìm kiếm, phân trang
 * [C3] Kế thừa layout 'layouts/admin' qua league/plates
 *
 * Biến nhận từ Admin\ProductController@index:
 *   - $products (array of objects)
 */
$this->layout("layouts/admin", [
    "title"      => "BT WATCH — Quản lý sản phẩm",
    "activePage" => "products"
]);
?>

<?php $this->start("page") ?>

<div class="row mb-4">
  <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <h2 class="text-uppercase fw-bold m-0" style="letter-spacing: 1px;">Danh sách sản phẩm</h2>
      <p class="text-muted small m-0">Quản lý kho hàng, thông tin chi tiết và hiển thị đồng hồ.</p>
    </div>
    <a href="/admin/products/create" class="btn btn-warning text-dark fw-bold"><i class="fas fa-plus me-2"></i> Thêm sản phẩm mới</a>
  </div>
</div>

<!-- Products Table -->
<div class="row">
  <div class="col-12">
    <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
      <div class="table-responsive">
        <table class="table table-hover datatable">
          <thead>
            <tr>
              <th style="width: 80px;">ID</th>
              <th style="width: 100px;">Hình ảnh</th>
              <th>Tên đồng hồ</th>
              <th>Danh mục</th>
              <th>Giá niêm yết</th>
              <th>Tồn kho</th>
              <th>Trạng thái</th>
              <th class="text-end" style="width: 180px;">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                  <td><strong class="text-dark"><?= $this->e($p->id) ?></strong></td>
                  <td>
                    <?php
                    $imgUrl = $p->image_path ?? '/images/placeholder-watch.jpg';
                    ?>
                    <img src="<?= $this->e($imgUrl) ?>" class="rounded shadow-sm border border-light" style="width: 55px; height: 55px; object-fit: cover;">
                  </td>
                  <td>
                    <div class="fw-bold text-dark"><?= $this->e($p->name) ?></div>
                    <small class="text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;"><?= $this->e($p->brand ?? 'Unbranded') ?></small>
                  </td>
                  <td>
                    <span class="badge bg-light text-dark border px-3 py-2 small"><?= $this->e($p->category_name ?? 'Chưa phân loại') ?></span>
                  </td>
                  <td>
                    <div class="fw-bold text-dark"><?= vnd($p->price) ?></div>
                  </td>
                  <td>
                    <strong class="<?= ($p->stock_quantity ?? 0) <= 5 ? 'text-danger' : 'text-dark' ?>"><?= $this->e($p->stock_quantity ?? 0) ?></strong>
                    <?php if (($p->stock_quantity ?? 0) <= 5): ?>
                      <br><small class="text-danger small fw-semibold">Sắp hết</small>
                    <?php endif; ?>
                  </td>
                  <td>
                    <span class="badge bg-<?= $p->status === 'show' ? 'success' : 'secondary' ?> rounded-pill px-3 py-2">
                      <?= $p->status === 'show' ? 'Hiển thị' : 'Ẩn' ?>
                    </span>
                  </td>
                  <td class="text-end">
                    <a href="/admin/products/<?= $p->id ?>/edit" class="btn btn-sm btn-light border-dark me-2">
                      <i class="fas fa-edit text-dark"></i>
                    </a>
                    <form action="/admin/products/<?= $p->id ?>/delete" method="POST" class="d-inline"
                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn btn-sm btn-light border-danger">
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
</div>

<?php $this->stop() ?>
