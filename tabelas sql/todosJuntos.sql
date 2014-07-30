-- Acidente
CREATE TABLE acidente (
	idAcidente int(11) NOT NULL auto_increment,
	localizacao varchar(100) NOT NULL,
	descricao varchar(100) NOT NULL,
	data varchar(100) NOT NULL,
	numeroVitimas int(11) NOT NULL,
	bombeiro boolean NOT NULL,
	policia boolean NOT NULL,
	obstrucao int(11) NOT NULL,
	PRIMARY KEY (idAcidente)
);
 
-- Índice (primary key)
CREATE UNIQUE INDEX PK_acidente ON acidente(idAcidente);

-- Valores interessantes
INSERT INTO acidente (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao)
	VALUES ('Av Vergueiro 300, Sao Paulo', 'Engavetamento', '15/07/14', 4, false, true, 3);
INSERT INTO acidente (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao)
	VALUES ('Rua Lins de Vasconcelos 589, Sao Paulo', 'Atropelamento de moto', '23/06/14', 1, true, false, 2);
INSERT INTO acidente (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao)
	VALUES ('Rua Ricardo Jaffet 1700, Sao Paulo', 'Batida de carro no poste', '01/07/14', 2, false, true, 1);
INSERT INTO acidente (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao)
	VALUES ('Av Politecnica 730, Sao Paulo', 'Atropelamento de pedestres', '28/05/14', 3, true, false, 1);
INSERT INTO acidente (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao)
	VALUES ('Av 9 de Julho 400, Sao Paulo', 'Batida de carro', '15/07/14', 2, false, true, 2);

		
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


-- Missao
CREATE TABLE Missao (
   id INTEGER UNSIGNED NOT NULL auto_increment,
   idTipoMissao INTEGER UNSIGNED  NOT NULL,
   protocolo INTEGER UNSIGNED NOT NULL,
   status varchar(100) NOT NULL,
   nome varchar(100) NOT NULL,
   recursosAlocados varchar(100) NOT NULL,
   PRIMARY KEY (id), 
   FOREIGN KEY (idTipoMissao) REFERENCES TipodeMissao(id)
 );
 INSERT INTO Missao (idTipoMissao, protocolo, status, nome, recursosAlocados)
     VALUES  (1, 1, 'concluida', 'missao1', 'sim');
 INSERT INTO Missao (idTipoMissao, protocolo, status, nome, recursosAlocados)
     VALUES  (2, 2, 'andamento','missao2', 'sim');
 INSERT INTO Missao (idTipoMissao, protocolo, status, nome, recursosAlocados)
     VALUES  (3, 3, 'andamento', 'missao3', 'nao');

	
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
	
-- Recurso
CREATE TABLE Recurso (
	idRecurso INTEGER UNSIGNED NOT NULL auto_increment,
	quant INTEGER UNSIGNED  NOT NULL,
	idTipoRecurso INTEGER UNSIGNED  NOT NULL,
	idMissao INTEGER UNSIGNED NOT NULL,
	PRIMARY KEY (idRecurso), 
	FOREIGN KEY (idMissao) REFERENCES Missao(id),
	FOREIGN KEY (idTipoRecurso) REFERENCES TipodeRecurso(id)
);

-- Índice (primary key)
CREATE UNIQUE INDEX PK_recurso ON Recurso(idRecurso);

-- Valores interessantes
INSERT INTO Recurso (idMissao, quant, idTipoRecurso)
	VALUES  (1, 2, 2);
INSERT INTO Recurso (idMissao, quant, idTipoRecurso)
	VALUES  (2, 1, 4);
INSERT INTO Recurso (idMissao, quant, idTipoRecurso)
	VALUES  (2, 3, 2);

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
