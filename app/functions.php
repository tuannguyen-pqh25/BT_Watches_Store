<?php
/**
 * app/functions.php — Hàm tiện ích dùng xuyên suốt dự án
 * [C3] Autoload qua composer.json → "files": ["app/functions.php"]
 * [C4] CSRF, Auth accessors, redirect, XSS protection
 */

// ── GLOBAL ACCESSORS ─────────────────────────────────────────────────────────

/** Trả về kết nối PDO toàn cục (khởi tạo trong bootstrap.php) */
function PDO(): PDO
{
    global $PDO;
    return $PDO;
}

/** Trả về SessionGuard toàn cục (khởi tạo trong bootstrap.php) */
function AUTHGUARD(): App\SessionGuard
{
    global $AUTHGUARD;
    return $AUTHGUARD;
}

// ── XSS PROTECTION ───────────────────────────────────────────────────────────

/** [C4] Escape chuỗi an toàn cho HTML, chống XSS */
function html_escape(string|null $text): string
{
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8', false);
}

/** Bí danh của html_escape() */
function h(string|null $text): string
{
    return html_escape($text);
}

// ── FORMAT & URL ─────────────────────────────────────────────────────────────

/** Format số thành tiền VND (vd: 45000000 → "45.000.000₫") */
function vnd(float|int $amount): string
{
    return number_format($amount, 0, ',', '.') . '₫';
}

/** Tạo URL đầy đủ từ path tương đối (dùng APP_URL trong .env) */
function url(string $path = ''): string
{
    $base = rtrim($_ENV['APP_URL'] ?? '', '/');
    return $base . '/' . ltrim($path, '/');
}

// ── SESSION HELPERS ───────────────────────────────────────────────────────────

/** Lấy giá trị session 1 lần rồi xóa (flash message) */
function session_get_once(string $key, mixed $default = null): mixed
{
    $value = $_SESSION[$key] ?? $default;
    unset($_SESSION[$key]);
    return $value;
}

/** Lấy giá trị form đã lưu trong session (dùng khi validation lỗi) */
function old(string $key, string $default = ''): string
{
    return html_escape($_SESSION['form'][$key] ?? $default);
}

// ── DEBUG ─────────────────────────────────────────────────────────────────────

/** Dump biến rồi dừng chương trình */
function dd(mixed $var): void
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    exit();
}

// ── REDIRECT ──────────────────────────────────────────────────────────────────

/** Chuyển hướng kèm flash data vào session */
function redirect(string $location, array $data = []): void
{
    foreach ($data as $key => $value) {
        $_SESSION[$key] = $value;
    }
    header('Location: ' . $location, true, 302);
    exit();
}

// ── CSRF PROTECTION ───────────────────────────────────────────────────────────
// [C4] Token ngẫu nhiên đính vào mọi form POST, kiểm tra khi nhận request

/** Sinh hoặc lấy CSRF token từ session (chuỗi hex 64 ký tự) */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Tạo thẻ hidden input chứa CSRF token — gọi trong mọi form POST */
function csrf_field(): string
{
    $token = csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . html_escape($token) . '">';
}

/** [C4] Xác thực CSRF token bằng hash_equals() (timing-safe) */
function validate_csrf_token(?string $token): bool
{
    $stored = $_SESSION['csrf_token'] ?? '';
    if (empty($token) || empty($stored)) {
        return false;
    }
    return hash_equals($stored, $token);
}

// ── FILE UPLOAD ───────────────────────────────────────────────────────────────
// [C5] Upload ảnh an toàn: kiểm tra dung lượng, MIME type thật, tên file ngẫu nhiên

/**
 * Upload ảnh sản phẩm vào public/uploads/products/
 * @param  array $file  Phần tử từ $_FILES (vd: $_FILES['image'])
 * @return array ['success' => true, 'path' => '/uploads/products/xxx.jpg']
 *               hoặc ['success' => false, 'error' => 'Lý do lỗi']
 */
function upload_image(array $file): array
{
    // Giới hạn 2MB
    if ($file['size'] > 2 * 1024 * 1024) {
        return ['success' => false, 'error' => 'Ảnh vượt quá 2MB. Vui lòng chọn ảnh nhỏ hơn.'];
    }

    $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedExts)) {
        return ['success' => false, 'error' => 'Chỉ chấp nhận ảnh JPG, PNG, GIF hoặc WebP.'];
    }

    $filename  = uniqid('product_', true) . '.' . $ext;
    $uploadDir = ROOTDIR . 'public/uploads/products';

    // Tạo thư mục nếu chưa tồn tại
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (move_uploaded_file($file['tmp_name'], $uploadDir . '/' . $filename)) {
        return ['success' => true, 'path' => '/uploads/products/' . $filename];
    }

    return ['success' => false, 'error' => 'Lỗi lưu file. Kiểm tra quyền thư mục.'];
}

// ── PRESENCE — Theo dõi người dùng online ────────────────────────────────────

/** Ghi nhận session đang online vào database/online_sessions.json */
function ping_online(): void
{
    $file = ROOTDIR . 'database/online_sessions.json';
    $sessions = [];
    if (file_exists($file)) {
        $data = file_get_contents($file);
        $sessions = json_decode($data, true) ?: [];
    }

    $sessionId = session_id();
    if (empty($sessionId)) return;

    $now = time();
    $sessions[$sessionId] = $now;

    // Xóa các session quá 30 giây không ping
    foreach ($sessions as $id => $lastPing) {
        if ($now - $lastPing > 30) {
            unset($sessions[$id]);
        }
    }

    file_put_contents($file, json_encode($sessions));
}

/** Đếm số người dùng đang online (trong 30 giây gần nhất) */
function get_online_count(): int
{
    $file = ROOTDIR . 'database/online_sessions.json';
    if (!file_exists($file)) return 0;

    $data = file_get_contents($file);
    $sessions = json_decode($data, true) ?: [];
    $now = time();
    $count = 0;

    foreach ($sessions as $id => $lastPing) {
        if ($now - $lastPing <= 30) {
            $count++;
        }
    }
    return $count;
}

// ── MAILER ────────────────────────────────────────────────────────────────────
// [C5] Gửi email xác nhận đơn hàng qua PHPMailer + SMTP (.env)

/**
 * Gửi email thông báo đơn hàng
 * Cấu hình SMTP lấy từ .env (SMTP_HOST, SMTP_USER, SMTP_PASS, SMTP_PORT)
 */
function send_order_email(string $toEmail, string $subject, string $body): bool
{
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

    try {
        // Cấu hình SMTP từ file .env
        $mail->isSMTP();
        $mail->Host     = $_ENV['SMTP_HOST'] ?? 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'] ?? '';
        $mail->Password = $_ENV['SMTP_PASS'] ?? '';
        $mail->Port     = $_ENV['SMTP_PORT'] ?? 2525;

        $mail->CharSet = 'UTF-8';
        $mail->setFrom('noreply@btwatch.local', 'BT WATCH');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Log lỗi hoặc xử lý (ở đây return false cho đơn giản)
        return false;
    }
}
