SET NAMES 'utf8mb4';

ALTER DATABASE center_ferramentas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    senha_hash character varying(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    imagem_url VARCHAR(255) NOT NULL,
    destaque BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO produtos (nome, descricao, preco, imagem_url, destaque) VALUES
('Serra de Bancada Stanley', 'Potência de 1800W e alta precisão para marcenaria profissional.', 2499.00, 'assets/img/produtos/serra_circular_de_bancada_10_1800w_stanley_254mm_com_guia_mesa_sst1801_128453_1_4b7129a03dc8f100656f6201a5a7d383.webp', 1),
('Parafusadeira DeWalt 20V', 'Motor Brushless, alto torque e bateria de longa duração.', 1499.00, 'assets/img/produtos/zoom-1712-A7019.webp', 1),
('Martelete Combinado Makita', 'Performance industrial para perfuração e demolição em concreto.', 899.90, 'assets/img/produtos/martelete-makita.webp', 1),
('Jogo Mecânico Tramontina PRO', 'Maleta completa com 176 peças de alta resistência para profissionais.', 1890.00, 'assets/img/produtos/ferramentas-manuais-outros-kit-ferramentas-para-mecanico-176-pcs-44952176-tramontinapro-1717183025549.webp', 1),
('Inversora de Solda Digital', 'Portátil, bivolt e com display digital para soldas precisas.', 499.90, 'assets/img/produtos/maquina-de-solda.webp', 1),
('Serra Mármore Makita', 'Alta potência para cortes em alvenaria.', 359.90, 'assets/img/produtos/serra-marmore-makita.webp', 0),
('Compressor Chiaperini', 'Motor 3HP, 150L, ideal para uso profissional e industrial.', 4599.90, 'assets/img/produtos/compressor.webp', 0),
('Inversora de Solda', 'Display digital e design compacto.', 499.90, 'assets/img/produtos/maquina-de-solda.webp', 0),
('Serra Tico-Tico DeWalt', 'Velocidade variável para cortes precisos.', 649.90, 'assets/img/produtos/serra-tico-tico.webp', 0),
('Esmerilhadeira Angular', 'Ideal para desbaste e corte de metais.', 289.90, 'assets/img/produtos/esmerilhadeira.webp', 0),
('Alicate de Pressão', 'Mordente curvo para fixação segura.', 45.90, 'assets/img/produtos/alicate-de-pressao.webp', 0),
('Chave Inglesa Ajustável', 'Regulagem fácil e material resistente.', 39.90, 'assets/img/produtos/chave-inglesa.webp', 0),
('Arame Farpado 250m', 'Galvanizado, proteção máxima.', 119.90, 'assets/img/produtos/arame-farpado.webp', 0),
('Jogo de Chaves', '5 Peças, Aço Cromo Vanádio.', 29.99, 'assets/img/produtos/kit-chave-de-fenda.webp', 0),
('Furadeira de Impacto Makita', 'Alta performance em concreto e madeira.', 429.90, 'assets/img/produtos/furadeira-de-impacto-makita.webp', 0),
('Lixadeira Orbital Makita', 'Acabamento fino com baixa vibração.', 389.90, 'assets/img/produtos/lixadeira-orbital-makita.webp', 0),
('Jogo Serra Copo', 'Cortes circulares precisos em madeira.', 59.90, 'assets/img/produtos/kit-serra-copo.webp', 0),
('Jogo Chave Allen', 'Aço cromo vanádio, alta durabilidade.', 24.90, 'assets/img/produtos/kit-chave-allen.webp', 0),
('Jogo Chave Estrela', 'Kit completo de chaves de precisão.', 89.90, 'assets/img/produtos/chave-sextavada.webp', 0),
('Jogo Chave Canhão', 'Ideal para porcas e parafusos sextavados.', 49.90, 'assets/img/produtos/kit-chave-pito-sextavada.webp', 0),
('Furadeira Impacto DeWalt', 'Alta potência 1300W, mandril 5/8".', 1099.90, 'assets/img/produtos/Furadeira-de-impacto-5-8pol-1300w-d21570k-Dewalt-1_2.webp', 0),
('Kit Ferramentas 129 Peças', 'Maleta completa para uso doméstico.', 139.90, 'assets/img/produtos/jogo_ferramentas_com_maleta_129_pecas_sparta_10651_1_a37490a0b81aed20396b5af76d594019.webp', 0),
('Furadeira Black & Decker', 'Compacta e potente para o dia a dia.', 199.90, 'assets/img/produtos/furadeira-de-impacto-black-e-decker.webp', 0),
('Kit Precisão 24 Bits', 'Para eletrônica e manutenção fina.', 69.90, 'assets/img/produtos/kit_ferramentas_precisao_24_bits_profissional_jakemy_jm_8168_2203_13_20191102121110.webp', 0),
('Compressor Schulz Prátiko', '2HP, 50 Litros, ideal para serviços diversos.', 1899.00, 'assets/img/produtos/364913_compressor_de_ar_8_6_pes_50l_2_hp_120_libras_monofasico_pratiko_csi.webp', 0),
('Furadeira Impacto Bosch', 'Modelo GSB Profissional, robustez total.', 549.90, 'assets/img/produtos/furadeira-de-impacto-bosch.webp', 0);
