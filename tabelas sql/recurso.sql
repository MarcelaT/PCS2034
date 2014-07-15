CREATE TABLE Recurso (
   idRecurso INTEGER UNSIGNED NOT NULL auto_increment,
   quant INTEGER UNSIGNED  NOT NULL,
   protocolo INTEGER UNSIGNED NOT NULL,
   PRIMARY KEY (idRecurso), 
   FOREIGN KEY (protocolo) REFERENCES Missao(protocolo)
 );
 INSERT INTO Recurso (protocolo, quant)
     VALUES  (12345677, 2);
 INSERT INTO Recurso (protocolo, quant)
     VALUES  (9999999, 1);
 INSERT INTO Recurso (protocolo, quant)
     VALUES  (1234444, 3);