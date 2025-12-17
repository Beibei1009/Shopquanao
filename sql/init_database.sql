
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255),  
    role VARCHAR(50) DEFAULT 'customer' CHECK (role IN ('customer', 'admin')),
    is_active BOOLEAN DEFAULT TRUE,
    google_id VARCHAR(255) UNIQUE, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);




INSERT INTO categories (name, slug, description) VALUES
('Áo thun', 'ao-thun', 'Áo thun nam nữ thời trang'),
('Áo sơ mi', 'ao-so-mi', 'Áo sơ mi công sở, dạo phố'),
('Quần jean', 'quan-jean', 'Quần jean nam nữ các kiểu dáng'),
('Váy đầm', 'vay-dam', 'Váy đầm nữ dự tiệc, công sở'),
('Phụ kiện', 'phu-kien', 'Phụ kiện thời trang đa dạng');


CREATE TABLE products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    price DECIMAL(12, 2) NOT NULL DEFAULT 0,
    stock_quantity INTEGER DEFAULT 0 CHECK (stock_quantity >= 0),
    image VARCHAR(255),
    category_id INTEGER REFERENCES categories(id) ON DELETE SET NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


INSERT INTO products (name, slug, description, price, stock_quantity, category_id, is_active) VALUES
('Áo thun trắng basic', 'ao-thun-trang-basic', 'Áo thun trắng cotton 100% form rộng thoải mái', 199000, 50, 1, TRUE),
('Áo thun đen basic', 'ao-thun-den-basic', 'Áo thun đen cotton 100% form rộng thoải mái', 199000, 50, 1, TRUE),
('Áo sơ mi trắng Oxford', 'ao-so-mi-trang-oxford', 'Áo sơ mi trắng vải Oxford cao cấp, phù hợp công sở', 350000, 30, 2, TRUE);

-- 
CREATE TABLE cart_items (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    product_id INTEGER REFERENCES products(id) ON DELETE CASCADE,
    quantity INTEGER NOT NULL DEFAULT 1 CHECK (quantity > 0),
    size VARCHAR(10),
    color VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);




CREATE TABLE orders (
    id SERIAL PRIMARY KEY,
    order_code VARCHAR(50) UNIQUE NOT NULL,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,

    -- Customer info (snapshot at order time)
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_address TEXT NOT NULL,
    customer_note TEXT,

    -- Order amounts
    subtotal DECIMAL(12, 2) NOT NULL DEFAULT 0,
    shipping_fee DECIMAL(12, 2) DEFAULT 0,
    discount_amount DECIMAL(12, 2) DEFAULT 0,
    total_amount DECIMAL(12, 2) NOT NULL,

    -- Payment & status
    payment_method VARCHAR(50) DEFAULT 'cod',
    status VARCHAR(50) DEFAULT 'pending' CHECK (status IN ('pending', 'confirmed', 'processing', 'shipping', 'completed', 'cancelled')),

    -- Admin notes
    admin_note TEXT,
    cancelled_reason TEXT,

    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    confirmed_at TIMESTAMP,
    completed_at TIMESTAMP,
    cancelled_at TIMESTAMP
);




CREATE TABLE order_items (
    id SERIAL PRIMARY KEY,
    order_id INTEGER NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    product_id INTEGER REFERENCES products(id) ON DELETE SET NULL,

    -- Product snapshot (at order time)
    product_name VARCHAR(255) NOT NULL,
    product_image VARCHAR(255),
    product_sku VARCHAR(100),
    product_price DECIMAL(12, 2) NOT NULL,

    -- Item details
    size VARCHAR(10),
    color VARCHAR(50),
    quantity INTEGER NOT NULL DEFAULT 1,
    subtotal DECIMAL(12, 2) NOT NULL
);




CREATE TABLE contacts (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cấp quyền cho user neondb_owner
GRANT ALL PRIVILEGES ON SCHEMA public TO neondb_owner;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO neondb_owner;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO neondb_owner;

-- Cấp quyền tạo table
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO neondb_owner;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON SEQUENCES TO neondb_owner;

