<?php
/**
 * app/Controllers/HomeController.php — Xử lý các trang tĩnh
 * [C3] MVC: Controller điều phối giữa Model và View
 */

namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    protected $db;
    private Product $productModel;
    private Category $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->db = PDO();
        $this->productModel = new Product($this->db);
        $this->categoryModel = new Category($this->db);
    }
    /**
     * Trang chủ — hiển thị sản phẩm nổi bật
     */
    public function index(): void
    {

        $products   = $this->productModel->getAll(6, 0, true); // 6 sản phẩm mới nhất
        $categories = $this->categoryModel->getAll();

        $this->sendPage('pages/home', [
            'products'   => $products,
            'categories' => $categories,
        ]);
    }

    /**
     * Trang giới thiệu
     */
    public function about(): void
    {
        $this->sendPage('pages/about');
    }

    public function contact(): void
    {
        $this->sendPage('pages/contact');
    }

}
