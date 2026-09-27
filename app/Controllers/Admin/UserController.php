<?php
/**
 * app/Controllers/Admin/UserController.php — Quản lý người dùng (Admin)
 * [C3] MVC Controller
 * [C4] requireAdmin() — chỉ admin mới truy cập được
 */

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    protected $db;
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->db = PDO();
        $this->userModel = new User($this->db);
    }

    /**
     * Danh sách người dùng
     * GET /admin/users
     */
    public function index(): void
    {
        AUTHGUARD()->requireAdmin();

        $users = $this->userModel->getAll();

        $this->sendPage('admin/users', [
            'users' => $users,
        ]);
    }
}
