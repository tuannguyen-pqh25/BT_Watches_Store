<?php
/**
 * app/Controllers/AuthController.php — Xử lý đăng nhập, đăng ký, đăng xuất
 * [C3] MVC Controller
 * [C4] Chứng thực: dùng SessionGuard qua AUTHGUARD()
 * [C4] CSRF: validate_csrf_token() trước khi xử lý POST
 * [C4] Prepared Statement: User Model dùng PDO chống SQL Injection
 */

namespace App\Controllers;

use App\Models\User;

class AuthController extends Controller
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
     * Hiển thị form đăng nhập
     * GET /login
     */
    public function showLogin(): void
    {
        // Nếu đã đăng nhập thì chuyển về trang chủ hoặc admin
        if (AUTHGUARD()->isUserLoggedIn()) {
            $user = AUTHGUARD()->user();
            if ($user && $user->role === 'admin') {
                $this->redirect('/admin');
            } else {
                $this->redirect('/');
            }
        }

        $this->sendPage('auth/login');
    }

    /**
     * Xử lý đăng nhập
     * POST /login
     */
    public function login(): void
    {

        // [C4] Lọc dữ liệu đầu vào bằng filter_input()
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = trim($email ?? '');
        $password = $_POST['password'] ?? '';

        // Validate không cho rỗng
        if (empty($email) || empty($password)) {
            $this->saveFormValues($_POST, ['password', 'csrf_token']);
            $this->redirect('/login', ['error' => 'Vui lòng nhập đầy đủ email và mật khẩu.']);
        }

        // [C4] Validate định dạng email bằng filter_var()
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->saveFormValues($_POST, ['password', 'csrf_token']);
            $this->redirect('/login', ['error' => 'Email hoặc mật khẩu không chính xác.']);
        }

        // [C4] Tìm user bằng Prepared Statement (chống SQL Injection)
        $user = $this->userModel->findByEmail($email);

        // [C4] Thông báo lỗi chung — không tiết lộ email có tồn tại hay không
        if (!$user) {
            $this->saveFormValues($_POST, ['password', 'csrf_token']);
            $this->redirect('/login', ['error' => 'Email hoặc mật khẩu không chính xác.']);
        }

        // [C4] SessionGuard xác thực: password_verify() + session_regenerate_id()
        if (AUTHGUARD()->login($user, ['password' => $password])) {
            // Đăng nhập thành công → chuyển hướng theo role
            if ($user->role === 'admin') {
                $this->redirect('/admin');
            } else {
                $this->redirect('/');
            }
        } else {
            // [C4] Thông báo lỗi chung — không tiết lộ mật khẩu sai
            $this->saveFormValues($_POST, ['password', 'csrf_token']);
            $this->redirect('/login', ['error' => 'Email hoặc mật khẩu không chính xác.']);
        }
    }

    /**
     * Hiển thị form đăng ký
     * GET /register
     */
    public function showRegister(): void
    {
        // Nếu đã đăng nhập thì chuyển về trang chủ
        if (AUTHGUARD()->isUserLoggedIn()) {
            $this->redirect('/');
        }

        $this->sendPage('auth/register');
    }

    /**
     * Xử lý đăng ký tài khoản mới
     * POST /register
     */
    public function register(): void
    {

        // [C4] Lọc dữ liệu đầu vào bằng filter_input()
        $fullName = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_SPECIAL_CHARS);
        $fullName = trim($fullName ?? '');
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = trim($email ?? '');
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS);
        $phone = trim($phone ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirmation'] ?? '';

        // Validate không cho rỗng
        if (empty($fullName) || empty($email) || empty($phone) || empty($password) || empty($passwordConfirm)) {
            $this->saveFormValues($_POST, ['password', 'password_confirmation', 'csrf_token']);
            $this->redirect('/register', ['error' => 'Vui lòng nhập đầy đủ thông tin.']);
        }

        // [C4] Validate định dạng email bằng filter_var()
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->saveFormValues($_POST, ['password', 'password_confirmation', 'csrf_token']);
            $this->redirect('/register', ['error' => 'Địa chỉ email không hợp lệ.']);
        }

        if ($password !== $passwordConfirm) {
            $this->saveFormValues($_POST, ['password', 'password_confirmation', 'csrf_token']);
            $this->redirect('/register', ['error' => 'Mật khẩu xác nhận không khớp.']);
        }

        if (strlen($password) < 8) {
            $this->saveFormValues($_POST, ['password', 'password_confirmation', 'csrf_token']);
            $this->redirect('/register', ['error' => 'Mật khẩu phải có ít nhất 8 ký tự.']);
        }

        // [C4] Kiểm tra email đã tồn tại (Prepared Statement)
        if ($this->userModel->findByEmail($email)) {
            $this->saveFormValues($_POST, ['password', 'password_confirmation', 'csrf_token']);
            $this->redirect('/register', ['error' => 'Email này đã được sử dụng.']);
        }

        // Tạo user mới (password_hash() trong Model)
        $success = $this->userModel->create([
            'email'     => $email,
            'password'  => $password,
            'full_name' => $fullName,
            'phone'     => $phone ?: null,
            'role'      => 'customer'
        ]);

        if ($success) {
            // Lấy lại user vừa tạo để tự động login
            $newUser = $this->userModel->findByEmail($email);
            AUTHGUARD()->login($newUser, ['password' => $password]);

            $this->redirect('/', ['success' => 'Đăng ký thành công! Chào mừng bạn.']);
        } else {
            $this->redirect('/register', ['error' => 'Có lỗi xảy ra khi tạo tài khoản.']);
        }
    }

    /**
     * Đăng xuất
     * GET /logout
     */
    public function logout(): void
    {
        AUTHGUARD()->logout();
        $this->redirect('/login', ['success' => 'Đã đăng xuất thành công.']);
    }
}
