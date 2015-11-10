-- Geração de Modelo físico
-- Sql ANSI 2003 - brModelo.

CREATE TABLE Usuario (
id_usuario int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
nome varchar(50) NOT NULL,
email varchar(50) NOT NULL UNIQUE,
login varchar(25) NOT NULL UNIQUE,
senha varchar(150) NOT NULL,
tipo_usuario tinyint(1) NOT NULL DEFAULT 0 /* 0 = comum, 1 = administrador */
) ENGINE=InnoDB;

INSERT INTO Usuario(id_usuario, nome, email, login, senha, tipo_usuario) VALUES (1, 'Master', 'joaoricardo.rm@gmail.com', 'master', '!!!$2y$10$3rAsBTkPIKtqykvClUrTyuWIVn1cdLYs6O7mv7tET5NbIHo1rdHtK', 1);
INSERT INTO Usuario(id_usuario, nome, email, login, senha, tipo_usuario) VALUES (2, 'Sistema', 'sistema@sistema.com', 'admin', '!!!$2y$10$K1yw.B8BPTFZZvQiM0gUBuX1mSHYmCoU1ztUtEt9v0Jf95YbT7AVy', 1); /* USUÁRIO PADRÃO: ADMIN 123 */

CREATE TABLE Certificado (
id_certificado int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
data_emissao timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
livro int(11) NOT NULL,
folha int(11) NOT NULL,
codigo int(11) NOT NULL,
id_usuario int(11) NOT NULL
) ENGINE=InnoDB;

INSERT INTO Certificado(id_certificado, livro, folha, codigo, id_usuario) VALUES (1, 0, 0, 0, 1);

CREATE TABLE Palestra (
id_palestra int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
nome varchar(70) NULL,
data date NULL,
carga_horaria time NULL,
proprio_evento tinyint(1) NOT NULL DEFAULT 1, /* 0 = não, 1 = sim */
id_evento int(11) NOT NULL,
id_modelo_certificado int(11) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Evento (
id_evento int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
nome varchar(70) NOT NULL,
local varchar(60),
data date,
duracao varchar(20)
) ENGINE=InnoDB;

CREATE TABLE Palestrante (
id_palestrante int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
nome varchar(50) NOT NULL,
email varchar(50),
cpf varchar(15) NOT NULL UNIQUE,
cargo varchar(50),
imagem_assinatura varchar(50)
) ENGINE=InnoDB;

CREATE TABLE Configuracao (
id_configuracao int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
nome_instituicao varchar(60) NOT NULL,
imagem_logo varchar(50),
cnpj varchar(20),
telefone varchar(15) NOT NULL
) ENGINE=InnoDB;

INSERT INTO Configuracao(id_configuracao, nome_instituicao, imagem_logo, cnpj, telefone) VALUES (1, 'NOME DA INSTITUIÇÃO', '', '', 'TELEFONE');

CREATE TABLE Participante (
id_participante int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
nome varchar(50) NOT NULL,
email varchar(50),
cpf varchar(15) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE Modelo_Certificado (
id_modelo_certificado int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
nome varchar(70) NOT NULL,
texto_participante text,
texto_palestrante text,
arquivo_css varchar(15) NOT NULL DEFAULT 'padrao',
elementos text NOT NULL
) ENGINE=InnoDB;

INSERT INTO Modelo_Certificado(id_modelo_certificado, nome, texto_participante, texto_palestrante) VALUES (1, 'Padrão', 
'Certificamos que %nome% participou da %atividade%, realizada no %local%, em %data%, com duração de %duracao% e carga horária de %carga_horaria%', 
'Certificamos que %nome% ministrou a %atividade%, realizada no %local%, em %data%, com duração de %duracao% e carga horária de %carga_horaria%');

CREATE TABLE Palestra_Palestrante (
id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
id_palestrante int(11) NOT NULL,
id_palestra int(11) NOT NULL,
id_certificado int(11) NOT NULL DEFAULT 1, /* 0 = certificado padrao */
FOREIGN KEY(id_palestrante) REFERENCES Palestrante (id_palestrante),
FOREIGN KEY(id_palestra) REFERENCES Palestra (id_palestra),
FOREIGN KEY(id_certificado) REFERENCES Certificado (id_certificado)
) ENGINE=InnoDB;

ALTER TABLE Palestra_Palestrante ADD UNIQUE unique_index (id_palestrante, id_palestra);

CREATE TABLE Palestra_Participante (
id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
presenca tinyint(1) NOT NULL DEFAULT 0, /* 0 = não, 1 = sim */
id_participante int(11) NOT NULL,
id_palestra int(11) NOT NULL,
id_certificado int(11) NOT NULL DEFAULT 1, /* 0 = certificado padrao */
FOREIGN KEY(id_palestra) REFERENCES Palestra (id_palestra),
FOREIGN KEY(id_participante) REFERENCES Participante (id_participante),
FOREIGN KEY(id_certificado) REFERENCES Certificado (id_certificado)
) ENGINE=InnoDB;

ALTER TABLE Palestra_Participante ADD UNIQUE unique_index (id_participante, id_palestra);

ALTER TABLE Certificado ADD FOREIGN KEY(id_usuario) REFERENCES Usuario (id_usuario);
ALTER TABLE Palestra ADD FOREIGN KEY(id_evento) REFERENCES Evento (id_evento);
ALTER TABLE Palestra ADD FOREIGN KEY(id_modelo_certificado) REFERENCES Modelo_Certificado (id_modelo_certificado);
