 CREATE TABLE TipodeMissao (
   idTipoMissao INTEGER UNSIGNED NOT NULL auto_increment,
   nome varchar(100) NOT NULL,
   descricao varchar(100) NOT NULL,
   PRIMARY KEY (idTipoMissao)
 );
 INSERT INTO TipodeMissao (nome, descricao)
     VALUES  ('Resgate Ambulancia',  'Envia ambulancia para resgate de feridos');
 INSERT INTO TipodeMissao (nome, descricao)
     VALUES  ('Guincho',  'Envio de guincho para retirada de carros e liberacao da via');
 INSERT INTO TipodeMissao (nome, descricao)
     VALUES  ('Resgate Helicopter',  'Envio de helicoptero para resgates de dificil acesso');
 INSERT INTO TipodeMissao (nome, descricao)
     VALUES  ('Bombeiros',  'Envia equipe de bombeiros para o local do acidente');
 INSERT INTO TipodeMissao (nome, descricao)
     VALUES  ('Policia',  'Envia equipe de policia para o local');
