------------------------------
-- Archivo de base de datos --
------------------------------

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
  , email       VARCHAR(255)  NOT NULL
);

DROP TABLE IF EXISTS eventos CASCADE;

CREATE TABLE eventos
(
    id          BIGSERIAL         PRIMARY KEY
  , nombre      VARCHAR(255)      NOT NULL
  , inicio      TIMESTAMP         NOT NULL
);
