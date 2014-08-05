-- Acidente
CREATE TABLE acidente (
	id INTEGER UNSIGNED NOT NULL auto_increment,
	localizacao varchar(100) NOT NULL,
	descricao varchar(100) NOT NULL,
	data TIMESTAMP NOT NULL,
	numeroVitimas INTEGER NOT NULL DEFAULT 0,
	bombeiro boolean NOT NULL DEFAULT 0,
	policia boolean NOT NULL DEFAULT 0,
	obstrucao INTEGER NOT NULL DEFAULT 0,
	PRIMARY KEY (id)
);
 
-- Índice (primary key)
CREATE UNIQUE INDEX PK_acidente ON acidente(id);

-- Valores interessantes
INSERT INTO acidente (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao)
	VALUES ('Av Vergueiro 300, Sao Paulo', 'Engavetamento', '2014-07-15 18:45:07', 4, false, true, 3);
INSERT INTO acidente (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao)
	VALUES ('Rua Lins de Vasconcelos 589, Sao Paulo', 'Atropelamento de moto', '2014-06-23 11:10:59', 1, true, false, 2);
INSERT INTO acidente (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao)
	VALUES ('Rua Ricardo Jaffet 1700, Sao Paulo', 'Batida de carro no poste', '2014-07-01 23:54:11', 2, false, true, 1);
INSERT INTO acidente (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao)
	VALUES ('Av Politecnica 730, Sao Paulo', 'Atropelamento de pedestres', '2014-05-14 06:35:53', 3, true, false, 1);
INSERT INTO acidente (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao)
	VALUES ('Av 9 de Julho 400, Sao Paulo', 'Batida de carro', '2014-07-14 21:31:18', 2, false, true, 2);

/*-------------------------------------------------------------------------*/

-- Tipo de Missão
CREATE TABLE TipodeMissao (
	id INTEGER UNSIGNED NOT NULL auto_increment,
	nome varchar(100) NOT NULL,
	descricao varchar(100) NOT NULL,
	PRIMARY KEY (id)
);

-- Índice (primary key)
CREATE UNIQUE INDEX PK_tipomissao ON TipodeMissao(id);

-- Valores interessantes
INSERT INTO TipodeMissao (nome, descricao)
	VALUES ('Resgate Ambulancia', 'Envia ambulancia para resgate de feridos');
INSERT INTO TipodeMissao (nome, descricao)
	VALUES ('Guincho', 'Envio de guincho para retirada de carros e liberacao da via');
INSERT INTO TipodeMissao (nome, descricao)
	VALUES ('Resgate Helicoptero', 'Envio de helicoptero para resgates de dificil acesso');
INSERT INTO TipodeMissao (nome, descricao)
	VALUES ('Bombeiros', 'Envia equipe de bombeiros para o local do acidente');
INSERT INTO TipodeMissao (nome, descricao)
	VALUES ('Policia', 'Envia equipe de policia para o local');

/*-------------------------------------------------------------------------*/

-- Missão
CREATE TABLE Missao (
	id INTEGER UNSIGNED NOT NULL auto_increment,
	idTipoMissao INTEGER UNSIGNED NOT NULL,
	protocolo INTEGER UNSIGNED NOT NULL,
	status enum('cadastrada','em_andamento','concluida','abortada') NOT NULL DEFAULT 'cadastrada',
	nome varchar(100) NOT NULL,
	recursosAlocados boolean NOT NULL DEFAULT 0,
	PRIMARY KEY (id), 
	FOREIGN KEY (idTipoMissao) REFERENCES TipodeMissao(id)
 );
 
-- Índices (primary key)
CREATE UNIQUE INDEX PK_missao ON Missao(id);
CREATE UNIQUE INDEX PK_missao_protocolo ON Missao(protocolo);

-- Valores interessantes
INSERT INTO Missao (idTipoMissao, protocolo, status, nome, recursosAlocados)
	VALUES  (1, 111, 'concluida', 'missao1', true);
INSERT INTO Missao (idTipoMissao, protocolo, status, nome, recursosAlocados)
	VALUES  (2, 222, 'em_andamento','missao2', true);
INSERT INTO Missao (idTipoMissao, protocolo, status, nome, recursosAlocados)
	VALUES  (3, 333, 'cadastrada', 'missao3', false);
INSERT INTO Missao (idTipoMissao, protocolo, status, nome, recursosAlocados)
	VALUES  (1, 444, 'abortada', 'missao4', true);
INSERT INTO Missao (idTipoMissao, protocolo, status, nome, recursosAlocados)
	VALUES  (2, 555, 'em_andamento','missao5', true);
INSERT INTO Missao (idTipoMissao, protocolo, status, nome, recursosAlocados)
	VALUES  (3, 666, 'em_andamento','missao6', true);
INSERT INTO Missao (idTipoMissao, protocolo, status, nome, recursosAlocados)
	VALUES  (3, 777, 'cadastrada', 'missao7', false);

/*-------------------------------------------------------------------------*/

-- Tipo de Recurso
CREATE TABLE TipodeRecurso (
	id INTEGER UNSIGNED NOT NULL auto_increment,
	nome varchar(100) NOT NULL,
	PRIMARY KEY (id)
);

-- Índice (primary key)
CREATE UNIQUE INDEX PK_tiporecurso ON TipodeRecurso(id);

-- Valores interessantes
INSERT INTO TipodeRecurso (nome)
	VALUES('Carro de Guincho');
INSERT INTO TipodeRecurso (nome)
	VALUES('Ambulancia');
INSERT INTO TipodeRecurso (nome)
	VALUES('Paramedico');
INSERT INTO TipodeRecurso (nome)
	VALUES('Bombeiros');
INSERT INTO TipodeRecurso (nome)
	VALUES('Policial');
INSERT INTO TipodeRecurso (nome)
	VALUES('Helicoptero');

/*-------------------------------------------------------------------------*/

-- Recurso
CREATE TABLE Recurso (
	id INTEGER UNSIGNED NOT NULL auto_increment,
	quantidade INTEGER UNSIGNED NOT NULL DEFAULT 0,
	idTipoRecurso INTEGER UNSIGNED NOT NULL,
	idMissao INTEGER UNSIGNED NOT NULL,
	PRIMARY KEY (id), 
	FOREIGN KEY (idTipoRecurso) REFERENCES TipodeRecurso(id),
	FOREIGN KEY (idMissao) REFERENCES Missao(id)
);

-- Índice (primary key)
CREATE UNIQUE INDEX PK_recurso ON Recurso(id);

-- Valores interessantes
INSERT INTO Recurso (quantidade, idTipoRecurso, idMissao)
	VALUES  (3, 1, 1);
INSERT INTO Recurso (quantidade, idTipoRecurso, idMissao)
	VALUES  (4, 2, 2);
INSERT INTO Recurso (quantidade, idTipoRecurso, idMissao)
	VALUES  (5, 3, 4);
INSERT INTO Recurso (quantidade, idTipoRecurso, idMissao)
	VALUES  (1, 4, 5);
INSERT INTO Recurso (quantidade, idTipoRecurso, idMissao)
	VALUES  (2, 5, 6);
	
/*-------------------------------------------------------------------------*/

-- Usuarios
CREATE TABLE IF NOT EXISTS usuarios (
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
CREATE UNIQUE INDEX PK_usuario ON usuarios(id);

-- Valores interessantes
INSERT INTO usuarios (login, senha, permissao, nome, email, dataCriacao, dataEdicao)
	VALUES  ('admin',  md5('admin'), 'administrador', 'Administrador Do Sistema', 'admin@sgcav.com', '2014-07-08 10:00:00', '2014-07-08 10:00:00');
INSERT INTO usuarios (login, senha, permissao, nome, email, dataCriacao, dataEdicao)
	VALUES  ('coord',  md5('coord'), 'coordenador', 'Coordenador Do Sistema', 'coordenador@sgcav.com', '2014-07-08 10:00:00', '2014-07-08 10:00:00');
INSERT INTO usuarios (login, senha, permissao, nome, email, dataCriacao, dataEdicao)
	VALUES  ('espec',  md5('espec'), 'especialista', 'Especialista Em Acidentes', 'especialista@sgcav.com', '2014-07-08 10:00:00', '2014-07-08 10:00:00');
INSERT INTO usuarios (login, senha, permissao, nome, email, dataCriacao, dataEdicao)
	VALUES  ('lider',  md5('lider'), 'lider_missao', 'Lider da Missao', 'lider@sgcav.com', '2014-07-08 10:00:00', '2014-07-08 10:00:00');

/*-------------------------------------------------------------------------*/
