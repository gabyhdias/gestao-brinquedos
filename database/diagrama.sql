CREATE DATABASE IF NOT EXISTS `loja_brinquedos` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `loja_brinquedos`;

CREATE TABLE IF NOT EXISTS `brinquedos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(100) NOT NULL,
    `categoria` VARCHAR(50) NOT NULL,
    `faixa_etaria` VARCHAR(30) NOT NULL,
    `preco` DECIMAL(10, 2) NOT NULL,
    `quantidade` INT NOT NULL DEFAULT 0,
    `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;]

INSERT INTO `brinquedos` (`nome`, `categoria`, `faixa_etaria`, `preco`, `quantidade`) VALUES
('Quebra-Cabeça 100 Peças', 'Educativo', '6 a 10 anos', 45.90, 15),
('Carrinho de Controle Remoto', 'Veículos', '8+ anos', 129.99, 8),
('Urso de Pelúcia Gigante', 'Pelúcias', '0 a 3 anos', 89.90, 20);