<?php
/**
 * app/Controllers/Admin/ProductController.php — CRUD sản phẩm (Admin)
 * [C2] Thêm/Sửa/Xóa sản phẩm — minh chứng CRUD đầy đủ
 * [C3] MVC Controller
 * [C4] requireAdmin() + CSRF
 */

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
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
     * Danh sách sản phẩm (Admin)
     * GET /admin/products
     */
    public function index(): void
    {
        AUTHGUARD()->requireAdmin();

        $products = $this->productModel->getAll();

        $this->sendPage('admin/products', [
            'products' => $products,
        ]);
    }

    /**
     * Form tạo sản phẩm mới
     * GET /admin/products/create
     */
    public function create(): void
    {
        AUTHGUARD()->requireAdmin();

        $categories = $this->categoryModel->getAll();

        $this->sendPage('admin/product_add', [
            'categories' => $categories,
        ]);
    }

    /**
     * Lưu sản phẩm mới vào DB
     * POST /admin/products/store
     * [C4] CSRF validation
     */
    public function store(): void
    {
        AUTHGUARD()->requireAdmin();

        // [C4] Lọc dữ liệu đầu vào bằng filter_input()
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $name = trim($name ?? '');
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $price = $price !== false ? $price : 0;

        // Validate cơ bản
        if (empty($name) || $price <= 0) {
            $this->saveFormValues($_POST, ['csrf_token']);
            $this->redirect('/admin/products/create', ['error' => 'Vui lòng nhập đầy đủ tên và giá sản phẩm.']);
        }

        // [C5] Xử lý upload ảnh — ưu tiên file upload, fallback sang URL text
        $imagePath = filter_input(INPUT_POST, 'image_url', FILTER_SANITIZE_URL);
        $imagePath = trim($imagePath ?? '');
        // [C4] Validate URL nếu nhập URL ảnh
        if (!empty($imagePath) && !filter_var($imagePath, FILTER_VALIDATE_URL) && !str_starts_with($imagePath, '/')) {
            $imagePath = ''; // Xóa URL không hợp lệ
        }
        if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload = upload_image($_FILES['image']);
            if (!$upload['success']) {
                $this->saveFormValues($_POST, ['csrf_token']);
                $this->redirect('/admin/products/create', ['error' => $upload['error']]);
            }
            $imagePath = $upload['path'];
        }

        // Tạo slug từ tên sản phẩm
        $slug = $this->createSlug($name);

        // [C4] Lọc các trường còn lại bằng filter_input()
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);
        $brand = filter_input(INPUT_POST, 'brand', FILTER_SANITIZE_SPECIAL_CHARS);
        $stockQuantity = filter_input(INPUT_POST, 'stock_quantity', FILTER_VALIDATE_INT);
        $categoryId = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);

        $success = $this->productModel->create([
            'category_id'    => $categoryId !== false ? $categoryId : 0,
            'name'           => $name,
            'slug'           => $slug,
            'description'    => trim($description ?? ''),
            'price'          => $price,
            'stock_quantity' => $stockQuantity !== false ? $stockQuantity : 0,
            'image_path'     => $imagePath,
            'brand'          => trim($brand ?? ''),
            'status'         => in_array($status, ['show', 'hide']) ? $status : 'show',
        ]);

        if ($success) {
            $this->redirect('/admin/products', ['success' => 'Đã thêm sản phẩm "' . $name . '" thành công.']);
        } else {
            $this->saveFormValues($_POST, ['csrf_token']);
            $this->redirect('/admin/products/create', ['error' => 'Có lỗi xảy ra khi thêm sản phẩm.']);
        }
    }

    /**
     * Form chỉnh sửa sản phẩm
     * GET /admin/products/{id}/edit
     */
    public function edit(int $id): void
    {
        AUTHGUARD()->requireAdmin();

        $product = $this->productModel->getById($id);
        if (!$product) {
            $this->redirect('/admin/products', ['error' => 'Sản phẩm không tồn tại.']);
        }

        $categories = $this->categoryModel->getAll();

        $this->sendPage('admin/product_edit', [
            'product'    => $product,
            'categories' => $categories,
        ]);
    }

    /**
     * Cập nhật sản phẩm
     * POST /admin/products/{id}/update
     */
    public function update(int $id): void
    {
        AUTHGUARD()->requireAdmin();

        // [C4] Lọc dữ liệu đầu vào bằng filter_input()
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $name = trim($name ?? '');
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $price = $price !== false ? $price : 0;

        if (empty($name) || $price <= 0) {
            $this->saveFormValues($_POST, ['csrf_token']);
            $this->redirect('/admin/products/' . $id . '/edit', ['error' => 'Vui lòng nhập đầy đủ tên và giá.']);
        }

        // Lấy sản phẩm hiện tại để giữ ảnh cũ nếu không upload mới
        $product = $this->productModel->getById($id);
        if (!$product) {
            $this->redirect('/admin/products', ['error' => 'Sản phẩm không tồn tại.']);
        }

        // [C5] Xử lý upload ảnh — ưu tiên file upload > URL text > giữ ảnh cũ
        $imagePath = $product->image_path; // mặc định giữ ảnh cũ
        if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload = upload_image($_FILES['image']);
            if (!$upload['success']) {
                $this->saveFormValues($_POST, ['csrf_token']);
                $this->redirect('/admin/products/' . $id . '/edit', ['error' => $upload['error']]);
            }
            // Xóa ảnh cũ nếu là file local (bắt đầu bằng /uploads/)
            if (!empty($product->image_path) && str_starts_with($product->image_path, '/uploads/')) {
                $oldFile = ROOTDIR . 'public' . $product->image_path;
                if (is_file($oldFile)) {
                    unlink($oldFile);
                }
            }
            $imagePath = $upload['path'];
        } else {
            // [C4] Lọc URL ảnh bằng filter_input()
            $imageUrl = filter_input(INPUT_POST, 'image_url', FILTER_SANITIZE_URL);
            $imageUrl = trim($imageUrl ?? '');
            if (!empty($imageUrl)) {
                // [C4] Validate URL hợp lệ hoặc đường dẫn local
                if (filter_var($imageUrl, FILTER_VALIDATE_URL) || str_starts_with($imageUrl, '/')) {
                    $imagePath = $imageUrl;
                }
            }
        }

        $slug = $this->createSlug($name);

        // [C4] Lọc các trường còn lại bằng filter_input()
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);
        $brand = filter_input(INPUT_POST, 'brand', FILTER_SANITIZE_SPECIAL_CHARS);
        $stockQuantity = filter_input(INPUT_POST, 'stock_quantity', FILTER_VALIDATE_INT);
        $categoryId = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);

        $success = $this->productModel->update($id, [
            'category_id'    => $categoryId !== false ? $categoryId : 0,
            'name'           => $name,
            'slug'           => $slug,
            'description'    => trim($description ?? ''),
            'price'          => $price,
            'stock_quantity' => $stockQuantity !== false ? $stockQuantity : 0,
            'image_path'     => $imagePath,
            'brand'          => trim($brand ?? ''),
            'status'         => in_array($status, ['show', 'hide']) ? $status : 'show',
        ]);

        if ($success) {
            $this->redirect('/admin/products', ['success' => 'Đã cập nhật sản phẩm thành công.']);
        } else {
            $this->redirect('/admin/products/' . $id . '/edit', ['error' => 'Có lỗi xảy ra.']);
        }
    }

    /**
     * Xóa sản phẩm
     * POST /admin/products/{id}/delete
     */
    public function delete(int $id): void
    {
        AUTHGUARD()->requireAdmin();


        $product = $this->productModel->getById($id);

        if (!$product) {
            $this->redirect('/admin/products', ['error' => 'Sản phẩm không tồn tại.']);
        }

        $this->productModel->delete($id);
        $this->redirect('/admin/products', ['success' => 'Đã xóa sản phẩm "' . $product->name . '".']);
    }

    /**
     * Tạo slug URL-friendly từ tên sản phẩm
     * Ví dụ: "Rolex Submariner Gold" → "rolex-submariner-gold"
     */
    private function createSlug(string $name): string
    {
        // Chuyển về lowercase, bỏ dấu tiếng Việt cơ bản
        $slug = mb_strtolower($name, 'UTF-8');
        // Thay khoảng trắng + ký tự đặc biệt bằng dấu gạch ngang
        $slug = preg_replace('/[^a-z0-9\-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug); // gộp nhiều dấu - liên tiếp
        return trim($slug, '-');
    }
}
