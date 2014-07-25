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
