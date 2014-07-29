CREATE TABLE Missao (
   id INTEGER UNSIGNED NOT NULL auto_increment,
   idTipoMissao INTEGER UNSIGNED  NOT NULL,
   protocolo INTEGER UNSIGNED NOT NULL,
   status varchar(100) NOT NULL,
   nome varchar(100) NOT NULL,
   recursosAlocados varchar(100) NOT NULL


   PRIMARY KEY (id), 
   FOREIGN KEY (idTipoMissao) REFERENCES TipodeMissao(id)
 );
 INSERT INTO Missao (idTipoMissao, protocolo, status, nome, recursosAlocados)
     VALUES  (1, 1, 'concluida', 'missao1', 'sim');
 INSERT INTO Missao (idTipoMissao, protocolo, status, nome, recursosAlocados)
     VALUES  (2, 2, 'andamento','missao2', 'sim');
 INSERT INTO Missao (idTipoMissao, protocolo, status, nome, recursosAlocados)
     VALUES  (3, 3, 'andamento', 'missao3', 'nao');
