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
  , created_at  DATE          NOT NULL DEFAULT CURRENT_DATE
  , token       VARCHAR(32)
  , email       VARCHAR(255)  NOT NULL UNIQUE
  , biografia   TEXT
  , fechaNac    DATE
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
  , inicio          TIMESTAMP(0)      NOT NULL
  , fin             TIMESTAMP(0)      NOT NULL
  , es_privado      BOOLEAN           NOT NULL DEFAULT FALSE
  , lugar_id        BIGINT            REFERENCES lugares(id)
                                      ON DELETE CASCADE
                                      ON UPDATE CASCADE
  , categoria_id    BIGINT            NOT NULL
                                      REFERENCES categorias(id)
                                      ON DELETE NO ACTION
                                      ON UPDATE CASCADE
  , creador_id      BIGINT            NOT NULL
                                      REFERENCES usuarios(id)
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
  , created_at    TIMESTAMP(0)      NOT NULL
                                    DEFAULT CURRENT_TIMESTAMP
  , usuario_id    BIGINT            NOT NULL
                                    REFERENCES usuarios(id)
  , padre_id      BIGINT            REFERENCES comentarios(id)
  , evento_id     BIGINT            NOT NULL
                                    REFERENCES eventos(id)
);

DROP TABLE IF EXISTS usuarios_eventos CASCADE;

CREATE TABLE usuarios_eventos
(
    evento_id     BIGINT            REFERENCES eventos(id)
                                    ON DELETE NO ACTION
                                    ON UPDATE CASCADE
  , usuario_id    BIGINT            REFERENCES usuarios(id)
                                    ON DELETE NO ACTION
                                    ON UPDATE CASCADE
  , PRIMARY KEY(evento_id, usuario_id)
);

-- USUARIOS ETIQUETAS TAMBIEN

-- INSERTS

INSERT INTO etiquetas(nombre)
VALUES ('Coches'),('Futbol'),('Motos'),('Airsoft'),('Ajedrez'),('Videojuegos'),
('Comics'),('Juegos de mesa'),('Manga'),('Anime'),('E-Sports'),
('League of Legends'),('Patinaje'),('Programación'),('Informática'),
('Interpretación');

INSERT INTO usuarios (nombre, password, email)
VALUES ('admin', crypt('hnmpl', gen_salt('bf', 10)), 'admin@aculturese.com'),
('pepe', crypt('pepe', gen_salt('bf', 10)), 'jose.millan@iesdonana.org');

INSERT INTO lugares (lat, lon, nombre)
VALUES (36.787998, -6.340801, 'IES Donana');

INSERT INTO categorias (nombre)
VALUES ('Cine'),('Concierto'),('Festival'),('Cumpleaños'),('Viaje'),
('Excursión'),('Académico'),('Animales'),('Fiesta local'),('Educativo'),
('Comedia'),('Interpretación'),('Temático');

INSERT INTO eventos (nombre, inicio, fin, lugar_id, categoria_id, creador_id, es_privado)
VALUES ('Revisión de proyecto', '2019-04-02 13:15:00', '2019-04-02 19:30:00', 1, 7, 1, false),
       ('Ejemplo de evento privado', '2019-06-02 13:15:00', '2019-06-02 19:30:00', 1, 4, 2, true);

INSERT INTO comentarios(texto, usuario_id, evento_id)
VALUES ('Estoy deseando ir!', 2, 1);

INSERT INTO eventos_etiquetas(evento_id, etiqueta_id)
VALUES (1, 13), (1, 14);

INSERT INTO usuarios_eventos(usuario_id, evento_id)
VALUES (1, 1), (2, 1), (2, 2);
