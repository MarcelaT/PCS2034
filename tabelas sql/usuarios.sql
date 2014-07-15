-- Usuarios
CREATE TABLE IF NOT EXISTS usuarios (
	id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	login varchar(100) NOT NULL UNIQUE,
	senha varchar(100) NOT NULL,
	nome varchar(100) DEFAULT NULL,
	permissao enum('administrador','coordenador','especialista','lider_missao') DEFAULT NULL,
	email varchar(100) DEFAULT NULL
);

-- Valores interessantes
INSERT INTO usuarios (login, senha, permissao, nome, email)
	VALUES  ('admin',  md5('admin'), 'administrador', 'Administrador Do Sistema', 'admin@sgcav.com');
INSERT INTO usuarios (login, senha, permissao, nome, email)
	VALUES  ('coord',  md5('coord'), 'coordenador', 'Coordenador Do Sistema', 'coordenador@sgcav.com');
INSERT INTO usuarios (login, senha, permissao, nome, email)
	VALUES  ('espec',  md5('espec'), 'especialista', 'Especialista Em Acidentes', 'especialista@sgcav.com');
INSERT INTO usuarios (login, senha, permissao, nome, email)
	VALUES  ('lider',  md5('lider'), 'lider_missao', 'Lider da Missao', 'lider@sgcav.com');
