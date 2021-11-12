CREATE DATABASE reality;
use reality;
CREATE TABLE usuarios(
  id   INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  nome  VARCHAR(200),
  apelido  VARCHAR(200),
  idade INT,
  morada  VARCHAR(200),
  email VARCHAR(200),
  categoria  VARCHAR(200),
  tipo       VARCHAR(50),
  codigo     VARCHAR(200),
  numero  VARCHAR(200),
  senha   VARCHAR(200),
  estado  VARCHAR(200),
  avatar  VARCHAR(100)
);

CREATE TABLE definicoes(
  id  INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  menu  VARCHAR(100),
  ocultar BOOLEAN
);

CREATE TABLE posts(
id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
text   TEXT,
time   VARCHAR(200),
photo   VARCHAR(200)
);

CREATE TABLE likes(
  id  INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  id_usuario  INT,
  id_post  INT,
  FOREIGN KEY (id_post) REFERENCES posts(id) ON DELETE CASCADE ON UPDATE NO ACTION
);

CREATE TABLE comments(
  id   INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  mensagem  TEXT,
  id_post  INT,
  id_usuario INT,
  tempo   VARCHAR(25),
  FOREIGN KEY usuarios(id_post) REFERENCES posts(id) ON DELETE CASCADE ON UPDATE NO ACTION
);