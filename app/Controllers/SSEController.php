<?php
/**
 * app/Controllers/SSEController.php — Xử lý các kết nối Realtime/SSE
 */

namespace App\Controllers;

class SSEController extends Controller
{
    /**
     * [C5] Endpoint nhận ping từ client để báo đang online
     * POST /ping
     */
    public function ping(): void
    {
        ping_online();
        header('Content-Type: application/json');
        echo json_encode(['status' => 'ok']);
        exit;
    }

    /**
     * [C5] Server-Sent Events đếm người online
     * GET /sse-online
     */
    public function sseOnline(): void
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');

        // Tính toán dữ liệu
        $count = get_online_count();
        
        // Xuất dữ liệu theo định dạng chuẩn SSE
        echo "data: " . json_encode(['count' => $count]) . "\n\n";
        ob_flush();
        flush();
        
        // Phía trình duyệt (EventSource) sẽ nhận thấy kết nối đóng và tự động gọi lại sau 3-5 giây.
        exit;
    }
}
