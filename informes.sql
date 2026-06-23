CREATE DATABASE informes_globales CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE informes_globales;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    area VARCHAR(150),
    cargo VARCHAR(150),
    activo TINYINT(1) DEFAULT 1,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE informes (
    id INT AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT,

    ejercicio YEAR NOT NULL,
    mes VARCHAR(20) NOT NULL,
    periodo VARCHAR(20),

    ordinarios INT DEFAULT 0,
    especiales INT DEFAULT 0,
    paraprocesales INT DEFAULT 0,

    condenatorios INT DEFAULT 0,
    absolutorios INT DEFAULT 0,
    mixtos INT DEFAULT 0,
    colectivos INT DEFAULT 0,

    resoluciones_interlocutorias INT DEFAULT 0,

    junta INT DEFAULT 0,
    presidencia INT DEFAULT 0,
    acuerdos_colectivos INT DEFAULT 0,

    convenios INT DEFAULT 0,

    audiencia_cde INT DEFAULT 0,
    audiencia_ofrecimiento INT DEFAULT 0,
    audiencia_desahogo INT DEFAULT 0,
    audiencia_area_colectiva INT DEFAULT 0,

    remates_incidentales INT DEFAULT 0,

    desistimiento_caducidad INT DEFAULT 0,

    ejecuciones_embargos INT DEFAULT 0,
    actuarios INT DEFAULT 0,

    exhortos INT DEFAULT 0,

    amparo_directo INT DEFAULT 0,
    amparo_indirecto INT DEFAULT 0,

    oficios INT DEFAULT 0,
    archivo INT DEFAULT 0,
    despachos_ejecucion INT DEFAULT 0,

    total_mes INT DEFAULT 0,

    bloqueado TINYINT(1) DEFAULT 1,

    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_usuario
    FOREIGN KEY(usuario_id)
    REFERENCES usuarios(id)
);