-- Criação do banco de dados
CREATE DATABASE loja;

-- Uso do banco de dados
USE loja;

-- Criação da tabela de usuarios
CREATE TABLE usuarios (
id INT AUTO_INCREMENT PRIMARY KEY,
usuario VARCHAR(50) NOT NULL,
email VARCHAR(100) NOT NULL UNIQUE,
senha VARCHAR(255) NOT NULL,
data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Criação da tabela de produtos
CREATE TABLE produtos (
id INT AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(100) NOT NULL,
preco DECIMAL(10, 2) NOT NULL,
quantidade INT (10) NOT NULL,
imagem VARCHAR(255)
);