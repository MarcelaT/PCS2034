CREATE TABLE TipodeRecurso (
   idTipoRecurso INTEGER UNSIGNED NOT NULL auto_increment,
   nome varchar(100) NOT NULL,
   idRecurso INTEGER UNSIGNED NOT NULL,
   PRIMARY KEY (idTipoRecurso), 
   FOREIGN KEY (idRecurso) REFERENCES Recurso(idRecurso)
 );
 INSERT INTO TipodeRecurso (nome, idRecurso )
     VALUES  ('Carro de Guincho', 1);
 INSERT INTO TipodeRecurso (nome, idRecurso)
     VALUES  ('Ambulancia', 2);
 INSERT INTO TipodeRecurso (nome, idRecurso)
     VALUES  ('Paramedico', 2);
 INSERT INTO TipodeRecurso (nome, idRecurso)
     VALUES  ('Bombeiros', 1);
 INSERT INTO TipodeRecurso (nome, idRecurso)
     VALUES  ('Policial', 3);
 INSERT INTO TipodeRecurso (nome, idRecurso)
     VALUES  ('Helicoptero', 1);
