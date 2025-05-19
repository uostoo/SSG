
-- Cria o banco de dados
CREATE DATABASE ssg;

-- Seleciona o banco de dados
USE ssg;

-- Tabela cliente (superclasse)
CREATE TABLE cliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('cliente', 'prestador') NOT NULL -- Para diferenciar os tipos
);

-- Tabela prestador (subclasse de cliente)
CREATE TABLE prestador (
    id INT PRIMARY KEY, -- Referencia o ID do cliente
    FOREIGN KEY (id) REFERENCES cliente(id) ON DELETE CASCADE
);

CREATE TABLE servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_do_servico VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    valorhora DECIMAL(10, 2) NOT NULL, -- Valor por hora em formato monetário
    telefone VARCHAR(15) NOT NULL,
    id_prestador INT NOT NULL, -- FK para a tabela prestador
    FOREIGN KEY (id_prestador) REFERENCES prestador(id) ON DELETE CASCADE
);

CREATE TABLE servicos_prestados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_servico INT NOT NULL, -- FK para a tabela serviços
    id_cliente INT NOT NULL, -- FK para a tabela cliente
    avaliacao TINYINT CHECK (avaliacao BETWEEN 0 AND 5), -- Avaliação de 0 a 5
    tempo INT NOT NULL, -- Tempo em minutos (escolhido no agendamento)
    FOREIGN KEY (id_servico) REFERENCES servicos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id) ON DELETE CASCADE
);

CREATE TABLE agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_servico INT NOT NULL, -- FK para a tabela serviços
    id_cliente INT NOT NULL, -- FK para a tabela cliente
    id_prestador INT NOT NULL, -- FK para a tabela prestador
    tempo DATETIME NOT NULL, -- Data e hora do agendamento
    status ENUM('Pendente', 'Aceito', 'Negado') DEFAULT 'Pendente',
    FOREIGN KEY (id_servico) REFERENCES servicos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_cliente) REFERENCES cliente(id) ON DELETE CASCADE,
    FOREIGN KEY (id_prestador) REFERENCES prestador(id) ON DELETE CASCADE
);

-- Índices
CREATE INDEX idx_email_cliente ON cliente(email);
CREATE INDEX idx_id_servico_prestados ON servicos_prestados(id_servico);
CREATE INDEX idx_prestador ON prestador(id);
