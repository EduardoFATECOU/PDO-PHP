-- =====================================================
-- Script de criação do banco de dados para sistema CRUD
-- Banco de dados: crud_pdo
-- Tabela: usuarios
-- =====================================================

-- Cria o banco de dados se não existir
CREATE DATABASE IF NOT EXISTS crud_pdo
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

-- Seleciona o banco de dados para uso
USE crud_pdo;

-- Remove a tabela se já existir (útil para recriar)
DROP TABLE IF EXISTS usuarios;

-- Cria a tabela de usuários com os campos necessários
CREATE TABLE usuarios (
    -- ID auto-incrementável como chave primária
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Nome completo do usuário (obrigatório)
    nome VARCHAR(100) NOT NULL,
    
    -- Endereço de email (obrigatório e único)
    email VARCHAR(100) NOT NULL UNIQUE,
    
    -- Número de telefone (opcional)
    telefone VARCHAR(20),
    
    -- Data de nascimento (opcional)
    data_nascimento DATE,
    
    -- Data e hora de criação do registro (preenchido automaticamente)
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Data e hora da última atualização (atualizado automaticamente)
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insere alguns dados de exemplo para teste
INSERT INTO usuarios (nome, email, telefone, data_nascimento) VALUES
('João Silva', 'joao.silva@email.com', '(11) 98765-4321', '1990-05-15'),
('Maria Santos', 'maria.santos@email.com', '(21) 99876-5432', '1985-08-22'),
('Pedro Oliveira', 'pedro.oliveira@email.com', '(31) 97654-3210', '1992-11-30');

-- Exibe os dados inseridos
SELECT * FROM usuarios;
