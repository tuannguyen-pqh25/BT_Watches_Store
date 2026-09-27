<?php
/**
 * app/Controllers/ProductController.php — Xử lý hiển thị sản phẩm
 * [C3] MVC Controller
 * [C5] HTTP Caching 304 trong show() — ETag dựa trên updated_at
 */

namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Paginator;

class ProductController extends Controller
{
    /** Số sản phẩm hiển thị mỗi trang */
    private const PRODUCTS_PER_PAGE = 8;

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
     * Danh sách sản phẩm + tìm kiếm + lọc theo danh mục + phân trang
     * GET /products?q=keyword&category=1&page=2
     */
    public function index(): void
    {
        $keyword    = trim($_GET['q'] ?? '');
        $categoryId = !empty($_GET['category']) ? (int)$_GET['category'] : null;
        $page       = max(1, (int)($_GET['page'] ?? 1));

        // Đếm tổng số bản ghi → tạo Paginator
        if ($keyword !== '' || $categoryId !== null) {
            $totalRecords = $this->productModel->countByFilter($keyword, $categoryId);
        } else {
            $totalRecords = $this->productModel->countAll(true);
        }

        $paginator = new Paginator(self::PRODUCTS_PER_PAGE, $totalRecords, $page);

        // Lấy sản phẩm theo trang (LIMIT + OFFSET)
        if ($keyword !== '' || $categoryId !== null) {
            $products = $this->productModel->search(
                $keyword, $categoryId,
                $paginator->recordsPerPage, $paginator->recordOffset
            );
        } else {
            $products = $this->productModel->getAll(
                $paginator->recordsPerPage, $paginator->recordOffset, true
            );
        }

        $categories = $this->categoryModel->getAll();

        // Query params để giữ filter khi chuyển trang
        $queryParams = [];
        if ($keyword !== '') {
            $queryParams['q'] = $keyword;
        }
        if ($categoryId !== null) {
            $queryParams['category'] = $categoryId;
        }

        $this->sendPage('product/product_list', [
            'products'    => $products,
            'categories'  => $categories,
            'keyword'     => $keyword,
            'categoryId'  => $categoryId,
            'paginator'   => $paginator,
            'queryParams' => $queryParams,
        ]);
    }

    /**
     * Chi tiết sản phẩm
     * GET /products/{id}
     * [C5] HTTP Caching: ETag dựa trên product id + updated_at
     */
    public function show(int $id): void
    {
        $product = $this->productModel->getById($id);

        if (!$product) {
            $this->sendNotFound();
            return;
        }

        // [C5] HTTP Caching — ETag riêng cho từng sản phẩm
        // Dùng id + updated_at → nếu sản phẩm không đổi → trả 304 (tiết kiệm băng thông)
        $etag = '"product-' . $product->id . '-' . md5($product->updated_at) . '"';
        header("ETag: " . $etag);
        header("Cache-Control: no-cache, must-revalidate");

        if (isset($_SERVER['HTTP_IF_NONE_MATCH'])
            && trim($_SERVER['HTTP_IF_NONE_MATCH']) === $etag) {
            http_response_code(304);
            exit;
        }

        $this->sendPage('product/detail', [
            'product' => $product,
        ]);
    }
}
