-- Database initialization for iPhone Cases Website

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    oauth_provider VARCHAR(50) NULL,
    oauth_id VARCHAR(255) NULL,
    reset_token VARCHAR(255) NULL,
    reset_token_expiry DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_oauth (oauth_provider, oauth_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    parent_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products table (iPhone cases)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    price DECIMAL(10, 2) NOT NULL,
    iphone_model VARCHAR(50) NOT NULL,
    color VARCHAR(50) NULL,
    material VARCHAR(50) NULL,
    image_url VARCHAR(255) NULL,
    stock_quantity INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_slug (slug),
    INDEX idx_category (category_id),
    INDEX idx_iphone_model (iphone_model),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Store locations table
CREATE TABLE IF NOT EXISTS stores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NULL,
    latitude DECIMAL(10, 8) NULL,
    longitude DECIMAL(11, 8) NULL,
    image_url VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_city (city)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Product availability at stores
CREATE TABLE IF NOT EXISTS product_store_availability (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    store_id INT NOT NULL,
    quantity INT DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE,
    UNIQUE KEY unique_product_store (product_id, store_id),
    INDEX idx_product (product_id),
    INDEX idx_store (store_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample categories
INSERT INTO categories (name, slug, description) VALUES
('Clear Cases', 'clear-cases', 'Transparent protective cases'),
('Leather Cases', 'leather-cases', 'Premium leather cases'),
('Silicone Cases', 'silicone-cases', 'Soft silicone protective cases'),
('Rugged Cases', 'rugged-cases', 'Heavy-duty protection cases'),
('Wallet Cases', 'wallet-cases', 'Cases with card holders');

-- Insert sample stores
INSERT INTO stores (name, address, city, phone, latitude, longitude, image_url) VALUES
('Ho Chi Minh Store', '268 Ly Thuong Kiet, District 10', 'Ho Chi Minh City', '028-1234-5678', 10.7721556, 106.6576888, '/images/stores/hcm-store.jpg'),
('District 1 Store', '123 Le Loi, District 1', 'Ho Chi Minh City', '028-9876-5432', 10.7756580, 106.7004710, '/images/stores/district1-store.jpg'),
('Hanoi Store', '45 Tran Duy Hung, Cau Giay', 'Hanoi', '024-1111-2222', 21.0285978, 105.7825405, '/images/stores/hanoi-store.jpg');

-- Insert sample iPhone cases
INSERT INTO products (category_id, name, slug, description, price, iphone_model, color, material, image_url, stock_quantity) VALUES
(1, 'Clear Case for iPhone 15 Pro Max', 'clear-case-iphone-15-pro-max', 'Crystal clear case for the 15 Pro Max', 299000, 'iPhone 15 Pro Max', 'Clear', 'TPU', '/images/products/clear-15promax.jpg', 50),
(1, 'Clear Case for iPhone 14', 'clear-case-iphone-14', 'Basic transparent case for iPhone 14', 229000, 'iPhone 14', 'Clear', 'Polycarbonate', '/images/products/clear-14.jpg', 70),
(1, 'Clear Case for iPhone 14 Plus', 'clear-case-iphone-14-plus', 'Thin clear case with MagSafe support', 319000, 'iPhone 14 Plus', 'Clear', 'TPU', '/images/products/clear-14plus.jpg', 40),
(2, 'Leather Case for iPhone 15', 'leather-case-iphone-15', 'Premium tan leather case', 849000, 'iPhone 15', 'Tan', 'Leather', '/images/products/leather-15-tan.jpg', 25),
(2, 'Leather Case for iPhone 15 Plus', 'leather-case-iphone-15-plus', 'Elegant case in forest green leather', 849000, 'iPhone 15 Plus', 'Forest Green', 'Leather', '/images/products/leather-15plus-green.jpg', 22),
(2, 'Leather Case for iPhone 14 Pro Max', 'leather-case-iphone-14-pro-max', 'Classic leather protection', 799000, 'iPhone 14 Pro Max', 'Red', 'Leather', '/images/products/leather-14promax-red.jpg', 18),
(3, 'Silicone Case for iPhone 15 Pro', 'silicone-case-iphone-15-pro', 'Soft-touch silicone case with microfiber lining', 349000, 'iPhone 15 Pro', 'Blue', 'Silicone', '/images/products/silicone-15pro-blue.jpg', 80),
(3, 'Silicone Case for iPhone 15 Pro Max', 'silicone-case-iphone-15-pro-max', 'White silicone case', 349000, 'iPhone 15 Pro Max', 'White', 'Silicone', '/images/products/silicone-15promax-white.jpg', 75),
(3, 'Silicone Case for iPhone 14 Plus', 'silicone-case-iphone-14-plus', 'Bright yellow silicone case', 329000, 'iPhone 14 Plus', 'Yellow', 'Silicone', '/images/products/silicone-14plus-yellow.jpg', 50),
(4, 'Rugged Case for iPhone 15 Pro Max', 'rugged-case-iphone-15-pro-max', 'Heavy-duty protection with kickstand', 649000, 'iPhone 15 Pro Max', 'Graphite', 'Polycarbonate/Rubber', '/images/products/rugged-15promax.jpg', 35),
(4, 'Rugged Case for iPhone 14', 'rugged-case-iphone-14', 'Dual-layer shockproof case', 549000, 'iPhone 14', 'Black', 'TPU/Polycarbonate', '/images/products/rugged-14.jpg', 40),
(4, 'Rugged Case for iPhone 15 Plus', 'rugged-case-iphone-15-plus', 'Slim rugged case in olive green', 599000, 'iPhone 15 Plus', 'Olive Green', 'TPU', '/images/products/rugged-15plus-olive.jpg', 42),
(5, 'Wallet Case for iPhone 15 Pro', 'wallet-case-iphone-15-pro', 'Black leather wallet case, holds 3 cards', 529000, 'iPhone 15 Pro', 'Black', 'PU Leather', '/images/products/wallet-15pro.jpg', 30),
(5, 'Wallet Case for iPhone 14 Pro', 'wallet-case-iphone-14-pro', 'Brown genuine leather folio case', 699000, 'iPhone 14 Pro', 'Brown', 'Genuine Leather', '/images/products/wallet-14pro-brown.jpg', 28),
(5, 'Wallet Case for iPhone 15 Pro Max', 'wallet-case-iphone-15-pro-max', 'Blue folio case with magnetic clasp', 549000, 'iPhone 15 Pro Max', 'Blue', 'PU Leather', '/images/products/wallet-15promax-blue.jpg', 33),
(1, 'Clear Case for iPhone 13', 'clear-case-iphone-13', 'Basic clear case for iPhone 13', 199000, 'iPhone 13', 'Clear', 'TPU', '/images/products/clear-13.jpg', 100),
(3, 'Silicone Case for iPhone 13 Pro', 'silicone-case-iphone-13-pro', 'Red silicone case for iPhone 13 Pro', 299000, 'iPhone 13 Pro', 'Red', 'Silicone', '/images/products/silicone-13pro-red.jpg', 60),
(4, 'Rugged Case for iPhone 13 Pro Max', 'rugged-case-iphone-13-pro-max', 'Camo pattern heavy-duty case', 499000, 'iPhone 13 Pro Max', 'Camo', 'Polycarbonate', '/images/products/rugged-13promax-camo.jpg', 20),
(2, 'Leather Case for iPhone 13 Mini', 'leather-case-iphone-13-mini', 'Saddle brown leather case for 13 mini', 749000, 'iPhone 13 Mini', 'Saddle Brown', 'Leather', '/images/products/leather-13mini.jpg', 15),
(5, 'Wallet Case for iPhone 14', 'wallet-case-iphone-14', 'Simple black wallet case with 2 card slots', 449000, 'iPhone 14', 'Black', 'PU Leather', '/images/products/wallet-14-black.jpg', 38),
(1, 'Clear Case for iPhone 15 Pro', 'clear-case-iphone-15-pro', 'Crystal clear protective case with anti-yellowing technology', 299000, 'iPhone 15 Pro', 'Clear', 'TPU', '/images/products/clear-15pro.jpg', 50),
(1, 'Clear Case for iPhone 15', 'clear-case-iphone-15', 'Ultra-thin transparent case', 249000, 'iPhone 15', 'Clear', 'TPU', '/images/products/clear-15.jpg', 45),
(2, 'Leather Case for iPhone 15 Pro Max', 'leather-case-iphone-15-pro-max', 'Premium genuine leather case', 899000, 'iPhone 15 Pro Max', 'Black', 'Leather', '/images/products/leather-15promax.jpg', 30),
(2, 'Leather Case for iPhone 14 Pro', 'leather-case-iphone-14-pro', 'Elegant leather protection', 799000, 'iPhone 14 Pro', 'Brown', 'Leather', '/images/products/leather-14pro.jpg', 25),
(3, 'Silicone Case for iPhone 15', 'silicone-case-iphone-15', 'Soft-touch silicone case', 349000, 'iPhone 15', 'Midnight', 'Silicone', '/images/products/silicone-15.jpg', 60),
(3, 'Silicone Case for iPhone 14', 'silicone-case-iphone-14', 'Colorful silicone protection', 329000, 'iPhone 14', 'Pink', 'Silicone', '/images/products/silicone-14.jpg', 55),
(4, 'Rugged Case for iPhone 15 Pro', 'rugged-case-iphone-15-pro', 'Military-grade drop protection', 599000, 'iPhone 15 Pro', 'Black', 'Polycarbonate', '/images/products/rugged-15pro.jpg', 40),
(5, 'Wallet Case for iPhone 15', 'wallet-case-iphone-15', 'Case with card slots and stand', 499000, 'iPhone 15', 'Navy', 'PU Leather', '/images/products/wallet-15.jpg', 35);

-- Insert sample availability (with some out of stock)
INSERT INTO product_store_availability (product_id, store_id, quantity) VALUES
-- Store 1 (Ho Chi Minh Store)
(1, 1, 20), (2, 1, 15), (3, 1, 0), (4, 1, 8), (5, 1, 25),
(6, 1, 20), (7, 1, 0), (8, 1, 12), (9, 1, 18), (10, 1, 22),
(11, 1, 14), (12, 1, 16), (13, 1, 0), (14, 1, 12), (15, 1, 17),
(16, 1, 25), (17, 1, 30), (18, 1, 0), (19, 1, 8), (20, 1, 15),
(21, 1, 20), (22, 1, 0), (23, 1, 18), (24, 1, 14), (25, 1, 16),
(26, 1, 35), (27, 1, 28), (28, 1, 0),

-- Store 2 (District 1 Store)
(1, 2, 15), (2, 2, 0), (3, 2, 10), (4, 2, 10), (5, 2, 20),
(6, 2, 0), (7, 2, 13), (8, 2, 13), (9, 2, 0), (10, 2, 19),
(11, 2, 11), (12, 2, 0), (13, 2, 21), (14, 2, 15), (15, 2, 0),
(16, 2, 28), (17, 2, 25), (18, 2, 8), (19, 2, 0), (20, 2, 12),
(21, 2, 18), (22, 2, 10), (23, 2, 0), (24, 2, 12), (25, 2, 14),
(26, 2, 0), (27, 2, 22), (28, 2, 3),

-- Store 3 (Hanoi Store)
(1, 3, 0), (2, 3, 10), (3, 3, 10), (4, 3, 0), (5, 3, 15),
(6, 3, 17), (7, 3, 12), (8, 3, 0), (9, 3, 14), (10, 3, 0),
(11, 3, 10), (12, 3, 13), (13, 3, 17), (14, 3, 0), (15, 3, 15),
(16, 3, 0), (17, 3, 20), (18, 3, 6), (19, 3, 5), (20, 3, 0),
(21, 3, 15), (22, 3, 8), (23, 3, 14), (24, 3, 0), (25, 3, 12),
(26, 3, 30), (27, 3, 0), (28, 3, 2);

-- Insert sample user
-- Password: `password`
INSERT INTO users (email, password, full_name) VALUES
('user@user.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'NguyenSybau 67');
