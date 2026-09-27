<?php
/**
 * app/Controllers/ProfileController.php — Xem & chỉnh sửa hồ sơ cá nhân
 * [C3] MVC Controller
 * [C4] requireLogin() + CSRF + Prepared Statement
 *
 * Dùng chung cho cả Customer (layout default) và Admin (layout admin)
 */

namespace App\Controllers;

use App\Models\User;

class ProfileController extends Controller
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
     * Hiển thị trang hồ sơ (Customer)
     * GET /profile
     */
    public function show(): void
    {
        AUTHGUARD()->requireLogin();
        $user = AUTHGUARD()->user();

        $this->sendPage('user/profile', [
            'user' => $user,
        ]);
    }

    /**
     * Cập nhật thông tin hồ sơ (Customer)
     * POST /profile/update
     */
    public function update(): void
    {
        AUTHGUARD()->requireLogin();
        $user = AUTHGUARD()->user();

        // Lọc dữ liệu đầu vào
        $fullName = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $fullName = trim($fullName ?? '');
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);
        $phone = trim($phone ?? '');

        // Validate
        if (empty($fullName)) {
            $this->redirect('/profile', ['error' => 'Họ tên không được để trống.']);
        }

        // Cập nhật thông tin
        $this->userModel->updateProfile($user->id, [
            'full_name' => $fullName,
            'phone'     => $phone ?: null,
        ]);

        $this->redirect('/profile', ['success' => 'Cập nhật hồ sơ thành công!']);
    }

    /**
     * Đổi mật khẩu (Customer)
     * POST /profile/password
     */
    public function changePassword(): void
    {
        AUTHGUARD()->requireLogin();
        $user = AUTHGUARD()->user();

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validate
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $this->redirect('/profile', ['error' => 'Vui lòng nhập đầy đủ thông tin mật khẩu.']);
        }

        // Kiểm tra mật khẩu hiện tại
        if (!password_verify($currentPassword, $user->password)) {
            $this->redirect('/profile', ['error' => 'Mật khẩu hiện tại không đúng.']);
        }

        if ($newPassword !== $confirmPassword) {
            $this->redirect('/profile', ['error' => 'Mật khẩu mới và xác nhận không khớp.']);
        }

        if (strlen($newPassword) < 8) {
            $this->redirect('/profile', ['error' => 'Mật khẩu mới phải có ít nhất 8 ký tự.']);
        }

        // Cập nhật mật khẩu
        $this->userModel->updatePassword($user->id, $newPassword);

        $this->redirect('/profile', ['success' => 'Đổi mật khẩu thành công!']);
    }

    /**
     * Upload avatar (Customer)
     * POST /profile/avatar
     */
    public function updateAvatar(): void
    {
        AUTHGUARD()->requireLogin();
        $user = AUTHGUARD()->user();

        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $this->redirect('/profile', ['error' => 'Vui lòng chọn ảnh để tải lên.']);
        }

        $file = $_FILES['avatar'];

        // Giới hạn 2MB
        if ($file['size'] > 2 * 1024 * 1024) {
            $this->redirect('/profile', ['error' => 'Ảnh vượt quá 2MB.']);
        }

        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExts)) {
            $this->redirect('/profile', ['error' => 'Chỉ chấp nhận ảnh JPG, PNG, GIF hoặc WebP.']);
        }

        $filename  = 'avatar_' . $user->id . '_' . uniqid() . '.' . $ext;
        $uploadDir = ROOTDIR . 'public/uploads/avatars';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadDir . '/' . $filename)) {
            $avatarPath = '/uploads/avatars/' . $filename;
            $this->userModel->updateAvatar($user->id, $avatarPath);
            $this->redirect('/profile', ['success' => 'Cập nhật ảnh đại diện thành công!']);
        }

        $this->redirect('/profile', ['error' => 'Lỗi khi tải ảnh lên.']);
    }

    // ═══════════════════════════════════════════════════════════════════════════
    // ADMIN — Hồ sơ Admin (dùng layout admin)
    // ═══════════════════════════════════════════════════════════════════════════

    /**
     * Hiển thị trang hồ sơ Admin
     * GET /admin/profile
     */
    public function adminShow(): void
    {
        AUTHGUARD()->requireAdmin();
        $user = AUTHGUARD()->user();

        $this->sendPage('admin/profile', [
            'user' => $user,
        ]);
    }

    /**
     * Cập nhật thông tin hồ sơ Admin
     * POST /admin/profile/update
     */
    public function adminUpdate(): void
    {
        AUTHGUARD()->requireAdmin();
        $user = AUTHGUARD()->user();

        $fullName = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $fullName = trim($fullName ?? '');
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);
        $phone = trim($phone ?? '');

        if (empty($fullName)) {
            $this->redirect('/admin/profile', ['error' => 'Họ tên không được để trống.']);
        }

        $this->userModel->updateProfile($user->id, [
            'full_name' => $fullName,
            'phone'     => $phone ?: null,
        ]);

        $this->redirect('/admin/profile', ['success' => 'Cập nhật hồ sơ thành công!']);
    }

    /**
     * Đổi mật khẩu Admin
     * POST /admin/profile/password
     */
    public function adminChangePassword(): void
    {
        AUTHGUARD()->requireAdmin();
        $user = AUTHGUARD()->user();

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $this->redirect('/admin/profile', ['error' => 'Vui lòng nhập đầy đủ thông tin mật khẩu.']);
        }

        if (!password_verify($currentPassword, $user->password)) {
            $this->redirect('/admin/profile', ['error' => 'Mật khẩu hiện tại không đúng.']);
        }

        if ($newPassword !== $confirmPassword) {
            $this->redirect('/admin/profile', ['error' => 'Mật khẩu mới và xác nhận không khớp.']);
        }

        if (strlen($newPassword) < 8) {
            $this->redirect('/admin/profile', ['error' => 'Mật khẩu mới phải có ít nhất 8 ký tự.']);
        }

        $this->userModel->updatePassword($user->id, $newPassword);

        $this->redirect('/admin/profile', ['success' => 'Đổi mật khẩu thành công!']);
    }

    /**
     * Upload avatar Admin
     * POST /admin/profile/avatar
     */
    public function adminUpdateAvatar(): void
    {
        AUTHGUARD()->requireAdmin();
        $user = AUTHGUARD()->user();

        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $this->redirect('/admin/profile', ['error' => 'Vui lòng chọn ảnh để tải lên.']);
        }

        $file = $_FILES['avatar'];

        if ($file['size'] > 2 * 1024 * 1024) {
            $this->redirect('/admin/profile', ['error' => 'Ảnh vượt quá 2MB.']);
        }

        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExts)) {
            $this->redirect('/admin/profile', ['error' => 'Chỉ chấp nhận ảnh JPG, PNG, GIF hoặc WebP.']);
        }

        $filename  = 'avatar_' . $user->id . '_' . uniqid() . '.' . $ext;
        $uploadDir = ROOTDIR . 'public/uploads/avatars';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadDir . '/' . $filename)) {
            $avatarPath = '/uploads/avatars/' . $filename;
            $this->userModel->updateAvatar($user->id, $avatarPath);
            $this->redirect('/admin/profile', ['success' => 'Cập nhật ảnh đại diện thành công!']);
        }

        $this->redirect('/admin/profile', ['error' => 'Lỗi khi tải ảnh lên.']);
    }
}
