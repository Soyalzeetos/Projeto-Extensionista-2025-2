SET NAMES 'utf8mb4';
ALTER DATABASE center_ferramentas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    password_hash VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS promotions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    discount_percentage DECIMAL(5, 2) NOT NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    image_data LONGBLOB,
    image_mime VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS product_promotions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    promotion_id INT NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (promotion_id) REFERENCES promotions(id) ON DELETE CASCADE
);

INSERT INTO categories (name, description) VALUES ('Ferramentas Gerais', 'Ferramentas diversas');

INSERT INTO products (name, description, price, category_id, stock_quantity, image_data, image_mime) VALUES
('Serra de Bancada Stanley', 'Potência de 1800W e alta precisão.', 2499.00, 1, 10, NULL, NULL),
('Parafusadeira DeWalt 20V', 'Motor Brushless e bateria longa duração.', 1499.00, 1, 25, NULL, NULL),
('Martelete Combinado Makita', 'Performance industrial.', 899.90, 1, 15, NULL, NULL);

INSERT INTO promotions (name, discount_percentage, start_date, end_date)
VALUES ('Oferta de Lançamento', 15.00, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY));

INSERT INTO product_promotions (product_id, promotion_id) VALUES (1, 1), (2, 1);
