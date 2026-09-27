<?php
/**
 * bootstrap.php — Khởi động ứng dụng
 * [C3] Được require đầu tiên bởi public/index.php
 */

// 1. Định nghĩa hằng số đường dẫn
define('ROOTDIR', __DIR__ . DIRECTORY_SEPARATOR);
define('VIEWS_PATH', ROOTDIR . 'app' . DIRECTORY_SEPARATOR . 'Views');

// 2. Composer Autoload (PSR-4 + helper functions)
require_once ROOTDIR . 'vendor/autoload.php';

// 3. Load biến môi trường từ file .env
$dotenv = Dotenv\Dotenv::createImmutable(ROOTDIR);
$dotenv->safeLoad(); // safeLoad() không báo lỗi nếu .env thiếu (an toàn hơn load())

// 4. Kết nối PostgreSQL qua PDO
// [C2] PDOFactory tạo kết nối với cấu hình trong .env
try {
    $PDO = (new App\Models\PDOFactory())->create([
        'dbhost' => $_ENV['DB_HOST'],
        'dbname' => $_ENV['DB_NAME'],
        'dbuser' => $_ENV['DB_USER'],
        'dbpass' => $_ENV['DB_PASS'],
    ]);
} catch (\PDOException $ex) {
    http_response_code(500);
    echo '<h1>Lỗi kết nối CSDL</h1>';
    echo '<p>Kiểm tra lại thông tin trong file <code>.env</code></p>';
    if (($_ENV['APP_DEBUG'] ?? 'true') === 'true') {
        echo '<pre>' . h($ex->getMessage()) . '</pre>';
    }
    exit;
}

// 5. Khởi động Session
// [C5] session_cache_limiter('') — ngăn PHP tự chèn header Cache-Control/Pragma
if (session_status() === PHP_SESSION_NONE) {
    session_cache_limiter('');
    session_start();
}

// 6. Auth Guard — quản lý đăng nhập qua session
// [C4] Phải sau session_start()
$AUTHGUARD = new App\SessionGuard();
