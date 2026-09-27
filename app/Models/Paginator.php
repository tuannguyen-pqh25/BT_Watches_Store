<?php
/**
 * app/Paginator.php — Class phân trang (adapt từ Lab 3)
 * [C3] Tổ chức mã nguồn: class tiện ích tái sử dụng
 *
 * Tính toán: tổng số trang, offset cho SQL LIMIT/OFFSET,
 * trang trước/sau, danh sách số trang dạng sliding window.
 */

namespace App\Models;

class Paginator
{
    public int $totalPages;
    public int $recordOffset;

    public function __construct(
        public int $recordsPerPage,
        public int $totalRecords,
        public int $currentPage = 1,
    ) {
        // Tính tổng số trang (làm tròn lên)
        $this->totalPages = $totalRecords > 0
            ? (int)ceil($totalRecords / $recordsPerPage)
            : 1;

        // Clamp currentPage vào phạm vi hợp lệ [1, totalPages]
        if ($this->currentPage < 1) {
            $this->currentPage = 1;
        }
        if ($this->currentPage > $this->totalPages) {
            $this->currentPage = $this->totalPages;
        }

        // Tính offset cho SQL: OFFSET = (trang_hiện_tại - 1) * số_bản_ghi_mỗi_trang
        $this->recordOffset = ($this->currentPage - 1) * $this->recordsPerPage;
    }

    /**
     * Trả về số trang trước (hoặc false nếu đang ở trang 1)
     */
    public function getPrevPage(): int|bool
    {
        return $this->currentPage > 1 ? $this->currentPage - 1 : false;
    }

    /**
     * Trả về số trang sau (hoặc false nếu đang ở trang cuối)
     */
    public function getNextPage(): int|bool
    {
        return $this->currentPage < $this->totalPages ? $this->currentPage + 1 : false;
    }

    /**
     * Trả về mảng các số trang hiển thị (sliding window)
     * Ví dụ: trang 5, length=5 → [3, 4, 5, 6, 7]
     *
     * @param int $length Số lượng nút trang hiển thị (mặc định 5)
     */
    public function getPages(int $length = 5): array
    {
        $halfLength = (int)floor($length / 2);
        $pageStart  = $this->currentPage - $halfLength;
        $pageEnd    = $this->currentPage + $halfLength;

        // Nếu vượt qua đầu → đẩy về 1 và mở rộng cuối
        if ($pageStart < 1) {
            $pageStart = 1;
            $pageEnd   = $length;
        }

        // Nếu vượt qua cuối → kéo về totalPages và mở rộng đầu
        if ($pageEnd > $this->totalPages) {
            $pageEnd   = $this->totalPages;
            $pageStart = $pageEnd - $length + 1;
            if ($pageStart < 1) {
                $pageStart = 1;
            }
        }

        return range((int)$pageStart, (int)$pageEnd);
    }
}
