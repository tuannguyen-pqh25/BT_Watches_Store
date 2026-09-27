<?php
/**
 * app/Controllers/Admin/CategoryController.php — CRUD danh mục (Admin)
 * [C2] Thêm/Sửa/Xóa danh mục — minh chứng CRUD đầy đủ
 * [C3] MVC Controller
 * [C4] requireAdmin() + CSRF
 */

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    protected $db;
    private Category $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->db = PDO();
        $this->categoryModel = new Category($this->db);
    }
    /**
     * Danh sách danh mục
     * GET /admin/categories
     */
    public function index(): void
    {
        AUTHGUARD()->requireAdmin();

        $categories = $this->categoryModel->getAll();

        $this->sendPage('admin/categories', [
            'categories' => $categories,
        ]);
    }

    /**
     * Form tạo danh mục mới (tích hợp trên trang categories)
     * GET /admin/categories/create
     */
    public function create(): void
    {
        AUTHGUARD()->requireAdmin();

        $categories = $this->categoryModel->getAll();

        $this->sendPage('admin/categories', [
            'categories' => $categories,
            'isCreate'   => true
        ]);
    }

    /**
     * Lưu danh mục mới
     * POST /admin/categories/store
     */
    public function store(): void
    {
        AUTHGUARD()->requireAdmin();

        // [C4] Lọc dữ liệu đầu vào bằng filter_input()
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $name = trim($name ?? '');

        if (empty($name)) {
            $this->saveFormValues($_POST, ['csrf_token']);
            $this->redirect('/admin/categories/create', ['error' => 'Vui lòng nhập tên danh mục.']);
        }

        // Tạo slug
        $slug = mb_strtolower($name, 'UTF-8');
        $slug = preg_replace('/[^a-z0-9\-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        // [C4] Lọc các trường còn lại bằng filter_input()
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);
        $icon = filter_input(INPUT_POST, 'icon', FILTER_SANITIZE_SPECIAL_CHARS);

        $success = $this->categoryModel->create([
            'name'        => $name,
            'slug'        => $slug,
            'description' => trim($description ?? ''),
            'icon'        => trim($icon ?? ''),
        ]);

        if ($success) {
            $this->redirect('/admin/categories', ['success' => 'Đã thêm danh mục "' . $name . '" thành công.']);
        } else {
            $this->saveFormValues($_POST, ['csrf_token']);
            $this->redirect('/admin/categories/create', ['error' => 'Có lỗi xảy ra khi thêm danh mục.']);
        }
    }

    /**
     * Form chỉnh sửa danh mục (tích hợp trên trang categories)
     * GET /admin/categories/{id}/edit
     */
    public function edit(int $id): void
    {
        AUTHGUARD()->requireAdmin();

        $category = $this->categoryModel->getById($id);

        if (!$category) {
            $this->redirect('/admin/categories', ['error' => 'Danh mục không tồn tại.']);
        }

        $categories = $this->categoryModel->getAll();

        $this->sendPage('admin/categories', [
            'categories' => $categories,
            'category'   => $category,
        ]);
    }

    /**
     * Cập nhật danh mục
     * POST /admin/categories/{id}/update
     */
    public function update(int $id): void
    {
        AUTHGUARD()->requireAdmin();

        // [C4] Lọc dữ liệu đầu vào bằng filter_input()
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $name = trim($name ?? '');

        if (empty($name)) {
            $this->saveFormValues($_POST, ['csrf_token']);
            $this->redirect('/admin/categories/' . $id . '/edit', ['error' => 'Vui lòng nhập tên danh mục.']);
        }

        $slug = mb_strtolower($name, 'UTF-8');
        $slug = preg_replace('/[^a-z0-9\-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        // [C4] Lọc các trường còn lại bằng filter_input()
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);
        $icon = filter_input(INPUT_POST, 'icon', FILTER_SANITIZE_SPECIAL_CHARS);

        $success = $this->categoryModel->update($id, [
            'name'        => $name,
            'slug'        => $slug,
            'description' => trim($description ?? ''),
            'icon'        => trim($icon ?? ''),
        ]);

        if ($success) {
            $this->redirect('/admin/categories', ['success' => 'Đã cập nhật danh mục thành công.']);
        } else {
            $this->redirect('/admin/categories/' . $id . '/edit', ['error' => 'Có lỗi xảy ra.']);
        }
    }

    /**
     * Xóa danh mục (xóa thật — DELETE FROM)
     * POST /admin/categories/{id}/delete
     * [C2] Minh chứng xóa dữ liệu trong DB
     */
    public function delete(int $id): void
    {
        AUTHGUARD()->requireAdmin();



        $category = $this->categoryModel->getById($id);

        if (!$category) {
            $this->redirect('/admin/categories', ['error' => 'Danh mục không tồn tại.']);
        }

        $this->categoryModel->delete($id);
        $this->redirect('/admin/categories', ['success' => 'Đã xóa danh mục "' . $category->name . '".']);
    }
}
