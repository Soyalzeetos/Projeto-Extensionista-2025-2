-- 1. Criação da tabela de Departamentos
CREATE TABLE departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- 2. Criação da tabela de Categorias (Ligada aos Departamentos)
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    department_id INT NOT NULL,
    FOREIGN KEY (department_id) REFERENCES departments(id)
);

-- 3. Criação da tabela de Produtos (Ligada às Categorias)
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_path VARCHAR(255),
    is_promo BOOLEAN DEFAULT FALSE,
    category_id INT NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- 4. Inserção de Departamentos
INSERT INTO departments (id, name) VALUES
(1, 'Ferramentas'),
(2, 'Equipamentos Profissionais'),
(3, 'Construção Civil');

-- 5. Inserção de Categorias
-- Ferramentas (Dept 1)
INSERT INTO categories (id, name, department_id) VALUES
(1, 'Elétricas', 1),
(2, 'A Bateria', 1),
(3, 'Manuais', 1),
(4, 'Kits e Jogos', 1),
(5, 'Precisão', 1),
(6, 'Acessórios', 1);

-- Equipamentos (Dept 2)
INSERT INTO categories (id, name, department_id) VALUES
(7, 'Solda', 2),
(8, 'Compressores', 2);

-- Construção (Dept 3)
INSERT INTO categories (id, name, department_id) VALUES
(9, 'Materiais Básicos', 3);

-- 6. Inserção de Produtos (Usando os IDs das categorias acima)
INSERT INTO products (name, description, price, image_path, is_promo, category_id) VALUES
-- Destaques
('Serra de Bancada Stanley', 'Potência de 1800W e alta precisão para marcenaria profissional.', 2499.00, 'assets/img/produtos/serra_circular_de_bancada_10_1800w_stanley_254mm_com_guia_mesa_sst1801_128453_1_4b7129a03dc8f100656f6201a5a7d383.webp', TRUE, 1),
('Parafusadeira DeWalt 20V', 'Motor Brushless, alto torque e bateria de longa duração.', 1499.00, 'assets/img/produtos/zoom-1712-A7019.webp', TRUE, 2),
('Martelete Combinado Makita', 'Performance industrial para perfuração e demolição em concreto.', 899.90, 'assets/img/produtos/martelete-makita.webp', TRUE, 1),
('Jogo Mecânico Tramontina PRO', 'Maleta completa com 176 peças de alta resistência para profissionais.', 1890.00, 'assets/img/produtos/ferramentas-manuais-outros-kit-ferramentas-para-mecanico-176-pcs-44952176-tramontinapro-1717183025549.webp', TRUE, 4),
('Inversora de Solda Digital', 'Portátil, bivolt e com display digital para soldas precisas.', 499.90, 'assets/img/produtos/maquina-de-solda.webp', TRUE, 7),

-- Grade Geral
('Serra Mármore Makita', 'Alta potência para cortes em alvenaria.', 359.90, 'assets/img/produtos/serra-marmore-makita.webp', FALSE, 1),
('Compressor Chiaperini', 'Motor 3HP, 150L, ideal para uso profissional e industrial.', 4599.90, 'assets/img/produtos/compressor.webp', FALSE, 8),
('Serra Tico-Tico DeWalt', 'Velocidade variável para cortes precisos.', 649.90, 'assets/img/produtos/serra-tico-tico.webp', FALSE, 1),
('Esmerilhadeira Angular', 'Ideal para desbaste e corte de metais.', 289.90, 'assets/img/produtos/esmerilhadeira.webp', FALSE, 1),
('Alicate de Pressão', 'Mordente curvo para fixação segura.', 45.90, 'assets/img/produtos/alicate-de-pressao.webp', FALSE, 3),
('Chave Inglesa Ajustável', 'Regulagem fácil e material resistente.', 39.90, 'assets/img/produtos/chave-inglesa.webp', FALSE, 3),
('Arame Farpado 250m', 'Galvanizado, proteção máxima.', 119.90, 'assets/img/produtos/arame-farpado.webp', FALSE, 9),
('Jogo de Chaves', '5 Peças, Aço Cromo Vanádio.', 29.99, 'assets/img/produtos/kit-chave-de-fenda.webp', FALSE, 3),
('Furadeira de Impacto Makita', 'Alta performance em concreto e madeira.', 429.90, 'assets/img/produtos/furadeira-de-impacto-makita.webp', FALSE, 1),
('Lixadeira Orbital Makita', 'Acabamento fino com baixa vibração.', 389.90, 'assets/img/produtos/lixadeira-orbital-makita.webp', FALSE, 1),
('Jogo Serra Copo', 'Cortes circulares precisos em madeira.', 59.90, 'assets/img/produtos/kit-serra-copo.webp', FALSE, 6),
('Jogo Chave Allen', 'Aço cromo vanádio, alta durabilidade.', 24.90, 'assets/img/produtos/kit-chave-allen.webp', FALSE, 3),
('Jogo Chave Estrela', 'Kit completo de chaves de precisão.', 89.90, 'assets/img/produtos/chave-sextavada.webp', FALSE, 3),
('Jogo Chave Canhão', 'Ideal para porcas e parafusos sextavados.', 49.90, 'assets/img/produtos/kit-chave-pito-sextavada.webp', FALSE, 3),
('Furadeira Impacto DeWalt', 'Alta potência 1300W, mandril 5/8".', 1099.90, 'assets/img/produtos/Furadeira-de-impacto-5-8pol-1300w-d21570k-Dewalt-1_2.webp', FALSE, 1),
('Kit Ferramentas 129 Peças', 'Maleta completa para uso doméstico.', 139.90, 'assets/img/produtos/jogo_ferramentas_com_maleta_129_pecas_sparta_10651_1_a37490a0b81aed20396b5af76d594019.webp', FALSE, 4),
('Furadeira Black & Decker', 'Compacta e potente para o dia a dia.', 199.90, 'assets/img/produtos/furadeira-de-impacto-black-e-decker.webp', FALSE, 1),
('Kit Precisão 24 Bits', 'Para eletrônica e manutenção fina.', 69.90, 'assets/img/produtos/kit_ferramentas_precisao_24_bits_profissional_jakemy_jm_8168_2203_13_20191102121110.webp', FALSE, 5),
('Compressor Schulz Prátiko', '2HP, 50 Litros, ideal para serviços diversos.', 1899.00, 'assets/img/produtos/364913_compressor_de_ar_8_6_pes_50l_2_hp_120_libras_monofasico_pratiko_csi.webp', FALSE, 8),
('Furadeira Impacto Bosch', 'Modelo GSB Profissional, robustez total.', 549.90, 'assets/img/produtos/furadeira-de-impacto-bosch.webp', FALSE, 1);
