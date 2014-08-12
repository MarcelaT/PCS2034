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
	VALUES ('R. Vergueiro, 300 - Liberdade, São Paulo - SP', 'Engavetamento', '2014-08-01 06:35:53', 4, false, true, 3, 'finalizado');
INSERT INTO `sgcav`.`acidente` (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao, status)
	VALUES ('Av. Lins de Vasconcelos, 589 - Cambuci, São Paulo - SP', 'Atropelamento de moto', '2014-08-02 11:10:59', 1, true, false, 2, 'finalizado');
INSERT INTO `sgcav`.`acidente` (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao, status)
	VALUES ('Av. Dr. Ricardo Jafet, 1700 - Vila Mariana, São Paulo - SP', 'Batida de carro no poste', '2014-08-03 23:54:11', 2, false, true, 1, 'cadastrado');
INSERT INTO `sgcav`.`acidente` (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao, status)
	VALUES ('Av. Escola Politécnica, 730 - Rio Pequeno, São Paulo - SP', 'Atropelamento de pedestres', '2014-08-04 18:45:07', 3, true, false, 1, 'cadastrado');
INSERT INTO `sgcav`.`acidente` (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao, status)
	VALUES ('Av. Nove de Julho, 400 - Bela Vista, São Paulo - SP', 'Batida de carro', '2014-08-05 21:31:18', 2, false, true, 2, 'cadastrado');


/*-------------------------------------------------------------------------*/

-- Tipo de Missão
CREATE TABLE `sgcav`.`TipodeMissao` (
	id INTEGER UNSIGNED NOT NULL auto_increment,
	nome varchar(100) NOT NULL UNIQUE,
	descricao varchar(100) NOT NULL,
	dataCriacao TIMESTAMP NOT NULL,
	PRIMARY KEY (id)
);

-- Índice (primary key)
CREATE UNIQUE INDEX PK_tipomissao ON `sgcav`.`TipodeMissao`(id);

-- Valores interessantes
INSERT INTO `sgcav`.`TipodeMissao` (nome, descricao, dataCriacao)
	VALUES ('Resgate Ambulância', 'Envio de ambulência para resgate de feridos.', '2014-07-29 08:20:00');
INSERT INTO `sgcav`.`TipodeMissao` (nome, descricao, dataCriacao)
	VALUES ('Guincho', 'Envio de guincho para retirada de carros e liberação da via.', '2014-07-29 12:00:00');
INSERT INTO `sgcav`.`TipodeMissao` (nome, descricao, dataCriacao)
	VALUES ('Resgate Helicóptero', 'Envio de helicóptero para resgates de difícil acesso.', '2014-07-30 08:20:00');
INSERT INTO `sgcav`.`TipodeMissao` (nome, descricao, dataCriacao)
	VALUES ('Bombeiros', 'Envio de equipe de bombeiros para o local do acidente.', '2014-07-30 12:00:00');
INSERT INTO `sgcav`.`TipodeMissao` (nome, descricao, dataCriacao)
	VALUES ('Polícia', 'Envio de equipe de polícia para o local do acidente.', '2014-07-31 08:20:00');

/*-------------------------------------------------------------------------*/

-- Missão
CREATE TABLE `sgcav`.`Missao` (
	id INTEGER UNSIGNED NOT NULL auto_increment,
	idTipoMissao INTEGER UNSIGNED NOT NULL,
	protocolo varchar(100) NOT NULL,
	status enum('cadastrada','em_andamento','concluida','abortada') NOT NULL DEFAULT 'cadastrada',
	nome varchar(100) NOT NULL,
	recursosAlocados boolean NOT NULL DEFAULT 0,
	dataCriacao TIMESTAMP NOT NULL,
	idAcidente INTEGER UNSIGNED NOT NULL,
	PRIMARY KEY (id), 
	FOREIGN KEY (idTipoMissao) REFERENCES TipodeMissao(id),
	FOREIGN KEY (idAcidente) REFERENCES Acidente(id)
 );
 
-- Índices (primary key)
CREATE UNIQUE INDEX PK_missao ON `sgcav`.`Missao`(id);
CREATE UNIQUE INDEX PK_missao_protocolo ON `sgcav`.`Missao`(protocolo);

-- Valores interessantes
INSERT INTO `sgcav`.`Missao` (idTipoMissao, protocolo, status, nome, recursosAlocados, dataCriacao, idAcidente)
	VALUES  (1, 111, 'concluida', 'missao1', true, '2014-08-01 06:35:53', 1);
INSERT INTO `sgcav`.`Missao` (idTipoMissao, protocolo, status, nome, recursosAlocados, dataCriacao, idAcidente)
	VALUES  (2, 222, 'abortada','missao2', true, '2014-08-01 06:36:15', 1);
INSERT INTO `sgcav`.`Missao` (idTipoMissao, protocolo, status, nome, recursosAlocados, dataCriacao, idAcidente)
	VALUES  (3, 333, 'concluida', 'missao3', false, '2014-08-02 11:10:59', 2);
INSERT INTO `sgcav`.`Missao` (idTipoMissao, protocolo, status, nome, recursosAlocados, dataCriacao, idAcidente)
	VALUES  (1, 444, 'cadastrada', 'missao4', true, '2014-08-03 23:54:11', 3);
INSERT INTO `sgcav`.`Missao` (idTipoMissao, protocolo, status, nome, recursosAlocados, dataCriacao, idAcidente)
	VALUES  (2, 555, 'em_andamento','missao5', true, '2014-08-04 18:45:07', 4);
INSERT INTO `sgcav`.`Missao` (idTipoMissao, protocolo, status, nome, recursosAlocados, dataCriacao, idAcidente)
	VALUES  (3, 666, 'em_andamento','missao6', true, '2014-08-04 18:48:24', 4);
INSERT INTO `sgcav`.`Missao` (idTipoMissao, protocolo, status, nome, recursosAlocados, dataCriacao, idAcidente)
	VALUES  (3, 777, 'cadastrada', 'missao7', false, '2014-08-05 21:31:18', 5);

/*-------------------------------------------------------------------------*/

-- Tipo de Recurso
CREATE TABLE `sgcav`.`TipodeRecurso` (
	id INTEGER UNSIGNED NOT NULL auto_increment,
	nome varchar(100) NOT NULL UNIQUE,
	dataCriacao TIMESTAMP NOT NULL,
	PRIMARY KEY (id)
);

-- Índice (primary key)
CREATE UNIQUE INDEX PK_tiporecurso ON `sgcav`.`TipodeRecurso`(id);

-- Valores interessantes
INSERT INTO `sgcav`.`TipodeRecurso` (nome, dataCriacao)
	VALUES('Carro de Guincho', '2014-07-29 08:20:00');
INSERT INTO `sgcav`.`TipodeRecurso` (nome, dataCriacao)
	VALUES('Ambulância', '2014-07-29 12:00:00');
INSERT INTO `sgcav`.`TipodeRecurso` (nome, dataCriacao)
	VALUES('Paramédico', '2014-07-30 08:20:00');
INSERT INTO `sgcav`.`TipodeRecurso` (nome, dataCriacao)
	VALUES('Bombeiro', '2014-07-30 12:00:00');
INSERT INTO `sgcav`.`TipodeRecurso` (nome, dataCriacao)
	VALUES('Policial', '2014-07-31 08:20:00');
INSERT INTO `sgcav`.`TipodeRecurso` (nome, dataCriacao)
	VALUES('Helicóptero', '2014-07-31 12:00:00');

/*-------------------------------------------------------------------------*/

-- Recurso
CREATE TABLE `sgcav`.`Recurso` (
	id INTEGER UNSIGNED NOT NULL auto_increment,
	quantidade INTEGER UNSIGNED NOT NULL DEFAULT 0,
	idTipoRecurso INTEGER UNSIGNED NOT NULL,
	idMissao INTEGER UNSIGNED NOT NULL,
	dataCriacao TIMESTAMP NOT NULL,
	PRIMARY KEY (id), 
	FOREIGN KEY (idTipoRecurso) REFERENCES `sgcav`.`TipodeRecurso`(id),
	FOREIGN KEY (idMissao) REFERENCES `sgcav`.`Missao`(id)
);

-- Índice (primary key)
CREATE UNIQUE INDEX PK_recurso ON `sgcav`.`Recurso`(id);

-- Valores interessantes
INSERT INTO `sgcav`.`Recurso` (quantidade, idTipoRecurso, idMissao, dataCriacao)
	VALUES  (3, 1, 1, '2014-08-01 06:40:00');
INSERT INTO `sgcav`.`Recurso` (quantidade, idTipoRecurso, idMissao, dataCriacao)
	VALUES  (4, 2, 2, '2014-08-01 06:41:00');
INSERT INTO `sgcav`.`Recurso` (quantidade, idTipoRecurso, idMissao, dataCriacao)
	VALUES  (5, 3, 3, '2014-08-03 23:58:53');
INSERT INTO `sgcav`.`Recurso` (quantidade, idTipoRecurso, idMissao, dataCriacao)
	VALUES  (1, 4, 4, '2014-08-04 18:49:02');
INSERT INTO `sgcav`.`Recurso` (quantidade, idTipoRecurso, idMissao, dataCriacao)
	VALUES  (2, 5, 6, '2014-08-04 18:55:52');
INSERT INTO `sgcav`.`Recurso` (quantidade, idTipoRecurso, idMissao, dataCriacao)
	VALUES  (1, 6, 7, '2014-08-04 18:57:52');

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
	VALUES  ('admin',  md5('admin'), 'administrador', 'Administrador Do Sistema', 'admin@sgcav.com', '2014-07-27 08:20:00', '2014-07-28 12:00:00');
INSERT INTO `sgcav`.`usuarios` (login, senha, permissao, nome, email, dataCriacao, dataEdicao)
	VALUES  ('coord',  md5('coord'), 'coordenador', 'Coordenador Do Sistema', 'coordenador@sgcav.com', '2014-07-28 08:20:00', '2014-07-29 08:20:00');
INSERT INTO `sgcav`.`usuarios` (login, senha, permissao, nome, email, dataCriacao, dataEdicao)
	VALUES  ('espec',  md5('espec'), 'especialista', 'Especialista Em Acidentes', 'especialista@sgcav.com', '2014-07-28 08:20:00', '2014-07-29 12:00:00');
INSERT INTO `sgcav`.`usuarios` (login, senha, permissao, nome, email, dataCriacao, dataEdicao)
	VALUES  ('lider',  md5('lider'), 'lider_missao', 'Lider da `sgcav`.`Missao`', 'lider@sgcav.com', '2014-07-29 08:20:00', '2014-07-30 12:00:00');

/*-------------------------------------------------------------------------*/
