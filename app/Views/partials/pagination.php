<?php
/**
 * app/Views/partials/pagination.php — Partial phân trang (Plates)
 * [C1] Bootstrap 5 Pagination component
 *
 * Biến nhận từ insert():
 *   - $paginator   : App\Models\Paginator — object phân trang
 *   - $queryParams : array — query params cần giữ (q, category...)
 */

// Không hiển thị nếu chỉ có 1 trang
if ($paginator->totalPages <= 1) return;

// Hàm tạo URL cho từng trang (giữ nguyên filter params)
$buildUrl = function (int $page) use ($queryParams) {
    $params = $queryParams;
    $params['page'] = $page;
    return '/products?' . http_build_query($params);
};
?>

<nav aria-label="Phân trang sản phẩm" class="mt-4 mb-2">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
    <!-- Thông tin trang -->
    <small class="text-secondary">
      Trang <strong><?= $paginator->currentPage ?></strong> / <strong><?= $paginator->totalPages ?></strong>
      — <?= number_format($paginator->totalRecords) ?> sản phẩm
    </small>

    <!-- Pagination controls -->
    <ul class="pagination mb-0">
      <!-- Nút Trang đầu -->
      <?php if ($paginator->currentPage > 1): ?>
        <li class="page-item">
          <a class="page-link text-dark border-secondary" href="<?= $buildUrl(1) ?>" title="Trang đầu">
            <i class="fas fa-angle-double-left"></i>
          </a>
        </li>
      <?php endif; ?>

      <!-- Nút Trước -->
      <li class="page-item <?= $paginator->getPrevPage() === false ? 'disabled' : '' ?>">
        <a class="page-link text-dark border-secondary"
           href="<?= $paginator->getPrevPage() !== false ? $buildUrl($paginator->getPrevPage()) : '#' ?>"
           aria-label="Trang trước">
          <i class="fas fa-chevron-left"></i>
        </a>
      </li>

      <!-- Các số trang (sliding window) -->
      <?php foreach ($paginator->getPages(5) as $p): ?>
        <li class="page-item <?= $p === $paginator->currentPage ? 'active' : '' ?>">
          <a class="page-link <?= $p === $paginator->currentPage ? 'bg-dark border-dark text-white' : 'text-dark border-secondary' ?>"
             href="<?= $buildUrl($p) ?>">
            <?= $p ?>
          </a>
        </li>
      <?php endforeach; ?>

      <!-- Nút Sau -->
      <li class="page-item <?= $paginator->getNextPage() === false ? 'disabled' : '' ?>">
        <a class="page-link text-dark border-secondary"
           href="<?= $paginator->getNextPage() !== false ? $buildUrl($paginator->getNextPage()) : '#' ?>"
           aria-label="Trang sau">
          <i class="fas fa-chevron-right"></i>
        </a>
      </li>

      <!-- Nút Trang cuối -->
      <?php if ($paginator->currentPage < $paginator->totalPages): ?>
        <li class="page-item">
          <a class="page-link text-dark border-secondary" href="<?= $buildUrl($paginator->totalPages) ?>" title="Trang cuối">
            <i class="fas fa-angle-double-right"></i>
          </a>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
