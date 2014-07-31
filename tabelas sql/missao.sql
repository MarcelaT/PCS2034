
-- Missão
CREATE TABLE Missao (
	id INTEGER UNSIGNED NOT NULL auto_increment,
	idTipoMissao INTEGER UNSIGNED NOT NULL,
	protocolo INTEGER UNSIGNED NOT NULL,
	status enum('cadastrada','em_andamento','concluida','abortada') NOT NULL DEFAULT 'cadastrada',
	nome varchar(100) NOT NULL,
	recursosAlocados varchar(100) NOT NULL,
	PRIMARY KEY (id), 
	FOREIGN KEY (idTipoMissao) REFERENCES TipodeMissao(id)
 );
 
-- Índices (primary key)
CREATE UNIQUE INDEX PK_missao ON Missao(id);
CREATE UNIQUE INDEX PK_missao_protocolo ON Missao(protocolo);

-- Valores interessantes
INSERT INTO Missao (idTipoMissao, protocolo, status, nome, recursosAlocados)
	VALUES  (1, 111, 'concluida', 'missao1', 'sim');
INSERT INTO Missao (idTipoMissao, protocolo, status, nome, recursosAlocados)
	VALUES  (2, 222, 'em_andamento','missao2', 'sim');
INSERT INTO Missao (idTipoMissao, protocolo, status, nome, recursosAlocados)
	VALUES  (3, 333, 'cadastrada', 'missao3', 'nao');
INSERT INTO Missao (idTipoMissao, protocolo, status, nome, recursosAlocados)
	VALUES  (1, 444, 'abortada', 'missao4', 'sim');
INSERT INTO Missao (idTipoMissao, protocolo, status, nome, recursosAlocados)
	VALUES  (2, 555, 'em_andamento','missao5', 'sim');
INSERT INTO Missao (idTipoMissao, protocolo, status, nome, recursosAlocados)
	VALUES  (3, 666, 'em_andamento','missao6', 'sim');

