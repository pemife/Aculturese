------------------------------
-- Archivo de base de datos --
------------------------------

DROP TABLE IF EXISTS etiquetas CASCADE;

CREATE TABLE etiquetas
(
    id          BIGSERIAL         PRIMARY KEY
  , nombre      VARCHAR(32)       NOT NULL
);

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios
(
    id          BIGSERIAL     PRIMARY KEY
  , nombre      VARCHAR(32)   NOT NULL UNIQUE
                              CONSTRAINT ck_nombre_sin_espacios
                              CHECK (nombre NOT ILIKE '% %')
  , password    VARCHAR(60)   NOT NULL
  , created_at  DATE          NOT NULL DEFAULT CURRENT_TIMESTAMP
  , token       VARCHAR(32)
  , email       VARCHAR(255)  NOT NULL UNIQUE
);

DROP TABLE IF EXISTS usuarios_etiquetas CASCADE;

CREATE TABLE usuarios_etiquetas
(
    usuario_id  BIGINT        REFERENCES usuarios(id)
                              ON DELETE NO ACTION
                              ON UPDATE CASCADE
  , etiqueta_id BIGINT        REFERENCES etiquetas(id)
                              ON DELETE NO ACTION
                              ON UPDATE CASCADE
  , PRIMARY KEY (usuario_id, etiqueta_id)
);

DROP TABLE IF EXISTS lugares CASCADE;

CREATE TABLE lugares
(
    id          BIGSERIAL         PRIMARY KEY
  , lat         NUMERIC(8, 6)     NOT NULL
  , lon         NUMERIC(9, 6)     NOT NULL
  , nombre      VARCHAR(32)
  , UNIQUE(lat, lon)
);

DROP TABLE IF EXISTS categorias CASCADE;

CREATE TABLE categorias
(
    id          BIGSERIAL         PRIMARY KEY
  , nombre      VARCHAR(32)       NOT NULL
);

DROP TABLE IF EXISTS eventos CASCADE;

CREATE TABLE eventos
(
    id              BIGSERIAL         PRIMARY KEY
  , nombre          VARCHAR(255)      NOT NULL
  , inicio          TIMESTAMP         NOT NULL
  , fin             TIMESTAMP         NOT NULL
  , lugar_id        BIGINT            REFERENCES lugares(id)
                                      ON DELETE CASCADE
                                      ON UPDATE CASCADE
  , categoria_id    BIGINT            NOT NULL
                                      REFERENCES categorias(id)
                                      ON DELETE NO ACTION
                                      ON UPDATE CASCADE
);

DROP TABLE IF EXISTS eventos_etiquetas CASCADE;

CREATE TABLE eventos_etiquetas
(
    evento_id     BIGINT            REFERENCES eventos(id)
                                    ON DELETE NO ACTION
                                    ON UPDATE CASCADE
  , etiqueta_id   BIGINT            REFERENCES etiquetas(id)
                                    ON DELETE NO ACTION
                                    ON UPDATE CASCADE
  , PRIMARY KEY(evento_id, etiqueta_id)
);

DROP TABLE IF EXISTS comentarios CASCADE;

CREATE TABLE comentarios
(
    id            BIGSERIAL         PRIMARY KEY
  , texto         TEXT              NOT NULL
  , created_at    TIMESTAMP         NOT NULL
                                    DEFAULT CURRENT_TIMESTAMP
  , usuario_id    BIGINT            NOT NULL
                                    REFERENCES usuarios(id)
  , padre_id      BIGINT            REFERENCES comentarios(id)
  , evento_id     BIGINT            NOT NULL
                                    REFERENCES eventos(id)
);

-- INSERTS

INSERT INTO etiquetas(nombre)
VALUES ('Coches'),('Futbol'),('Motos'),('Airsoft'),('Ajedrez'),('Videojuegos'),
('Comics'),('Juegos de mesa'),('Manga'),('Anime'),('E-Sports'),
('League of Legends'),('Patinaje'),('Programacián'),('Informática'),
('Interpretación');

INSERT INTO usuarios (nombre, password, email)
VALUES ('pepe', crypt('pepe', gen_salt('bf', 10)), 'jose.millan@iesdonana.org');

INSERT INTO lugares (lat, lon, nombre)
VALUES (36.787998, -6.340801, 'IES Donana');

INSERT INTO categorias (nombre)
VALUES ('Cine'),('Concierto'),('Festival'),('Cumpleaños'),('Viaje'),
('Excursión'),('Académico'),('Animales'),('Fiesta local'),('Educativo'),
('Comedia'),('Interpretación'),('Temático');

INSERT INTO eventos (nombre, inicio, fin, lugar_id, categoria_id)
VALUES ('Jornada de estudio', )
