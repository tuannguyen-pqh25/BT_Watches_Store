-- ============================================================================
-- BT WATCH — WatchStore | CT275
-- Database: bt_watch (PostgreSQL)
-- 5 bảng, 4 khóa ngoại
-- ============================================================================

-- [C2] Bảng 1: users — Người dùng (customer / admin)
CREATE TABLE IF NOT EXISTS users (
    id            SERIAL PRIMARY KEY,
    email         VARCHAR(100) UNIQUE NOT NULL,
    password      VARCHAR(255) NOT NULL,
    full_name     VARCHAR(100) NOT NULL,
    phone         VARCHAR(20),
    avatar_path   VARCHAR(255),
    role          VARCHAR(20)  NOT NULL DEFAULT 'customer',  -- 'customer' | 'admin'
    created_at    TIMESTAMP    NOT NULL DEFAULT NOW(),
    updated_at    TIMESTAMP    NOT NULL DEFAULT NOW()
);

-- [C2] Bảng 2: categories — Danh mục sản phẩm
CREATE TABLE IF NOT EXISTS categories (
    id          SERIAL PRIMARY KEY,
    name        VARCHAR(100) UNIQUE NOT NULL,
    slug        VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    icon        VARCHAR(50),
    created_at  TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at  TIMESTAMP NOT NULL DEFAULT NOW()
);

-- [C2] Bảng 3: products — Sản phẩm (FK → categories)
CREATE TABLE IF NOT EXISTS products (
    id             SERIAL PRIMARY KEY,
    category_id    INTEGER REFERENCES categories(id) ON DELETE SET NULL,
    name           VARCHAR(200) NOT NULL,
    slug           VARCHAR(200) UNIQUE NOT NULL,
    description    TEXT,
    price          DECIMAL(15,2) NOT NULL,
    stock_quantity INTEGER NOT NULL DEFAULT 0,
    image_path     VARCHAR(255),
    brand          VARCHAR(100),
    status         VARCHAR(20) NOT NULL DEFAULT 'show',  -- 'show' | 'hide'
    created_at     TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at     TIMESTAMP NOT NULL DEFAULT NOW()      -- dùng cho HTTP Caching [C5]
);

-- [C2] Bảng 4: orders — Đơn hàng (FK → users)
-- Quy trình: Chờ xác nhận → Đã xác nhận → Đang giao → Hoàn thành
--            (hoặc → Đã hủy ở bất kỳ bước nào trước Hoàn thành)
CREATE TABLE IF NOT EXISTS orders (
    id               SERIAL PRIMARY KEY,
    user_id          INTEGER REFERENCES users(id) ON DELETE SET NULL,
    order_code       VARCHAR(20) UNIQUE NOT NULL,
    status           VARCHAR(30) NOT NULL DEFAULT 'pending',
    total_amount     DECIMAL(15,2) NOT NULL,
    shipping_name    VARCHAR(100) NOT NULL,
    shipping_address TEXT NOT NULL,
    shipping_phone   VARCHAR(20) NOT NULL,
    shipping_email   VARCHAR(100),       -- dùng để gửi email PHPMailer khi 'Đã xác nhận'
    payment_method   VARCHAR(30) NOT NULL DEFAULT 'COD',
    payment_status   VARCHAR(30) NOT NULL DEFAULT 'Chưa thanh toán',
    note             TEXT,
    created_at       TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at       TIMESTAMP NOT NULL DEFAULT NOW()
);

-- [C2] Bảng 5: order_items — Chi tiết đơn hàng (FK → orders, FK → products)
CREATE TABLE IF NOT EXISTS order_items (
    id         SERIAL PRIMARY KEY,
    order_id   INTEGER NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    product_id INTEGER NOT NULL REFERENCES products(id),
    quantity   INTEGER NOT NULL,
    unit_price DECIMAL(15,2) NOT NULL
);

-- ============================================================================
-- Indexes — Tăng tốc truy vấn
-- ============================================================================
CREATE INDEX IF NOT EXISTS idx_products_category   ON products(category_id);
CREATE INDEX IF NOT EXISTS idx_products_brand      ON products(brand);
CREATE INDEX IF NOT EXISTS idx_products_status     ON products(status);
CREATE INDEX IF NOT EXISTS idx_orders_user         ON orders(user_id);
CREATE INDEX IF NOT EXISTS idx_orders_status       ON orders(status);
CREATE INDEX IF NOT EXISTS idx_order_items_order   ON order_items(order_id);

