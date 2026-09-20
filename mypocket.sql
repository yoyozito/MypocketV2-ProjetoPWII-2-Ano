CREATE DATABASE IF NOT EXISTS mypocket
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE mypocket;

CREATE TABLE IF NOT EXISTS transacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('receita', 'despesa') NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    data DATE NOT NULL,

    INDEX idx_tipo_data (tipo, data),
    INDEX idx_data (data)
);