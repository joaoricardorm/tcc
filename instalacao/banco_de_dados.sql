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


INSERT INTO modelo_certificado(id_modelo_certificado, nome, texto_participante, texto_palestrante, arquivo_css, elementos)  VALUES
(1, 

'Padrão',

'["Certificamos que",{ "label": "Nome do Participante", "class": "tagit-choice dbitem nomeParticipante" },"participou da",{ "label": "Nome da Atividade", "class": "tagit-choice dbitem nomeAtividade" },", realizada no",{ "label": "Local da Atividade", "class": "tagit-choice dbitem localAtividade" },", em",{ "label": "Data da Atividade", "class": "tagit-choice dbitem dataAtividade" },", com duração de",{ "label": "Duração do Evento", "class": "tagit-choice dbitem duracaoEvento" },"e carga horária de",{ "label": "Carga Horária", "class": "tagit-choice dbitem cargaHoraria" }]',

'[\n    "Certificamos que",\n    {\n        "label": "Nome do Palestrante",\n        "class": "dbitem nomePalestrante"\n    },\n    "ministrou a",\n    {\n        "label": "Nome da Atividade",\n        "class": "dbitem nomeAtividade"\n    },\n    ", realizada no",\n    {\n        "label": "Local da Atividade",\n        "class": "dbitem localAtividade"\n    },\n    ", em",\n    {\n        "label": "Data da Atividade",\n        "class": "dbitem dataAtividade"\n    },\n    ", com duração de",\n    {\n        "label": "Duração do Evento",\n        "class": "dbitem duracaoEvento"\n    },\n    "e carga horária de",\n    {\n        "label": "Carga Horária",\n        "class": "dbitem cargaHoraria"\n    }\n]',

'padrao',

'				\n					<!--Certificado enviado para PDF-->\n					<div class="containerPDF justifycenter">\n						<div class="center-block">\n							<img id="ImagemLogo" src="http://localhost:85/tcc/images/uploads/logos/small/5b5bc6ad33beebb171f8a6e8a4149502.png">\n						</div>\n						\n						<div id="TituloCertificado" class="center-block reset-css">Certificado</div>\n						\n						<div class="center-block">\n							<img id="TituloMarcador" src="http://localhost:85/tcc/styles/certificados/images/marcador-titulo-padrao.png">\n						</div>\n						\n						<div id="containerDinamico">Certificamos que <span class="dbItemCertificado tagit-choice dbitem nomeParticipante">Nome do Participante</span> participou da <span class="dbItemCertificado tagit-choice dbitem nomeAtividade">Nome da Atividade</span>, realizada no <span class="dbItemCertificado tagit-choice dbitem localAtividade">Local da Atividade</span>, em <span class="dbItemCertificado tagit-choice dbitem dataAtividade">Data da Atividade</span>, com duração de <span class="dbItemCertificado tagit-choice dbitem duracaoEvento">Duração do Evento</span> e carga horária de <span class="dbItemCertificado tagit-choice dbitem cargaHoraria">Carga Horária</span></div>\n					</div>\n					<!--Certificado enviado para PDF-->\n					\n					<table class="assinaturas justifycenter">\n						<tbody><tr>\n							<td><img id="AssinaturaPalestrante" class="assinatura" src="http://localhost:85/tcc/images/uploads/logos/small/32dc6fa8ca13a53ebcad2053e87165fb.png"></td>\n							<td class="hide-palestrante"></td>\n						</tr>\n						<tr>\n							<td><hr></td>\n							<td class="hide-palestrante"><hr></td>\n						</tr>\n						<tr>\n							<td><small><strong>Nome do Palestrantre</strong><br>Cargo do Palestrante</small></td>\n							<td class="hide-palestrante"><small><strong>Nome do Participante</strong></small></td>\n						</tr>\n					</tbody></table>\n					\n					<div class="rodapeCertificado registro bottom-left justifyleft fixed-pdf">\n							Registro nº 9081/15 folha 86 do livro nº 2\n					</div>\n					\n					<div class="rodapeCertificado justifycenter autenticidade bottom-right fixed-pdf">\n						confirme a autenticidade deste certificado em\n						<span class="siteCertificado">http://localhost:85/tcc/validar-certificado/</span>\n					</div>\n					\n					\n\n				');


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
