-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS conectiva;
USE conectiva;

-- Tabela de escritórios
CREATE TABLE IF NOT EXISTS escritorios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  localidade VARCHAR(255) NOT NULL,
  territorio VARCHAR(100) NOT NULL,
  cidade VARCHAR(100) NOT NULL,
  endereco VARCHAR(255) NOT NULL,
  latitude DECIMAL(10, 8) NOT NULL,
  longitude DECIMAL(11, 8) NOT NULL,
  data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de pontos de internet
CREATE TABLE IF NOT EXISTS conectiva (
  id INT AUTO_INCREMENT PRIMARY KEY,
  escritorio_id INT NOT NULL,
  ip VARCHAR(15) NOT NULL,
  circuito VARCHAR(100),
  velocidade VARCHAR(50) NOT NULL,
  tipo VARCHAR(50),
  marcador VARCHAR(100),
  data_instalacao DATE NOT NULL,
  observacao TEXT,
  data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (escritorio_id) REFERENCES escritorios(id) ON DELETE CASCADE
);

-- Criar índices para melhorar performance
CREATE INDEX idx_cidade ON escritorios(cidade);
CREATE INDEX idx_territorio ON escritorios(territorio);
CREATE INDEX idx_escritorio_id ON conectiva(escritorio_id);
CREATE INDEX idx_ip ON conectiva(ip);
CREATE INDEX idx_velocidade ON conectiva(velocidade);