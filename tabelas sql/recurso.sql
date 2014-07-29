CREATE TABLE Recurso (
   id INTEGER UNSIGNED NOT NULL auto_increment,
   quantidade INTEGER UNSIGNED  NOT NULL,
   idTipoRecurso INTEGER UNSIGNED NOT NULL,
   idMissao INTEGER UNSIGNED NOT NULL,
   PRIMARY KEY (id), 
   FOREIGN KEY (idTipoRecurso) REFERENCES TipodeRecurso(id),
   FOREIGN KEY (idMissao) REFERENCES Missao(id)
 );
 INSERT INTO Recurso (quantidade, idTipoRecurso, idMissao)
     VALUES  (3, 1, 1);
 INSERT INTO Recurso (quantidade, idTipoRecurso, idMissao)
     VALUES  (4, 2, 2);
 INSERT INTO Recurso (quantidade, idTipoRecurso, idMissao)
     VALUES  (5, 3, 3);