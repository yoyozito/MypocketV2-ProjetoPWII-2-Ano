CREATE DATABASE mypocket;

USE mypocket;


CREATE TABLE usuario (

    id_usuario INT AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(100),

    email VARCHAR(150) UNIQUE,

    senha VARCHAR(255)

);


CREATE TABLE carteira (

    id_carteira INT AUTO_INCREMENT PRIMARY KEY,

    id_usuario INT,

    saldo FLOAT DEFAULT 0,

    FOREIGN KEY (id_usuario)
    REFERENCES usuario(id_usuario)

);


CREATE TABLE categoria (

    id_categoria INT AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(100),

    tipo VARCHAR(20)

);


CREATE TABLE transacao (

    id_transacao INT AUTO_INCREMENT PRIMARY KEY,

    id_carteira INT,

    id_categoria INT,

    data DATE,

    valor FLOAT,

    descricao VARCHAR(150),

    FOREIGN KEY (id_carteira)
    REFERENCES carteira(id_carteira),

    FOREIGN KEY (id_categoria)
    REFERENCES categoria(id_categoria)

);


CREATE TABLE resumo_mensal (

    id_resumo INT AUTO_INCREMENT PRIMARY KEY,

    id_carteira INT,

    mes INT,

    ano INT,

    total_entradas FLOAT DEFAULT 0,

    total_saidas FLOAT DEFAULT 0,

    saldo_final FLOAT DEFAULT 0,

    desempenho FLOAT DEFAULT 0,

    FOREIGN KEY (id_carteira)
    REFERENCES carteira(id_carteira)

);