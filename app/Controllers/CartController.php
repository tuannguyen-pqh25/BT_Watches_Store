<?php
/**
 * app/Controllers/CartController.php — Xử lý giỏ hàng (Session-based)
 * [C3] MVC Controller
 * [C4] CSRF: validate token trước khi xử lý POST
 *
 * Giỏ hàng lưu trong $_SESSION['cart'] dạng:
 * [
 *     product_id => ['id' => ..., 'name' => ..., 'price' => ..., 'image' => ..., 'qty' => ...]
 * ]
 */

namespace App\Controllers;

use App\Models\Product;

class CartController extends Controller
{
    protected $db;
    private Product $productModel;

    public function __construct()
    {
        parent::__construct();
        $this->db = PDO();
        $this->productModel = new Product($this->db);
    }
    /**
     * Hiển thị giỏ hàng
     * GET /cart
     */
    public function index(): void
    {
        $cart = $_SESSION['cart'] ?? [];

        // Chuyển đổi cart session sang format view cần: [{product: {...}, quantity: N}]
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

        $this->sendPage('cart/cart', [
            'cartItems' => $cartItems,
            'total'     => $total,
        ]);
    }

    /**
     * Thêm sản phẩm vào giỏ
     * POST /cart/add
     */
    public function add(): void
    {

        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity  = max(1, (int)($_POST['quantity'] ?? 1));

        if ($productId <= 0) {
            $this->redirect('/products', ['error' => 'Sản phẩm không hợp lệ.']);
        }

        // Lấy thông tin sản phẩm từ DB để đảm bảo dữ liệu chính xác
        $product = $this->productModel->getById($productId);

        if (!$product) {
            $this->redirect('/products', ['error' => 'Sản phẩm không tồn tại.']);
        }

        // Kiểm tra tồn kho
        if ($product->stock_quantity < $quantity) {
            $this->redirect('/products/' . $productId, [
                'error' => 'Sản phẩm không đủ số lượng trong kho.'
            ]);
        }

        // Khởi tạo giỏ hàng nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Nếu sản phẩm đã có trong giỏ → tăng số lượng
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['qty'] += $quantity;
        } else {
            // Thêm sản phẩm mới vào giỏ
            $_SESSION['cart'][$productId] = [
                'id'    => $product->id,
                'name'  => $product->name,
                'price' => $product->price,
                'image' => $product->image_path,
                'qty'   => $quantity,
            ];
        }

        $this->redirect('/cart', ['success' => 'Đã thêm "' . $product->name . '" vào giỏ hàng.']);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ
     * POST /cart/update
     */
    public function update(): void
    {

        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity  = (int)($_POST['quantity'] ?? 0);

        if (isset($_SESSION['cart'][$productId])) {
            if ($quantity <= 0) {
                // Số lượng = 0 → xóa khỏi giỏ
                unset($_SESSION['cart'][$productId]);
            } else {
                $_SESSION['cart'][$productId]['qty'] = $quantity;
            }
        }

        $this->redirect('/cart', ['success' => 'Đã cập nhật giỏ hàng.']);
    }

    /**
     * Xóa sản phẩm khỏi giỏ
     * POST /cart/remove
     */
    public function remove(): void
    {

        $productId = (int)($_POST['product_id'] ?? 0);

        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }

        $this->redirect('/cart', ['success' => 'Đã xóa sản phẩm khỏi giỏ hàng.']);
    }
}
