<?php
/**
 * app/Controllers/Admin/DashboardController.php — Trang tổng quan quản trị
 * [C3] MVC Controller
 * [C4] requireAdmin() — chỉ admin mới truy cập được
 */

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;

class DashboardController extends Controller
{
    protected $db;
    private Product $productModel;
    private Order $orderModel;
    private Category $categoryModel;
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->db = PDO();
        $this->productModel = new Product($this->db);
        $this->orderModel = new Order($this->db);
        $this->categoryModel = new Category($this->db);
        $this->userModel = new User($this->db);
    }
    /**
     * Trang dashboard admin
     * GET /admin
     */
    public function index(): void
    {
        // [C4] Phân quyền: chỉ admin mới xem được
        AUTHGUARD()->requireAdmin();

        // Thống kê cơ bản cho dashboard
        $products     = $this->productModel->getAll();
        $categories   = $this->categoryModel->getAll();
        $totalOrders  = $this->orderModel->countAll();
        $totalRevenue = $this->orderModel->getTotalRevenue();
        $totalUsers   = $this->userModel->countAll();

        $this->sendPage('admin/dashboard', [
            'totalProducts'   => count($products),
            'totalCategories' => count($categories),
            'totalOrders'     => $totalOrders,
            'totalRevenue'    => $totalRevenue,
            'totalUsers'      => $totalUsers,
            'products'        => $products,
            'categories'      => $categories,
        ]);
    }
}
