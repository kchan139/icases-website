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
INSERT INTO stores (name, address, city, phone, latitude, longitude) VALUES
('Ho Chi Minh Store', '268 Ly Thuong Kiet, District 10', 'Ho Chi Minh City', '028-1234-5678', 10.7721556, 106.6576888),
('District 1 Store', '123 Le Loi, District 1', 'Ho Chi Minh City', '028-9876-5432', 10.7756580, 106.7004710),
('Hanoi Store', '45 Tran Duy Hung, Cau Giay', 'Hanoi', '024-1111-2222', 21.0285978, 105.7825405);

-- Insert sample iPhone cases
INSERT INTO products (category_id, name, slug, description, price, iphone_model, color, material, image_url, stock_quantity) VALUES
(1, 'Clear Case for iPhone 15 Pro', 'clear-case-iphone-15-pro', 'Crystal clear protective case with anti-yellowing technology', 299000, 'iPhone 15 Pro', 'Clear', 'TPU', '/images/products/clear-15pro.jpg', 50),
(1, 'Clear Case for iPhone 15', 'clear-case-iphone-15', 'Ultra-thin transparent case', 249000, 'iPhone 15', 'Clear', 'TPU', '/images/products/clear-15.jpg', 45),
(2, 'Leather Case for iPhone 15 Pro Max', 'leather-case-iphone-15-pro-max', 'Premium genuine leather case', 899000, 'iPhone 15 Pro Max', 'Black', 'Leather', '/images/products/leather-15promax.jpg', 30),
(2, 'Leather Case for iPhone 14 Pro', 'leather-case-iphone-14-pro', 'Elegant leather protection', 799000, 'iPhone 14 Pro', 'Brown', 'Leather', '/images/products/leather-14pro.jpg', 25),
(3, 'Silicone Case for iPhone 15', 'silicone-case-iphone-15', 'Soft-touch silicone case', 349000, 'iPhone 15', 'Midnight', 'Silicone', '/images/products/silicone-15.jpg', 60),
(3, 'Silicone Case for iPhone 14', 'silicone-case-iphone-14', 'Colorful silicone protection', 329000, 'iPhone 14', 'Pink', 'Silicone', '/images/products/silicone-14.jpg', 55),
(4, 'Rugged Case for iPhone 15 Pro', 'rugged-case-iphone-15-pro', 'Military-grade drop protection', 599000, 'iPhone 15 Pro', 'Black', 'Polycarbonate', '/images/products/rugged-15pro.jpg', 40),
(5, 'Wallet Case for iPhone 15', 'wallet-case-iphone-15', 'Case with card slots and stand', 499000, 'iPhone 15', 'Navy', 'PU Leather', '/images/products/wallet-15.jpg', 35);

-- Insert sample availability
INSERT INTO product_store_availability (product_id, store_id, quantity) VALUES
(1, 1, 20), (1, 2, 15), (1, 3, 15),
(2, 1, 15), (2, 2, 20), (2, 3, 10),
(3, 1, 10), (3, 2, 10), (3, 3, 10),
(4, 1, 8), (4, 2, 10), (4, 3, 7),
(5, 1, 25), (5, 2, 20), (5, 3, 15),
(6, 1, 20), (6, 2, 18), (6, 3, 17),
(7, 1, 15), (7, 2, 13), (7, 3, 12),
(8, 1, 12), (8, 2, 13), (8, 3, 10);
