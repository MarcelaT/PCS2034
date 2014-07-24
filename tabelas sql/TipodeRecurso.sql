CREATE TABLE TipodeRecurso (
   id INTEGER UNSIGNED NOT NULL auto_increment,
   nome varchar(100) NOT NULL,
   PRIMARY KEY (id)
 );
 INSERT INTO TipodeRecurso (nome)
     VALUES  ('Carro de Guincho');
 INSERT INTO TipodeRecurso (nome)
     VALUES  ('Ambulancia');
 INSERT INTO TipodeRecurso (nome)
     VALUES  ('Paramedico');
 INSERT INTO TipodeRecurso (nome)
     VALUES  ('Bombeiros');
 INSERT INTO TipodeRecurso (nome)
     VALUES  ('Policial');
 INSERT INTO TipodeRecurso (nome)
     VALUES  ('Helicoptero');
