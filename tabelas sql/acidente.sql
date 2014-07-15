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
 INSERT INTO acidente (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao)
     VALUES  ('Av Vergueiro 300, Sao Paulo',  'Engavetamento', '15/07/14', 4, false, true, 3);
 INSERT INTO acidente (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao)
     VALUES  ('Rua Lins de Vasconcelos 589, Sao Paulo',  'Atropelamento de moto', '23/06/14', 1, false, true, 2);
 INSERT INTO acidente (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao)
     VALUES  ('Rua Ricardo Jaffet 1700, Sao Paulo',  'Batida de carro no poste', '01/07/14', 2, false, true, 1);
 INSERT INTO acidente (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao)
     VALUES  ('Av Politecnica 730, Sao Paulo',  'Atropelamento de pedestres', '28/05/14', 3, false, true, 1);
 INSERT INTO acidente (localizacao, descricao, data, numeroVitimas, bombeiro, policia, obstrucao)
     VALUES  ('Av 9 de Julho 400, Sao Paulo',  'Batida de carro', '15/07/14', 2, false, true, 2);
