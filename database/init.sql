SET NAMES 'utf8mb4';

CREATE DATABASE IF NOT EXISTS center_ferramentas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE center_ferramentas;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    password_hash VARCHAR(255),
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    INDEX (token)
);

CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS role_permissions (
    role_id INT NOT NULL,
    permission_id INT NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    role_id INT NOT NULL,
    registration_number VARCHAR(20) UNIQUE,
    hire_date DATE,
    department VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE RESTRICT
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
    price_cash DECIMAL(10, 2) NOT NULL,
    price_installments DECIMAL(10, 2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    image_data LONGTEXT,
    image_mime VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS product_promotions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    promotion_id INT NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE,
    FOREIGN KEY (promotion_id) REFERENCES promotions (id) ON DELETE CASCADE
);

INSERT INTO
    roles (name, slug, description)
VALUES (
        'Administrador',
        'admin',
        'Acesso total ao sistema'
    ),
    (
        'Gerente de Vendas',
        'sales_manager',
        'Gestão de produtos e relatórios de vendas'
    ),
    (
        'Vendedor',
        'salesperson',
        'Realiza vendas e consulta estoque'
    );

INSERT INTO
    permissions (name, slug, description)
VALUES (
        'Criar Produtos',
        'products.create',
        'Permite cadastrar novos produtos'
    ),
    (
        'Editar Produtos',
        'products.edit',
        'Permite alterar dados de produtos'
    ),
    (
        'Excluir Produtos',
        'products.delete',
        'Permite remover produtos'
    ),
    (
        'Visualizar Relatórios',
        'reports.view',
        'Acesso a dashboards gerenciais'
    ),
    (
        'Realizar Venda',
        'sales.create',
        'Permite fechar pedidos'
    );

INSERT INTO
    role_permissions (role_id, permission_id)
SELECT 1, id
FROM permissions;

INSERT INTO
    role_permissions (role_id, permission_id)
VALUES (2, 1),
    (2, 2),
    (2, 4),
    (3, 5);

INSERT INTO
    categories (name, description)
VALUES (
        'Ferramentas Gerais',
        'Ferramentas diversas'
    );

INSERT INTO
    products (
        name,
        description,
        price_cash,
        price_installments,
        category_id,
        stock_quantity,
        image_data,
        image_mime
    )
VALUES (
        'Serra de Bancada Stanley',
        'Potência de 1800W e alta precisão.',
        2499.00,
        2999.90,
        1,
        10,
        NULL,
        NULL
    ),
    (
        'Parafusadeira DeWalt 20V',
        'Motor Brushless e bateria longa duração.',
        1499.00,
        1750.00,
        1,
        25,
        NULL,
        NULL
    ),
    (
        'Martelete Combinado Makita',
        'Performance industrial.',
        899.90,
        1050.00,
        1,
        15,
        NULL,
        NULL
    ),
    (
        'Arame Farpado',
        'Farpa bem',
        199.90,
        239.90,
        1,
        85,
        NULL,
        NULL
    ),
    (
        'Chave Sextavada',
        'Chave de sextavar',
        199.90,
        239.90,
        1,
        85,
        NULL,
        NULL
    ),
    (
        'Chave Inglesa',
        'Bebe chá as 6',
        199.90,
        239.90,
        1,
        85,
        NULL,
        NULL
    ),
    (
        'Serra Marmore Makita',
        'Os cara estão na maldade',
        1990.90,
        2390.90,
        1,
        85,
        NULL,
        NULL
    ),
    (
        'Alicate Universal',
        'Eu sou Alexandra Mendes, Eu sou a Universal',
        99.90,
        39.90,
        1,
        85,
        NULL,
        NULL
    ),
    (
        'Alicate DePressão',
        'Corta bem',
        199.90,
        239.90,
        1,
        85,
        NULL,
        NULL
    ),
    (
        'Kit Chave Allien',
        'Ta na hora de virar heroi',
        199.90,
        239.90,
        1,
        85,
        NULL,
        NULL
    );

INSERT INTO
    promotions (
        name,
        discount_percentage,
        start_date,
        end_date
    )
VALUES (
        'Oferta de Lançamento',
        15.00,
        NOW(),
        DATE_ADD(NOW(), INTERVAL 7 DAY)
    );

INSERT INTO
    product_promotions (product_id, promotion_id)
VALUES (1, 1),
    (2, 1),
    (4, 1);
