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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    image_url VARCHAR(255) NOT NULL,
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

INSERT INTO products (name, description, price, image_url, category_id, stock_quantity) VALUES
('Serra de Bancada Stanley', 'Potência de 1800W e alta precisão.', 2499.00, 'assets/img/produtos/serra_circular.webp', 1, 10),
('Parafusadeira DeWalt 20V', 'Motor Brushless e bateria longa duração.', 1499.00, 'assets/img/produtos/zoom-1712-A7019.webp', 1, 25),
('Martelete Combinado Makita', 'Performance industrial.', 899.90, 'assets/img/produtos/martelete-makita.webp', 1, 15);

INSERT INTO promotions (name, discount_percentage, start_date, end_date)
VALUES ('Oferta de Lançamento', 15.00, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY));

INSERT INTO product_promotions (product_id, promotion_id) VALUES (1, 1), (2, 1);
