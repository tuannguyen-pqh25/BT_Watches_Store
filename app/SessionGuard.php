<?php
/**
 * app/SessionGuard.php — Quản lý xác thực người dùng qua Session
 * [C4] Login/logout, session fixation protection, role-based access
 */

namespace App;

use App\Models\User;

class SessionGuard
{
    /** Cache user object — tránh query DB nhiều lần trong cùng 1 request */
    protected ?object $user = null;

    /**
     * [C4] Đăng nhập: xác thực mật khẩu bcrypt → regenerate session → lưu user_id
     * @param object $user         User object từ DB
     * @param array  $credentials  ['password' => '...']
     */
    public function login(object $user, array $credentials): bool
    {
        $verified = password_verify($credentials['password'], $user->password);

        if ($verified) {
            session_regenerate_id(true); // Đổi id sau khi đăng nhập để hacker không thể dùng ID cũ đã được cài trước đó
            $_SESSION['user_id'] = $user->id;
        }

        return $verified;
    }

    /**
     * Lấy user hiện tại (lazy load từ DB)
     * Nếu user đã bị xóa khỏi DB → tự động logout
     */
    public function user(): ?object
    {
        if (!$this->user && $this->isUserLoggedIn()) {
            $this->user = (new User(PDO()))->findById($_SESSION['user_id']);
            if ($this->user === null) {
                $this->logout();
                return null;
            }
        }
        return $this->user;
    }

    /** Đăng xuất: xóa session + regenerate ID */
    public function logout(): void
    {
        $this->user = null;
        unset($_SESSION['user_id']); // Chỉ xóa thông tin đăng nhập, giữ lại cart
        session_regenerate_id(true); // đổi ID sau khi đăng xuất để người đánh cắp ID cũ không thể sử dụng lại
    }

    /** Kiểm tra đã đăng nhập chưa */
    public function isUserLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /** [C4] Yêu cầu đăng nhập, chưa đăng nhập → redirect về /login */
    public function requireLogin(): void
    {
        if (!$this->isUserLoggedIn()) {
            redirect('/login', ['error' => 'Vui lòng đăng nhập để tiếp tục.']);
        }
    }

    /** [C4] Yêu cầu role admin, không phải admin → 403 Forbidden */
    public function requireAdmin(): void
    {
        $this->requireLogin();

        $user = $this->user();
        if (!$user || $user->role !== 'admin') {
            http_response_code(403);
            exit('<h1>403 — Không có quyền truy cập</h1>');
        }
    }
}
