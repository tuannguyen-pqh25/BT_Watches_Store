<div align="center">
  <img src="https://via.placeholder.com/150?text=BT+WATCH+LOGO" alt="BT Watch Logo" width="120" />
  <h1>BT WATCH</h1>
  <p><strong>A Premium E-commerce Watch Store Platform</strong></p>

  <p>
    <img src="https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
    <img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
    <img src="https://img.shields.io/badge/Nginx-009639?style=for-the-badge&logo=nginx&logoColor=white" alt="Nginx" />
    <img src="https://img.shields.io/badge/Cloudflare-F38020?style=for-the-badge&logo=Cloudflare&logoColor=white" alt="Cloudflare" />
    <img src="https://img.shields.io/badge/Architecture-MVC-563D7C?style=for-the-badge&logo=codeigniter&logoColor=white" alt="MVC" />
  </p>
  
  <p>
    <a href="#-english-documentation">English</a> •
    <a href="#-tài-liệu-tiếng-việt">Tiếng Việt</a>
  </p>
</div>

---

## 🇬🇧 English Documentation

### 📖 About The Project

**BT WATCH** is a complete, feature-rich E-commerce web application built specifically for selling premium watches. Developed from scratch using a custom PHP MVC (Model-View-Controller) architecture, the platform ensures high performance, clean code organization, and scalability. It includes a user-friendly storefront for customers and a comprehensive administrative dashboard for store managers.

### ✨ Key Features

**🧑‍💻 Customer / Storefront:**
- **Product Discovery:** Browse watches, filter by categories, and utilize search functionality.
- **Shopping Cart & Checkout:** Intuitive cart management with a seamless checkout process.
- **User Authentication:** Secure login, registration, and session management.
- **Profile Management:** Users can update personal details and track their order history.
- **Real-time Notifications:** Server-Sent Events (SSE) integration for instant updates.

**🛡️ Admin / Backend:**
- **Dashboard:** Overview of sales, total orders, and user statistics.
- **Product Management:** Full CRUD (Create, Read, Update, Delete) operations for watches.
- **Category Management:** Organize products efficiently.
- **Order Processing:** Track, update, and manage customer orders.
- **User Management:** Monitor and manage registered customers.

### 🛠️ Built With

- **Backend:** Raw PHP (>= 8.0) using Custom MVC Pattern
- **Routing:** [Bramus Router](https://github.com/bramus/router)
- **Templating:** [League Plates](https://platesphp.com/)
- **Database:** MySQL (using PDO)
- **Web Server & Proxies:** Nginx, Cloudflare Tunnels
- **Environment Management:** [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)
- **Email Services:** [PHPMailer](https://github.com/PHPMailer/PHPMailer)
- **Frontend:** HTML5, CSS3, JavaScript

### 📂 Folder Structure

```text
.
├── app/
│   ├── Controllers/   # Application controllers (Admin & Customer)
│   ├── Models/        # Database interaction models
│   ├── Views/         # Templates using League Plates
│   └── functions.php  # Global helper functions
├── database/          # SQL scripts (db.sql, seed.sql)
├── public/            # Document root (index.php, CSS, JS, images)
├── .env.example       # Example environment variables
└── composer.json      # PHP dependencies
```

### 🚀 Getting Started

Follow these steps to set up the project locally.

#### Prerequisites
- PHP >= 8.0 & PHP-FPM
- Nginx Server
- Composer
- MySQL Server
- Local SSL Certificates (e.g., using `mkcert`)

#### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/your-username/bt-watch.git
   cd bt-watch
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Configure Environment:**
   - Copy `.env.example` to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Update `.env` with your database credentials and SMTP settings.

4. **Database Setup:**
   - Create a new MySQL database for the project.
   - Import the schema and initial data:
     - Run `database/db.sql` to create tables.
     - Run `database/seed.sql` to populate sample data.

5. **Run the Application (Nginx + PHP-FPM):**
   - Start your PHP-FPM service (e.g., ports `9000` and `9001` for load balancing).
   - Configure Nginx with the provided routing rules.
   - Start the Nginx server and visit `https://bt-watch.localhost`.

<details>
<summary><b>Click to view Nginx Configuration (nginx.conf)</b></summary>

```nginx
# Load balancing for PHP-FPM
upstream cgi_pool {
    server 127.0.0.1:9000;
    server 127.0.0.1:9001;
}

# Redirect HTTP -> HTTPS for local domain
server {
    listen 80;
    server_name bt-watch.localhost;
    return 301 https://$host$request_uri;
}

# Main Server Block
server {
    listen 80;
    listen 443 ssl;
    http2 on;

    # Listen to both local domain and Cloudflare Tunnels
    server_name bt-watch.localhost ~^.*\.trycloudflare\.com$;

    # SSL Configuration (Replace with your cert paths)
    ssl_certificate "/path/to/your/certificate.pem";
    ssl_certificate_key "/path/to/your/private-key.pem";
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Document Root
    root "/path/to/bt-watch/public";
    index index.php;
    charset utf-8;

    # Static Assets Cache Optimization
    etag on;
    if_modified_since exact;

    location ~* \.(css|js|mjs|png|jpg|jpeg|gif|webp|ico|svg|woff2?)$ {
        expires 1y;
        add_header Cache-Control "public, max-age=31536000, immutable" always;
        add_header Vary "Accept-Encoding" always;
        try_files $uri =404;
    }

    # Front Controller Routing
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM & SSE (Server-Sent Events) Optimization
    location ~ \.php$ {
        fastcgi_pass cgi_pool;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        
        # SSE stream optimizations
        fastcgi_read_timeout 300;
        fastcgi_buffering off;
    }

    # Deny access to hidden/sensitive files
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```
</details>

### 👥 Authors

- **Nguyễn Minh Tuấn**
- **Nguyễn Thanh Bình**

---

## 🇻🇳 Tài Liệu Tiếng Việt

### 📖 Giới Thiệu Dự Án

**BT WATCH** là một ứng dụng web thương mại điện tử hoàn chỉnh, được xây dựng chuyên biệt cho việc kinh doanh đồng hồ cao cấp. Dự án được phát triển từ đầu dựa trên kiến trúc PHP MVC (Model-View-Controller) tự xây dựng, đảm bảo hiệu suất cao, cấu trúc code sạch và dễ dàng mở rộng. Ứng dụng bao gồm giao diện cửa hàng thân thiện cho khách hàng và bảng điều khiển quản trị (Admin Dashboard) toàn diện cho người quản lý.

### ✨ Các Tính Năng Nổi Bật

**🧑‍💻 Dành Cho Khách Hàng:**
- **Khám Phá Sản Phẩm:** Xem danh sách đồng hồ, lọc theo danh mục và tìm kiếm sản phẩm.
- **Giỏ Hàng & Thanh Toán:** Quản lý giỏ hàng trực quan và quy trình thanh toán mượt mà.
- **Xác Thực Người Dùng:** Đăng nhập, đăng ký và quản lý phiên (session) bảo mật.
- **Quản Lý Hồ Sơ:** Cập nhật thông tin cá nhân và xem lại lịch sử đơn hàng.
- **Thông Báo Thời Gian Thực:** Tích hợp Server-Sent Events (SSE) để nhận thông báo tức thì.

**🛡️ Dành Cho Quản Trị Viên:**
- **Bảng Điều Khiển (Dashboard):** Xem tổng quan về doanh thu, số lượng đơn hàng và thống kê người dùng.
- **Quản Lý Sản Phẩm:** Toàn quyền CRUD (Thêm, Xem, Sửa, Xóa) thông tin đồng hồ.
- **Quản Lý Danh Mục:** Phân loại và tổ chức sản phẩm hiệu quả.
- **Xử Lý Đơn Hàng:** Theo dõi, cập nhật trạng thái và quản lý đơn hàng của khách.
- **Quản Lý Người Dùng:** Giám sát và quản lý danh sách khách hàng đã đăng ký.

### 🛠️ Công Nghệ Sử Dụng

- **Backend:** PHP thuần (>= 8.0) theo chuẩn kiến trúc MVC
- **Routing (Định tuyến):** [Bramus Router](https://github.com/bramus/router)
- **Template Engine:** [League Plates](https://platesphp.com/)
- **Cơ Sở Dữ Liệu:** MySQL (Sử dụng PDO)
- **Web Server & Proxies:** Nginx, Cloudflare Tunnels
- **Quản Lý Biến Môi Trường:** [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)
- **Dịch Vụ Gửi Email:** [PHPMailer](https://github.com/PHPMailer/PHPMailer)
- **Frontend:** HTML5, CSS3, JavaScript

### 📂 Cấu Trúc Thư Mục

```text
.
├── app/
│   ├── Controllers/   # Các Controllers (cho Admin & Khách)
│   ├── Models/        # Các Models tương tác với Database
│   ├── Views/         # Giao diện (Sử dụng League Plates)
│   └── functions.php  # Các hàm hỗ trợ chung (Helper functions)
├── database/          # File SQL (db.sql, seed.sql)
├── public/            # Document root (index.php, CSS, JS, Hình ảnh)
├── .env.example       # File biến môi trường mẫu
└── composer.json      # Quản lý thư viện PHP
```

### 🚀 Hướng Dẫn Cài Đặt

Thực hiện các bước sau để chạy dự án trên máy cá nhân của bạn.

#### Yêu Cầu Hệ Thống
- PHP >= 8.0 & PHP-FPM
- Nginx Server
- Composer
- Máy chủ MySQL
- Cài đặt SSL Certificate cho localhost (ví dụ dùng `mkcert`)

#### Cài Đặt

1. **Clone dự án về máy:**
   ```bash
   git clone https://github.com/your-username/bt-watch.git
   cd bt-watch
   ```

2. **Cài đặt thư viện:**
   ```bash
   composer install
   ```

3. **Cấu Hình Môi Trường:**
   - Copy file `.env.example` thành `.env`:
     ```bash
     cp .env.example .env
     ```
   - Cập nhật thông tin kết nối Database và cấu hình SMTP (nếu cần) trong file `.env`.

4. **Thiết Lập Cơ Sở Dữ thực Liệu:**
   - Tạo một database mới trong MySQL.
   - Import dữ liệu:
     - Chạy file `database/db.sql` để tạo các cấu trúc bảng.
     - Chạy file `database/seed.sql` để thêm dữ liệu mẫu.

5. **Khởi Chạy Ứng Dụng (Nginx + PHP-FPM):**
   - Khởi động PHP-FPM trên hệ thống của bạn (ví dụ: chạy trên port `9000` và `9001` cho cấu hình load balancing).
   - Thiết lập cấu hình `nginx.conf` với routing được cung cấp ở bên dưới.
   - Khởi động Nginx và mở trình duyệt truy cập: `https://bt-watch.localhost`

<details>
<summary><b>Nhấn vào đây để xem cấu hình Nginx (nginx.conf)</b></summary>

```nginx
# Load balancing cho PHP-FPM
upstream cgi_pool {
    server 127.0.0.1:9000;
    server 127.0.0.1:9001;
}

# Redirect HTTP -> HTTPS riêng cho tên miền local
server {
    listen 80;
    server_name bt-watch.localhost;
    return 301 https://$host$request_uri;
}

# Gộp chung xử lý cho cả Cloudflare Tunnel và Local HTTPS
server {
    listen 80;
    listen 443 ssl;
    http2 on;

    # Chấp nhận cả tên miền local và tên miền ngẫu nhiên từ Cloudflare
    server_name bt-watch.localhost ~^.*\.trycloudflare\.com$;

    # Cấu hình SSL (Sửa lại đường dẫn tới file chứng chỉ của bạn)
    ssl_certificate "/path/to/your/certificate.pem";
    ssl_certificate_key "/path/to/your/private-key.pem";
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Đường dẫn tới thư mục public của dự án
    root "/path/to/bt-watch/public";
    index index.php;
    charset utf-8;

    # Tối ưu HTTP Cache các file tĩnh
    etag on;
    if_modified_since exact;

    location ~* \.(css|js|mjs|png|jpg|jpeg|gif|webp|ico|svg|woff2?)$ {
        expires 1y;
        add_header Cache-Control "public, max-age=31536000, immutable" always;
        add_header Vary "Accept-Encoding" always;
        try_files $uri =404;
    }

    # Cấu hình Routing & Reverse Proxy cho Front Controller
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Tối ưu PHP-FPM và luồng SSE (Server-Sent Events)
    location ~ \.php$ {
        fastcgi_pass cgi_pool;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        
        # Tắt buffer để tối ưu SSE stream
        fastcgi_read_timeout 300;
        fastcgi_buffering off;
    }

    # Chặn truy cập các file ẩn/nhạy cảm
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```
</details>

### 👥 Nhóm Tác Giả

- **Nguyễn Minh Tuấn**
- **Nguyễn Thanh Bình**