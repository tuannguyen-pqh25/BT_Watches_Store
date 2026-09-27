<?php
/**
 * app/Controllers/CheckoutController.php — Xử lý thanh toán đơn hàng
 * [C3] MVC Controller
 * [C2] Transaction: Order::create() tạo đơn + chi tiết + trừ tồn kho trong 1 transaction
 * [C4] CSRF + requireLogin()
 */

namespace App\Controllers;

use App\Models\Order;
use Exception;

class CheckoutController extends Controller
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
     * Hiển thị form thanh toán
     * GET /checkout
     */
    public function index(): void
    {
        // [C4] Yêu cầu đăng nhập
        AUTHGUARD()->requireLogin();

        $cart = $_SESSION['cart'] ?? [];

        // Giỏ hàng trống → về trang giỏ hàng
        if (empty($cart)) {
            $this->redirect('/cart', ['error' => 'Giỏ hàng trống. Vui lòng thêm sản phẩm trước khi thanh toán.']);
        }

        // Chuyển đổi cart session sang format view cần
        $cartItems = [];
        $total = 0;
        foreach ($cart as $item) {
            $cartItems[] = [
                'product' => [
                    'id'         => $item['id'],
                    'name'       => $item['name'],
                    'price'      => $item['price'],
                    'image_path' => $item['image'] ?? null,
                ],
                'quantity' => $item['qty'],
            ];
            $total += $item['price'] * $item['qty'];
        }

        $this->sendPage('checkout/index', [
            'cartItems' => $cartItems,
            'total'     => $total,
        ]);
    }

    /**
     * Xử lý đặt hàng
     * POST /checkout
     * [C2] Dùng Transaction — nếu 1 bước lỗi → rollback toàn bộ
     */
    public function process(): void
    {
        // [C4] Yêu cầu đăng nhập
        AUTHGUARD()->requireLogin();


        $cart = $_SESSION['cart'] ?? [];

        if (empty($cart)) {
            $this->redirect('/cart', ['error' => 'Giỏ hàng trống.']);
        }

        $user = AUTHGUARD()->user();

        // [C4] Lọc dữ liệu đầu vào bằng filter_input()
        $shippingName    = filter_input(INPUT_POST, 'shipping_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $shippingName    = trim($shippingName ?? '');
        $shippingAddress = filter_input(INPUT_POST, 'shipping_address', FILTER_SANITIZE_SPECIAL_CHARS);
        $shippingAddress = trim($shippingAddress ?? '');
        $shippingPhone   = filter_input(INPUT_POST, 'shipping_phone', FILTER_SANITIZE_SPECIAL_CHARS);
        $shippingPhone   = trim($shippingPhone ?? '');
        $shippingEmail   = filter_input(INPUT_POST, 'shipping_email', FILTER_SANITIZE_SPECIAL_CHARS);
        $shippingEmail   = trim($shippingEmail ?? '');
        $paymentMethod   = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_SPECIAL_CHARS);
        $paymentMethod   = $paymentMethod ?? 'COD';
        $note            = filter_input(INPUT_POST, 'note', FILTER_SANITIZE_SPECIAL_CHARS);
        $note            = trim($note ?? '');

        if (empty($shippingName) || empty($shippingAddress) || empty($shippingPhone)) {
            $this->saveFormValues($_POST, ['csrf_token']);
            $this->redirect('/checkout', ['error' => 'Vui lòng nhập đầy đủ họ tên, địa chỉ và số điện thoại.']);
        }

        // [C4] Validate định dạng email bằng filter_var()
        if (!empty($shippingEmail) && !filter_var($shippingEmail, FILTER_VALIDATE_EMAIL)) {
            $this->saveFormValues($_POST, ['csrf_token']);
            $this->redirect('/checkout', ['error' => 'Địa chỉ email không hợp lệ.']);
        }

        // Tính tổng tiền
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['qty'];
        }

        // Tạo mã đơn hàng duy nhất: ORD-XXXXX
        $orderCode = 'ORD-' . strtoupper(substr(uniqid(), -5));

        // Chuẩn bị dữ liệu đơn hàng
        $orderData = [
            'user_id'          => $user->id,
            'order_code'       => $orderCode,
            'status'           => 'pending',
            'total_amount'     => $totalAmount,
            'shipping_name'    => $shippingName,
            'shipping_address' => $shippingAddress,
            'shipping_phone'   => $shippingPhone,
            'shipping_email'   => $shippingEmail,
            'payment_method'   => $paymentMethod,
            'note'             => $note,
        ];

        // Chuyển cart thành format Order::create() yêu cầu
        $cartItems = [];
        foreach ($cart as $item) {
            $cartItems[] = [
                'id'    => $item['id'],
                'name'  => $item['name'],
                'price' => $item['price'],
                'qty'   => $item['qty'],
            ];
        }

        try {
            // [C2] Transaction: tạo đơn + order_items + trừ tồn kho
            $orderId = $this->orderModel->create($orderData, $cartItems);

            // [C5] Gửi email xác nhận đơn hàng
            if (!empty($shippingEmail)) {
                $emailBody = "<h3>Cảm ơn bạn đã đặt hàng tại BT WATCH!</h3>";
                $emailBody .= "<p>Mã đơn hàng của bạn là: <strong>{$orderCode}</strong></p>";
                $emailBody .= "<p>Tổng tiền: <strong>" . number_format($totalAmount, 0, ',', '.') . "₫</strong></p>";
                $emailBody .= "<p>Chúng tôi sẽ sớm liên hệ để giao hàng.</p>";
                send_order_email($shippingEmail, "Xác nhận đơn hàng {$orderCode} - BT WATCH", $emailBody);
            }

            // Xóa giỏ hàng sau khi đặt hàng thành công
            unset($_SESSION['cart']);

            $this->redirect('/orders/' . $orderId, [
                'success' => 'Đặt hàng thành công! Mã đơn hàng: ' . $orderCode
            ]);

        } catch (Exception $e) {
            $this->redirect('/checkout', [
                'error' => 'Đặt hàng thất bại: ' . $e->getMessage()
            ]);
        }
    }
}
