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
