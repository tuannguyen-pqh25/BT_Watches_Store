<?php
/**
 * app/Controllers/OrderController.php — Xem lịch sử đơn hàng (khách hàng)
 * [C3] MVC Controller
 * [C4] requireLogin() + chống IDOR qua Order::getById($id, $userId)
 */

namespace App\Controllers;

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
    /**
     * Danh sách đơn hàng của user đang đăng nhập
     * GET /orders
     */
    public function history(): void
    {
        // [C4] Yêu cầu đăng nhập
        AUTHGUARD()->requireLogin();

        $user = AUTHGUARD()->user();
        $orders = $this->orderModel->getByUser($user->id);

        $this->sendPage('orders/history', [
            'orders' => $orders,
        ]);
    }

    /**
     * Chi tiết đơn hàng
     * GET /orders/{id}
     * [C4] Chống IDOR: chỉ xem đơn thuộc user đang đăng nhập
     */
    public function show(int $id): void
    {
        // [C4] Yêu cầu đăng nhập
        AUTHGUARD()->requireLogin();

        $user = AUTHGUARD()->user();

        // [C4] Chống IDOR — truyền $userId vào SQL: AND user_id = :user_id
        // Nếu đơn hàng không thuộc user này → trả null
        $order = $this->orderModel->getById($id, $user->id);

        if (!$order) {
            $this->redirect('/orders', ['error' => 'Đơn hàng không tồn tại hoặc bạn không có quyền xem.']);
        }

        // Lấy danh sách sản phẩm trong đơn
        $items = $this->orderModel->getItems($order->id);

        $this->sendPage('orders/detail', [
            'order' => $order,
            'items' => $items,
        ]);
    }
}
