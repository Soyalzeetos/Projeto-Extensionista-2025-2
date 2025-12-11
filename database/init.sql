SET NAMES 'utf8mb4';

CREATE DATABASE IF NOT EXISTS center_ferramentas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE center_ferramentas;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    password_hash VARCHAR(255),
    verification_token VARCHAR(255) NULL,
    email_verified_at TIMESTAMP NULL,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS password_resets (
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
    active BOOLEAN,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    zip_code VARCHAR(20) NOT NULL,
    street VARCHAR(255) NOT NULL,
    number VARCHAR(20) NOT NULL,
    complement VARCHAR(100),
    neighborhood VARCHAR(100),
    city VARCHAR(100) NOT NULL,
    state CHAR(2) NOT NULL,
    type ENUM('billing', 'shipping') DEFAULT 'shipping',
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
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
    active BOOLEAN DEFAULT TRUE,
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

CREATE TABLE IF NOT EXISTS carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_token VARCHAR(255) NOT NULL,
    user_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cart_id) REFERENCES carts (id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    shipping_address_id INT,
    total_amount DECIMAL(10, 2) NOT NULL,
    discount_amount DECIMAL(10, 2) DEFAULT 0.00,
    shipping_cost DECIMAL(10, 2) DEFAULT 0.00,
    status ENUM(
        'pending',
        'awaiting_payment',
        'paid',
        'processing',
        'shipped',
        'delivered',
        'cancelled',
        'refunded'
    ) DEFAULT 'pending',
    payment_method VARCHAR(50),
    tracking_code VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE RESTRICT,
    FOREIGN KEY (shipping_address_id) REFERENCES addresses (id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,
    quantity INT NOT NULL CHECK (quantity > 0),
    unit_price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    transaction_id VARCHAR(255),
    provider VARCHAR(50),
    amount DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) NOT NULL,
    paid_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE
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
        'Serra de Bancada Stanley 1800W',
        'Motor de 1800W com capacidade de corte de 10 polegadas. Guia de corte auto-alinhável.',
        2100.00,
        2699.90,
        1,
        5,
        NULL,
        NULL
    ),
    (
        'Parafusadeira/Furadeira DeWalt 20V Max',
        'Motor Brushless, mandril de ajuste rápido e 2 baterias de lítio inclusas.',
        1350.00,
        1799.00,
        1,
        12,
        NULL,
        NULL
    ),
    (
        'Martelete Combinado Makita HR2470',
        '3 modos de operação: simples impacto, rotação com impacto e rotação simples.',
        780.00,
        1050.00,
        1,
        8,
        NULL,
        NULL
    ),
    (
        'Arame Farpado Motto 500m',
        'Galvanização pesada, alta resistência à corrosão e ruptura. Rolo de 500 metros.',
        420.00,
        589.90,
        1,
        40,
        NULL,
        NULL
    ),
    (
        'Jogo de Chaves Sextavadas (Allen)',
        'Kit com 9 peças (1.5mm a 10mm), aço Cromo-Vanádio com acabamento oxidado preto.',
        45.00,
        89.90,
        1,
        55,
        NULL,
        NULL
    ),
    (
        'Chave Inglesa Ajustável 12 Pol.',
        'Abertura regulável, aço forjado e escala métrica gravada a laser.',
        75.00,
        119.90,
        1,
        20,
        NULL,
        NULL
    ),
    (
        'Serra Mármore Makita 1300W',
        'Ideal para cortes em pisos, cerâmicas, pedras e concreto. Acompanha kit refrigeração.',
        380.00,
        549.90,
        1,
        15,
        NULL,
        NULL
    ),
    (
        'Alicate Universal 8 Pol. Isolado',
        'Cabo com isolamento 1000V, arestas de corte temperadas por indução.',
        42.00,
        79.90,
        1,
        60,
        NULL,
        NULL
    ),
    (
        'Alicate de Pressão 10 Pol.',
        'Mordentes curvos, ideal para fixação de tubos e conexões hidráulicas com travamento seguro.',
        55.00,
        95.90,
        1,
        35,
        NULL,
        NULL
    ),
    (
        'Jogo Chaves Torx (Tipo Canivete)',
        'Conjunto portátil com 8 medidas (T9 a T40), corpo emborrachado para melhor pega.',
        35.00,
        69.90,
        1,
        45,
        NULL,
        NULL
    ),
    (
        'Kit Chaves de Fenda e Phillips',
        'Haste em aço Cromo-Vanádio, ponta imantada e cabo ergonômico. Contém 6 peças.',
        85.00,
        149.90,
        1,
        30,
        NULL,
        NULL
    ),
    (
        'Serra Tico-Tico Bosch 500W',
        'Velocidade variável, sistema de troca rápida de lâmina e base inclinável.',
        320.00,
        489.90,
        1,
        10,
        NULL,
        NULL
    ),
    (
        'Serra Circular Manual 7 1/4"',
        'Potência de 1400W, disco de 24 dentes incluso. Ideal para madeiras de construção.',
        450.00,
        689.90,
        1,
        7,
        NULL,
        NULL
    ),
    (
        'Kit Ferramentas de Precisão 32 Peças',
        'Estojo com bits magnéticos diversos para manutenção de eletrônicos e celulares.',
        25.00,
        59.90,
        1,
        100,
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
    (4, 1),
    (7, 1);
