<?php
/**
 * app/Controllers/Admin/OrderController.php — Quản lý đơn hàng (Admin)
 * [C3] MVC | [C4] requireAdmin() | [C5] SSE đếm đơn pending
 */

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    protected $db;
    private Order $orderModel;

    public function __construct()
    {
        parent::__construct();
        $this->db = PDO();
        $this->orderModel = new Order($this->db);
    }
    /** GET /admin/orders — Danh sách tất cả đơn hàng */
    public function index(): void
    {
        AUTHGUARD()->requireAdmin();

        $stmt = $this->db->query("SELECT o.*, u.full_name as customer_name, u.email as customer_email 
                              FROM orders o 
                              JOIN users u ON o.user_id = u.id 
                              ORDER BY o.created_at DESC");
        $orders = $stmt->fetchAll();

        $this->sendPage('admin/orders', [
            'orders' => $orders,
        ]);
    }

    /** GET /admin/orders/{id} — Chi tiết đơn hàng */
    public function show(int $id): void
    {
        AUTHGUARD()->requireAdmin();

        $order = $this->orderModel->getById($id);

        if (!$order) {
            $this->redirect('/admin/orders', ['error' => 'Đơn hàng không tồn tại.']);
        }

        $items = $this->orderModel->getItems($order->id);

        $this->sendPage('admin/order_detail', [
            'order' => $order,
            'items' => $items,
        ]);
    }

    /** POST /admin/orders/{id}/status — Cập nhật trạng thái đơn hàng */
    public function updateStatus(int $id): void
    {
        AUTHGUARD()->requireAdmin();

        // [C4] Lọc dữ liệu đầu vào bằng filter_input()
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
        $validStatuses = ['pending', 'confirmed', 'shipping', 'delivered', 'cancelled'];

        if (!in_array($status, $validStatuses, true)) {
            $this->redirect('/admin/orders/' . $id, ['error' => 'Trạng thái không hợp lệ.']);
        }

        $order = $this->orderModel->getById($id);

        if (!$order) {
            $this->redirect('/admin/orders', ['error' => 'Đơn hàng không tồn tại.']);
        }

        $this->orderModel->updateStatus($id, $status);

        $this->redirect('/admin/orders/' . $id, [
            'success' => 'Đã cập nhật trạng thái đơn hàng thành "' . $status . '".'
        ]);
    }

    /** [C5] GET /admin/sse-orders — SSE đẩy số đơn pending về Admin real-time */
    public function ssePending(): void
    {
        AUTHGUARD()->requireAdmin();
        
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');

        $stmt = $this->db->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'");
        $count = $stmt->fetchColumn();
        
        echo "data: " . json_encode(['pending_orders' => $count]) . "\n\n";
        ob_flush();
        flush();
        // Kết thúc để giải phóng worker — EventSource client tự gọi lại sau vài giây
        exit;
    }
}
