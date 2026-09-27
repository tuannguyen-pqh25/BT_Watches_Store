<?php
/**
 * app/Controllers/Controller.php — Parent Controller
 * [C3] Plates Engine: render template, layout inheritance
 * [C5] sendPage() tích hợp Weak ETag + 304 Not Modified
 */

namespace App\Controllers;

use League\Plates\Engine;

class Controller
{
    protected Engine $view;

    public function __construct()
    {
        // [C3] Khởi tạo Plates Engine — trỏ vào thư mục Views
        $this->view = new Engine(VIEWS_PATH);
    }

    /**
     * Render view bằng Plates Engine và áp dụng HTTP Caching (ETag + 304 Not Modified)
     *
     * Quy trình:
     * 1. Plates render nội dung HTML từ file view (hỗ trợ layout inheritance)
     * 2. Tạo ETag (mã vân tay) từ nội dung
     * 3. So sánh với ETag trình duyệt gửi lên → nếu khớp → trả 304 (không gửi body)
     * 4. Nếu khác → gửi nội dung mới kèm ETag mới
     *
     * @param string $page  Đường dẫn view tương đối từ thư mục Views (ví dụ: 'pages/home')
     * @param array  $data  Biến truyền vào view (Plates tự extract thành biến riêng lẻ)
     */
    public function sendPage(string $page, array $data = []): void
    {
        $content = $this->view->render($page, $data);

        // Tạo mã vân tay (ETag) từ nội dung trang
        $etag = '"' . md5($content) . '"';

        // Nội dung chưa đổi → 304 Not Modified, tiết kiệm băng thông
        if (isset($_SERVER['HTTP_IF_NONE_MATCH'])
            && trim($_SERVER['HTTP_IF_NONE_MATCH']) === $etag) {
            // Nội dung không thay đổi → trả 304, không gửi lại body (tiết kiệm băng thông)
            header("HTTP/1.1 304 Not Modified");
            exit;
        }

        // Nội dung có thay đổi (hoặc lần đầu truy cập):
        // - Cache-Control: no-cache → trình duyệt luôn hỏi lại server trước khi dùng cache
        // - ETag: gửi mã vân tay mới để trình duyệt lưu cho lần kiểm tra sau
        header("Cache-Control: no-cache, must-revalidate");
        header("ETag: " . $etag);

        // Xuất toàn bộ nội dung HTML
        exit($content);
    }

    /**
     * Lưu giá trị form vào session khi validation lỗi (dùng với old())
     * @param array $except  Các key bỏ qua không lưu (vd: 'password', 'csrf_token')
     */
    protected function saveFormValues(array $data, array $except = []): void
    {
        $form = [];
        foreach ($data as $key => $value) {
            if (!in_array($key, $except, true)) {
                $form[$key] = $value;
            }
        }
        $_SESSION['form'] = $form;
    }

    /** Lấy lại giá trị form đã lưu (xóa khỏi session sau khi lấy) */
    protected function getSavedFormValues(): array
    {
        return session_get_once('form', []);
    }

    /** Chuyển hướng kèm flash data vào session
     *
     * @param string $url   URL đích
     * @param array  $data  Flash data lưu vào session (ví dụ: ['error' => 'Sai mật khẩu'])
     */
    protected function redirect(string $url, array $data = []): void
    {
        redirect($url, $data);
    }

    /** Hiển thị trang 404 */
    public function sendNotFound(): void
    {
        http_response_code(404);
        $this->sendPage("errors/404");
    }
}
