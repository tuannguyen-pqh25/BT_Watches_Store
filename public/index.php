<?php
/**
 * public/index.php — Front Controller BT WATCH
 * [C3] MVC: Tất cả request đi qua đây → bramus/router map URL → Controller
 *
 * Đã chuyển sang cú pháp Controller@method giống Lab 4 để đúng chuẩn
 */

// ─── Nạp file khởi động ─────────────────────────────────────────────────
// bootstrap.php: autoload → .env → DB ($PDO) → session → $AUTHGUARD
require_once dirname(__DIR__) . '/bootstrap.php';

// ─── Khởi tạo Router ────────────────────────────────────────────────────
$router = new \Bramus\Router\Router();

// ═══════════════════════════════════════════════════════════════════════════
// TRANG CHỦ + TRANG TĨNH + SSE
// ═══════════════════════════════════════════════════════════════════════════

$router->get('/', '\App\Controllers\HomeController@index');
$router->get('/about', '\App\Controllers\HomeController@about');
$router->get('/contact', '\App\Controllers\HomeController@contact');

// [C5] Presence / SSE
$router->post('/ping', '\App\Controllers\SSEController@ping');
# Ngay khi người dùng truy cập vào trang web (EventSource trong admin.php và default.php)
$router->get('/sse-online', '\App\Controllers\SSEController@sseOnline');

// ═══════════════════════════════════════════════════════════════════════════
// SẢN PHẨM (Public)
// ═══════════════════════════════════════════════════════════════════════════

$router->get('/products', '\App\Controllers\ProductController@index');
$router->get('/products/(\d+)', '\App\Controllers\ProductController@show');

// ═══════════════════════════════════════════════════════════════════════════
// XÁC THỰC (Auth)
// ═══════════════════════════════════════════════════════════════════════════

$router->get('/login', '\App\Controllers\AuthController@showLogin');
$router->post('/login', '\App\Controllers\AuthController@login');
$router->get('/register', '\App\Controllers\AuthController@showRegister');
$router->post('/register', '\App\Controllers\AuthController@register');
$router->get('/logout', '\App\Controllers\AuthController@logout');

// ═══════════════════════════════════════════════════════════════════════════
// GIỎ HÀNG
// ═══════════════════════════════════════════════════════════════════════════

$router->get('/cart', '\App\Controllers\CartController@index');
$router->post('/cart/add', '\App\Controllers\CartController@add');
$router->post('/cart/update', '\App\Controllers\CartController@update');
$router->post('/cart/remove', '\App\Controllers\CartController@remove');

// ═══════════════════════════════════════════════════════════════════════════
// THANH TOÁN
// ═══════════════════════════════════════════════════════════════════════════

$router->get('/checkout', '\App\Controllers\CheckoutController@index');
$router->post('/checkout', '\App\Controllers\CheckoutController@process');

// ═══════════════════════════════════════════════════════════════════════════
// HỒ SƠ CÁ NHÂN (Khách hàng)
// ═══════════════════════════════════════════════════════════════════════════

$router->get('/profile', '\App\Controllers\ProfileController@show');
$router->post('/profile/update', '\App\Controllers\ProfileController@update');
$router->post('/profile/password', '\App\Controllers\ProfileController@changePassword');
$router->post('/profile/avatar', '\App\Controllers\ProfileController@updateAvatar');

// ═══════════════════════════════════════════════════════════════════════════
// ĐƠN HÀNG (Khách hàng xem đơn của mình)
// ═══════════════════════════════════════════════════════════════════════════

$router->get('/orders', '\App\Controllers\OrderController@history');
$router->get('/orders/(\d+)', '\App\Controllers\OrderController@show');

// ═══════════════════════════════════════════════════════════════════════════
// ADMIN — Nhóm route quản trị (tất cả yêu cầu role = admin)
// ═══════════════════════════════════════════════════════════════════════════

$router->mount('/admin', function () use ($router) {
    // Dashboard
    $router->get('/', '\App\Controllers\Admin\DashboardController@index');

    // Quản lý sản phẩm
    $router->get('/products', '\App\Controllers\Admin\ProductController@index');
    $router->get('/products/create', '\App\Controllers\Admin\ProductController@create');
    $router->post('/products/store', '\App\Controllers\Admin\ProductController@store');
    $router->get('/products/(\d+)/edit', '\App\Controllers\Admin\ProductController@edit');
    $router->post('/products/(\d+)/update', '\App\Controllers\Admin\ProductController@update');
    $router->post('/products/(\d+)/delete', '\App\Controllers\Admin\ProductController@delete');

    // Quản lý danh mục
    $router->get('/categories', '\App\Controllers\Admin\CategoryController@index');
    $router->get('/categories/create', '\App\Controllers\Admin\CategoryController@create');
    $router->post('/categories/store', '\App\Controllers\Admin\CategoryController@store');
    $router->get('/categories/(\d+)/edit', '\App\Controllers\Admin\CategoryController@edit');
    $router->post('/categories/(\d+)/update', '\App\Controllers\Admin\CategoryController@update');
    $router->post('/categories/(\d+)/delete', '\App\Controllers\Admin\CategoryController@delete');

    // Quản lý đơn hàng
    $router->get('/orders', '\App\Controllers\Admin\OrderController@index');
    $router->get('/orders/(\d+)', '\App\Controllers\Admin\OrderController@show');
    $router->post('/orders/(\d+)/status', '\App\Controllers\Admin\OrderController@updateStatus');
    $router->get('/sse-pending', '\App\Controllers\Admin\OrderController@ssePending');

    // Quản lý người dùng
    $router->get('/users', '\App\Controllers\Admin\UserController@index');

    // Hồ sơ Admin
    $router->get('/profile', '\App\Controllers\ProfileController@adminShow');
    $router->post('/profile/update', '\App\Controllers\ProfileController@adminUpdate');
    $router->post('/profile/password', '\App\Controllers\ProfileController@adminChangePassword');
    $router->post('/profile/avatar', '\App\Controllers\ProfileController@adminUpdateAvatar');
});

// [C4] CSRF Middleware: Bảo vệ tất cả form POST khỏi giả mạo request
$router->before('POST', '/.*', function () {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if ($path === '/ping') return; // Skip CSRF

    if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
        http_response_code(403);
        echo 'Invalid CSRF token. Please refresh the page and try again.';
        exit();
    }
});

// ═══════════════════════════════════════════════════════════════════════════
// 404 — Trang không tìm thấy
// ═══════════════════════════════════════════════════════════════════════════

$router->set404(function () {
    (new \App\Controllers\HomeController())->sendNotFound();
});

// ─── Chạy Router ─────────────────────────────────────────────────────────
$router->run();