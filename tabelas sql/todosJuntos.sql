-- Limpeza da tabela antiga
DROP DATABASE sgcav;
CREATE DATABASE sgcav;

/*-------------------------------------------------------------------------*/

-- Acidente
CREATE TABLE `sgcav`.`acidente` (
	id INTEGER UNSIGNED NOT NULL auto_increment,
	localizacao varchar(100) NOT NULL,
	descricao varchar(100) NOT NULL,
	data TIMESTAMP NOT NULL,
	numeroVitimas INTEGER NOT NULL DEFAULT 0,
	bombeiro boolean NOT NULL DEFAULT 0,
	policia boolean NOT NULL DEFAULT 0,
	obstrucao INTEGER NOT NULL DEFAULT 0,
	status enum('cadastrado','finalizado') NOT NULL DEFAULT 'cadastrado',
	PRIMARY KEY (id)
);
 
-- Índice (primary key)
CREATE UNIQUE INDEX PK_acidente ON `sgcav`.`acidente`(id);

-- Valores interessantes
INSERT INTO `sgcav`.`acidente` (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao, status)
	VALUES ('Av Vergueiro 300, Sao Paulo', 'Engavetamento', '2014-07-15 18:45:07', 4, false, true, 3,'cadastrado');
INSERT INTO `sgcav`.`acidente` (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao, status)
	VALUES ('Rua Lins de Vasconcelos 589, Sao Paulo', 'Atropelamento de moto', '2014-06-23 11:10:59', 1, true, false, 2, 'cadastrado');
INSERT INTO `sgcav`.`acidente` (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao, status)
	VALUES ('Rua Ricardo Jaffet 1700, Sao Paulo', 'Batida de carro no poste', '2014-07-01 23:54:11', 2, false, true, 1, 'cadastrado');
INSERT INTO `sgcav`.`acidente` (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao, status)
	VALUES ('Av Politecnica 730, Sao Paulo', 'Atropelamento de pedestres', '2014-05-14 06:35:53', 3, true, false, 1, 'finalizado');
INSERT INTO `sgcav`.`acidente` (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao, status)
	VALUES ('Av 9 de Julho 400, Sao Paulo', 'Batida de carro', '2014-07-14 21:31:18', 2, false, true, 2, 'finalizado');


/*-------------------------------------------------------------------------*/

-- Tipo de Missão
CREATE TABLE `sgcav`.`TipodeMissao` (
	id INTEGER UNSIGNED NOT NULL auto_increment,
	nome varchar(100) NOT NULL UNIQUE,
	descricao varchar(100) NOT NULL,
	PRIMARY KEY (id)
);

-- Índice (primary key)
CREATE UNIQUE INDEX PK_tipomissao ON `sgcav`.`TipodeMissao`(id);

-- Valores interessantes
INSERT INTO `sgcav`.`TipodeMissao` (nome, descricao)
	VALUES ('Resgate Ambulancia', 'Envia ambulancia para resgate de feridos');
INSERT INTO `sgcav`.`TipodeMissao` (nome, descricao)
	VALUES ('Guincho', 'Envio de guincho para retirada de carros e liberacao da via');
INSERT INTO `sgcav`.`TipodeMissao` (nome, descricao)
	VALUES ('Resgate Helicoptero', 'Envio de helicoptero para resgates de dificil acesso');
INSERT INTO `sgcav`.`TipodeMissao` (nome, descricao)
	VALUES ('Bombeiros', 'Envia equipe de bombeiros para o local do acidente');
INSERT INTO `sgcav`.`TipodeMissao` (nome, descricao)
	VALUES ('Policia', 'Envia equipe de policia para o local');

/*-------------------------------------------------------------------------*/

-- Missão
CREATE TABLE `sgcav`.`Missao` (
	id INTEGER UNSIGNED NOT NULL auto_increment,
	idTipoMissao INTEGER UNSIGNED NOT NULL,
	protocolo varchar(100) NOT NULL,
	status enum('cadastrada','em_andamento','concluida','abortada') NOT NULL DEFAULT 'cadastrada',
	nome varchar(100) NOT NULL,
	recursosAlocados boolean NOT NULL DEFAULT 0,
	idAcidente INTEGER UNSIGNED NOT NULL,

	PRIMARY KEY (id), 
	FOREIGN KEY (idTipoMissao) REFERENCES TipodeMissao(id),
	FOREIGN KEY (idAcidente) REFERENCES Acidente(id)
 );
 
-- Índices (primary key)
CREATE UNIQUE INDEX PK_missao ON `sgcav`.`Missao`(id);
CREATE UNIQUE INDEX PK_missao_protocolo ON `sgcav`.`Missao`(protocolo);

-- Valores interessantes

INSERT INTO `sgcav`.`Missao` (idTipoMissao, protocolo, status, nome, recursosAlocados, idAcidente)
	VALUES  (1, '111', 'concluida', 'missao1', true, 1);
INSERT INTO `sgcav`.`Missao` (idTipoMissao, protocolo, status, nome, recursosAlocados, idAcidente)
	VALUES  (2, '222', 'em_andamento','missao2', true, 2);
INSERT INTO `sgcav`.`Missao` (idTipoMissao, protocolo, status, nome, recursosAlocados, idAcidente)
	VALUES  (1, '113', 'concluida', 'missao3', true, 1);
INSERT INTO `sgcav`.`Missao` (idTipoMissao, protocolo, status, nome, recursosAlocados, idAcidente)
	VALUES  (2, '224', 'em_andamento','missao4', true, 2);
INSERT INTO `sgcav`.`Missao` (idTipoMissao, protocolo, status, nome, recursosAlocados, idAcidente)
	VALUES  (2, '223', 'em_andamento','missao5', true, 2);
INSERT INTO `sgcav`.`Missao` (idTipoMissao, protocolo, status, nome, recursosAlocados, idAcidente)
	VALUES  (2, '225', 'em_andamento','missao6', true, 2);

/*-------------------------------------------------------------------------*/

-- Tipo de Recurso
CREATE TABLE `sgcav`.`TipodeRecurso` (
	id INTEGER UNSIGNED NOT NULL auto_increment,
	nome varchar(100) NOT NULL UNIQUE,
	PRIMARY KEY (id)
);

-- Índice (primary key)
CREATE UNIQUE INDEX PK_tiporecurso ON `sgcav`.`TipodeRecurso`(id);

-- Valores interessantes
INSERT INTO `sgcav`.`TipodeRecurso` (nome)
	VALUES('Carro de Guincho');
INSERT INTO `sgcav`.`TipodeRecurso` (nome)
	VALUES('Ambulancia');
INSERT INTO `sgcav`.`TipodeRecurso` (nome)
	VALUES('Paramedico');
INSERT INTO `sgcav`.`TipodeRecurso` (nome)
	VALUES('Bombeiros');
INSERT INTO `sgcav`.`TipodeRecurso` (nome)
	VALUES('Policial');
INSERT INTO `sgcav`.`TipodeRecurso` (nome)
	VALUES('Helicoptero');

/*-------------------------------------------------------------------------*/

-- Recurso
CREATE TABLE `sgcav`.`Recurso` (
	id INTEGER UNSIGNED NOT NULL auto_increment,
	quantidade INTEGER UNSIGNED NOT NULL DEFAULT 0,
	idTipoRecurso INTEGER UNSIGNED NOT NULL,
	idMissao INTEGER UNSIGNED NOT NULL,
	PRIMARY KEY (id), 
	FOREIGN KEY (idTipoRecurso) REFERENCES `sgcav`.`TipodeRecurso`(id),
	FOREIGN KEY (idMissao) REFERENCES `sgcav`.`Missao`(id)
);

-- Índice (primary key)
CREATE UNIQUE INDEX PK_recurso ON `sgcav`.`Recurso`(id);

-- Valores interessantes
INSERT INTO `sgcav`.`Recurso` (quantidade, idTipoRecurso, idMissao)
	VALUES  (3, 1, 1);
INSERT INTO `sgcav`.`Recurso` (quantidade, idTipoRecurso, idMissao)
	VALUES  (4, 2, 2);
INSERT INTO `sgcav`.`Recurso` (quantidade, idTipoRecurso, idMissao)
	VALUES  (5, 3, 4);
INSERT INTO `sgcav`.`Recurso` (quantidade, idTipoRecurso, idMissao)
	VALUES  (1, 4, 5);

	
/*-------------------------------------------------------------------------*/

-- Usuarios
CREATE TABLE IF NOT EXISTS `sgcav`.`usuarios` (
	id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	login varchar(100) NOT NULL UNIQUE,
	senha varchar(100) NOT NULL,
	nome varchar(100) DEFAULT NULL,
	permissao enum('administrador','coordenador','especialista','lider_missao') NOT NULL DEFAULT 'administrador',
	email varchar(100) DEFAULT NULL,
	dataCriacao TIMESTAMP NOT NULL,
	dataEdicao TIMESTAMP NOT NULL
);

-- Índice (primary key)
CREATE UNIQUE INDEX PK_usuario ON `sgcav`.`usuarios`(id);

-- Valores interessantes
INSERT INTO `sgcav`.`usuarios` (login, senha, permissao, nome, email, dataCriacao, dataEdicao)
	VALUES  ('admin',  md5('admin'), 'administrador', 'Administrador Do Sistema', 'admin@sgcav.com', '2014-07-08 10:00:00', '2014-07-08 10:00:00');
INSERT INTO `sgcav`.`usuarios` (login, senha, permissao, nome, email, dataCriacao, dataEdicao)
	VALUES  ('coord',  md5('coord'), 'coordenador', 'Coordenador Do Sistema', 'coordenador@sgcav.com', '2014-07-08 10:00:00', '2014-07-08 10:00:00');
INSERT INTO `sgcav`.`usuarios` (login, senha, permissao, nome, email, dataCriacao, dataEdicao)
	VALUES  ('espec',  md5('espec'), 'especialista', 'Especialista Em Acidentes', 'especialista@sgcav.com', '2014-07-08 10:00:00', '2014-07-08 10:00:00');
INSERT INTO `sgcav`.`usuarios` (login, senha, permissao, nome, email, dataCriacao, dataEdicao)
	VALUES  ('lider',  md5('lider'), 'lider_missao', 'Lider da `sgcav`.`Missao`', 'lider@sgcav.com', '2014-07-08 10:00:00', '2014-07-08 10:00:00');

/*-------------------------------------------------------------------------*/
