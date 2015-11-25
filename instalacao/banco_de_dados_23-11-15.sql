-- Geração de Modelo físico
-- Sql ANSI 2003 - brModelo.

SET SESSION sql_mode='NO_AUTO_VALUE_ON_ZERO'; /* Para aceitar id = 0 */

CREATE TABLE usuario (
id_usuario int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
nome varchar(50) NOT NULL,
email varchar(50) NOT NULL UNIQUE,
login varchar(25) NOT NULL UNIQUE,
senha varchar(150) NOT NULL,
tipo_usuario tinyint(1) NOT NULL DEFAULT 0 /* 0 = comum, 1 = administrador */
) ENGINE=InnoDB;

INSERT INTO usuario(id_usuario, nome, email, login, senha, tipo_usuario) VALUES (1, 'Master', 'joaoricardo.rm@gmail.com', 'master', '!!!$2y$10$3rAsBTkPIKtqykvClUrTyuWIVn1cdLYs6O7mv7tET5NbIHo1rdHtK', 1);
INSERT INTO usuario(id_usuario, nome, email, login, senha, tipo_usuario) VALUES (2, 'Sistema', 'sistema@sistema.com', 'admin', '!!!$2y$10$K1yw.B8BPTFZZvQiM0gUBuX1mSHYmCoU1ztUtEt9v0Jf95YbT7AVy', 1); /* USUÁRIO PADRÃO: ADMIN 123 */

CREATE TABLE certificado (
id_certificado int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
data_emissao timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
livro int(11) NOT NULL,
folha int(11) NOT NULL,
codigo int(11) NOT NULL,
id_usuario int(11) NOT NULL
) ENGINE=InnoDB;

ALTER TABLE certificado ADD UNIQUE (livro, codigo);

INSERT INTO certificado(id_certificado, livro, folha, codigo, id_usuario) VALUES (0, 0, 0, 0, 1); /* tem que aceitar id 0 */

CREATE TABLE palestra (
id_palestra int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
nome varchar(70) NULL,
data date NULL,
carga_horaria time NULL,
proprio_evento tinyint(1) NOT NULL DEFAULT 1, /* 0 = não, 1 = sim */
id_evento int(11) NOT NULL,
id_modelo_certificado int(11) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE evento (
id_evento int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
nome varchar(70) NOT NULL,
local varchar(60),
data date,
duracao varchar(20)
) ENGINE=InnoDB;

CREATE TABLE palestrante (
id_palestrante int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
nome varchar(50) NOT NULL,
email varchar(50),
cpf varchar(15) NOT NULL UNIQUE,
cargo varchar(50),
imagem_assinatura varchar(50)
) ENGINE=InnoDB;

CREATE TABLE configuracao (
id_configuracao int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
nome_instituicao varchar(60) NOT NULL,
imagem_logo varchar(50),
cnpj varchar(20),
telefone varchar(15) NOT NULL
) ENGINE=InnoDB;

INSERT INTO configuracao(id_configuracao, nome_instituicao, imagem_logo, cnpj, telefone) VALUES (1, 'NOME DA INSTITUIÇÃO', '', '', 'TELEFONE');

CREATE TABLE participante (
id_participante int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
nome varchar(50) NOT NULL,
email varchar(50),
cpf varchar(15) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE modelo_certificado (
id_modelo_certificado int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
nome varchar(70) NOT NULL,
texto_participante text,
texto_palestrante text,
arquivo_css varchar(15) NOT NULL DEFAULT 'padrao',
elementos text NOT NULL
) ENGINE=InnoDB;

INSERT INTO modelo_certificado(id_modelo_certificado, nome, texto_participante, texto_palestrante) VALUES (1, 'Padrão', 
'Certificamos que %nome% participou da %atividade%, realizada no %local%, em %data%, com duração de %duracao% e carga horária de %carga_horaria%', 
'Certificamos que %nome% ministrou a %atividade%, realizada no %local%, em %data%, com duração de %duracao% e carga horária de %carga_horaria%');

CREATE TABLE palestra_palestrante (
id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
id_palestrante int(11) NOT NULL,
id_palestra int(11) NOT NULL,
id_certificado int(11) NOT NULL DEFAULT 0, /* 0 = certificado padrao */
FOREIGN KEY(id_palestrante) REFERENCES palestrante (id_palestrante),
FOREIGN KEY(id_palestra) REFERENCES palestra (id_palestra),
FOREIGN KEY(id_certificado) REFERENCES certificado (id_certificado)
) ENGINE=InnoDB;

ALTER TABLE palestra_palestrante ADD UNIQUE unique_index (id_palestrante, id_palestra);

CREATE TABLE palestra_participante (
id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
presenca tinyint(1) NOT NULL DEFAULT 0, /* 0 = não, 1 = sim */
id_participante int(11) NOT NULL,
id_palestra int(11) NOT NULL,
id_certificado int(11) NOT NULL DEFAULT 0, /* 0 = certificado padrao */
FOREIGN KEY(id_palestra) REFERENCES palestra (id_palestra),
FOREIGN KEY(id_participante) REFERENCES participante (id_participante),
FOREIGN KEY(id_certificado) REFERENCES certificado (id_certificado)
) ENGINE=InnoDB;

ALTER TABLE palestra_participante ADD UNIQUE unique_index (id_participante, id_palestra);

ALTER TABLE certificado ADD FOREIGN KEY(id_usuario) REFERENCES usuario (id_usuario);
ALTER TABLE palestra ADD FOREIGN KEY(id_evento) REFERENCES evento (id_evento);
ALTER TABLE palestra ADD FOREIGN KEY(id_modelo_certificado) REFERENCES modelo_certificado (id_modelo_certificado);
