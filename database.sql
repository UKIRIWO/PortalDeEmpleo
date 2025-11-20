/* Script ejecución completo*/

-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema portal_de_empleo
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema portal_de_empleo
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `portal_de_empleo` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `portal_de_empleo` ;

-- -----------------------------------------------------
-- Table `portal_de_empleo`.`rol`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `portal_de_empleo`.`rol` ;

CREATE TABLE IF NOT EXISTS `portal_de_empleo`.`rol` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nombre` (`nombre` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `portal_de_empleo`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `portal_de_empleo`.`user` ;

CREATE TABLE IF NOT EXISTS `portal_de_empleo`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre_usuario` VARCHAR(100) NULL,
  `password` VARCHAR(255) NOT NULL,
  `id_rol_fk` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nombre_usuario` (`nombre_usuario` ASC) VISIBLE,
  INDEX `id_rol_fk` (`id_rol_fk` ASC) VISIBLE,
  CONSTRAINT `user_ibfk_1`
    FOREIGN KEY (`id_rol_fk`)
    REFERENCES `portal_de_empleo`.`rol` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `portal_de_empleo`.`alumno`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `portal_de_empleo`.`alumno` ;

CREATE TABLE IF NOT EXISTS `portal_de_empleo`.`alumno` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_user_fk` INT NOT NULL,
  `dni` VARCHAR(9) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `ape1` VARCHAR(100) NOT NULL,
  `ape2` VARCHAR(100) NULL DEFAULT NULL,
  `curriculum` MEDIUMBLOB NULL,
  `fecha_nacimiento` DATE NULL,
  `direccion` VARCHAR(255) NULL,
  `foto` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_user_fk` (`id_user_fk` ASC) VISIBLE,
  UNIQUE INDEX `dni` (`dni` ASC) VISIBLE,
  CONSTRAINT `alumno_ibfk_1`
    FOREIGN KEY (`id_user_fk`)
    REFERENCES `portal_de_empleo`.`user` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `portal_de_empleo`.`token`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `portal_de_empleo`.`token`;

CREATE TABLE IF NOT EXISTS `portal_de_empleo`.`token` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_user_fk` INT NOT NULL,
  `token` VARCHAR(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_user_fk` (`id_user_fk` ASC) VISIBLE,
  CONSTRAINT `token_ibfk_1`
    FOREIGN KEY (`id_user_fk`)
    REFERENCES `portal_de_empleo`.`user` (`id`)
    ON DELETE CASCADE
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `portal_de_empleo`.`familia`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `portal_de_empleo`.`familia` ;

CREATE TABLE IF NOT EXISTS `portal_de_empleo`.`familia` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nombre` (`nombre` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `portal_de_empleo`.`ciclo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `portal_de_empleo`.`ciclo` ;

CREATE TABLE IF NOT EXISTS `portal_de_empleo`.`ciclo` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `nivel` ENUM('basico', 'medio', 'superior', 'curso_especializacion') NOT NULL,
  `familia_fk` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `familia_fk` (`familia_fk` ASC) VISIBLE,
  CONSTRAINT `ciclo_ibfk_1`
    FOREIGN KEY (`familia_fk`)
    REFERENCES `portal_de_empleo`.`familia` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `portal_de_empleo`.`empresa`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `portal_de_empleo`.`empresa` ;

CREATE TABLE IF NOT EXISTS `portal_de_empleo`.`empresa` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_user_fk` INT NOT NULL,
  `nombre` VARCHAR(255) NOT NULL,
  `direccion` VARCHAR(255) NOT NULL,
  `persona_de_contacto` VARCHAR(100) NOT NULL,
  `correo_de_contacto` VARCHAR(100) NOT NULL,
  `telefono_de_contacto` VARCHAR(20) NOT NULL,
  `logo` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_user_fk` (`id_user_fk` ASC) VISIBLE,
  CONSTRAINT `empresa_ibfk_1`
    FOREIGN KEY (`id_user_fk`)
    REFERENCES `portal_de_empleo`.`user` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `portal_de_empleo`.`empresa_candidata`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `portal_de_empleo`.`empresa_candidata` ;

CREATE TABLE IF NOT EXISTS `portal_de_empleo`.`empresa_candidata` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_user_fk` INT NOT NULL,
  `nombre` VARCHAR(255) NOT NULL,
  `direccion` VARCHAR(255) NOT NULL,
  `persona_de_contacto` VARCHAR(100) NOT NULL,
  `correo_de_contacto` VARCHAR(100) NOT NULL,
  `telefono_de_contacto` VARCHAR(20) NOT NULL,
  `logo` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_user_fk` (`id_user_fk` ASC) VISIBLE,
  CONSTRAINT `empresa_candidata_ibfk_1`
    FOREIGN KEY (`id_user_fk`)
    REFERENCES `portal_de_empleo`.`user` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `portal_de_empleo`.`estudios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `portal_de_empleo`.`estudios` ;

CREATE TABLE IF NOT EXISTS `portal_de_empleo`.`estudios` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_alumno_fk` INT NOT NULL,
  `id_ciclo_fk` INT NOT NULL,
  `fecha_inicio` DATE NULL DEFAULT NULL,
  `fecha_fin` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `id_alumno_fk` (`id_alumno_fk` ASC) VISIBLE,
  INDEX `id_ciclo_fk` (`id_ciclo_fk` ASC) VISIBLE,
  CONSTRAINT `estudios_ibfk_1`
    FOREIGN KEY (`id_alumno_fk`)
    REFERENCES `portal_de_empleo`.`alumno` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `estudios_ibfk_2`
    FOREIGN KEY (`id_ciclo_fk`)
    REFERENCES `portal_de_empleo`.`ciclo` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `portal_de_empleo`.`forgotten_password`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `portal_de_empleo`.`forgotten_password` ;

CREATE TABLE IF NOT EXISTS `portal_de_empleo`.`forgotten_password` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_user_fk` INT NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `old_password` VARCHAR(255) NOT NULL,
  `fecha_creacion` DATETIME NOT NULL,
  `fecha_expiracion` DATETIME NOT NULL,
  `used` TINYINT NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_user_fk` (`id_user_fk` ASC) VISIBLE,
  CONSTRAINT `forgotten_password_ibfk_1`
    FOREIGN KEY (`id_user_fk`)
    REFERENCES `portal_de_empleo`.`user` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `portal_de_empleo`.`oferta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `portal_de_empleo`.`oferta` ;

CREATE TABLE IF NOT EXISTS `portal_de_empleo`.`oferta` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_empresa_fk` INT NOT NULL,
  `fecha_inicio` DATE NOT NULL,
  `fecha_fin` DATE NOT NULL,
  `titulo` VARCHAR(150) NOT NULL,
  `descripcion` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `id_empresa_fk` (`id_empresa_fk` ASC) VISIBLE,
  CONSTRAINT `oferta_ibfk_1`
    FOREIGN KEY (`id_empresa_fk`)
    REFERENCES `portal_de_empleo`.`empresa` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `portal_de_empleo`.`oferta_ciclo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `portal_de_empleo`.`oferta_ciclo` ;

CREATE TABLE IF NOT EXISTS `portal_de_empleo`.`oferta_ciclo` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_ciclo_fk` INT NOT NULL,
  `id_oferta_fk` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `id_ciclo_fk` (`id_ciclo_fk` ASC) VISIBLE,
  INDEX `id_oferta_fk` (`id_oferta_fk` ASC) VISIBLE,
  CONSTRAINT `oferta_ciclo_ibfk_1`
    FOREIGN KEY (`id_ciclo_fk`)
    REFERENCES `portal_de_empleo`.`ciclo` (`id`),
  CONSTRAINT `oferta_ciclo_ibfk_2`
    FOREIGN KEY (`id_oferta_fk`)
    REFERENCES `portal_de_empleo`.`oferta` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `portal_de_empleo`.`solicitud`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `portal_de_empleo`.`solicitud` ;

CREATE TABLE IF NOT EXISTS `portal_de_empleo`.`solicitud` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_oferta_fk` INT NOT NULL,
  `id_alumno_fk` INT NOT NULL,
  `fecha_solicitud` DATE NOT NULL,
  `estado` ENUM('pendiente', 'aceptada', 'rechazada') NOT NULL DEFAULT 'pendiente',
  `favorito` TINYINT NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `id_oferta_fk` (`id_oferta_fk` ASC) VISIBLE,
  INDEX `id_alumno_fk` (`id_alumno_fk` ASC) VISIBLE,
  CONSTRAINT `solicitud_ibfk_1`
    FOREIGN KEY (`id_oferta_fk`)
    REFERENCES `portal_de_empleo`.`oferta` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `solicitud_ibfk_2`
    FOREIGN KEY (`id_alumno_fk`)
    REFERENCES `portal_de_empleo`.`alumno` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


INSERT INTO rol (nombre) VALUES 
('admin'),
('empresa'),
('alumno');


INSERT INTO familia (nombre) VALUES
('Informática'),
('Administración'),
('Comercio'),
('Sanidad'),
('Electricidad');

INSERT INTO ciclo (nombre, nivel, familia_fk) VALUES
('DAW', 'superior', 1),
('ASIR', 'superior', 1),
('SMR', 'medio', 1),
('Administración y Finanzas', 'superior', 2),
('Actividades Comerciales', 'medio', 3),
('Emergencias Sanitarias', 'basico', 4),
('Robótica Colaborativa', 'curso_especializacion', 5);


INSERT INTO user (nombre_usuario, password, id_rol_fk) VALUES 
('adrian', '$2y$10$T2ZobgY5RGOfwB0KPlE15eLhTmma6otJqynBQepCK8gUxaw80XLyG', 1),
('adriEmpresa', '$2y$10$T2ZobgY5RGOfwB0KPlE15eLhTmma6otJqynBQepCK8gUxaw80XLyG', 2),
('adriAlumno', '$2y$10$T2ZobgY5RGOfwB0KPlE15eLhTmma6otJqynBQepCK8gUxaw80XLyG', 3),
('Inforjobs', '$2y$10$T2ZobgY5RGOfwB0KPlE15eLhTmma6otJqynBQepCK8gUxaw80XLyG', 2),
('Alumno1', '$2y$10$T2ZobgY5RGOfwB0KPlE15eLhTmma6otJqynBQepCK8gUxaw80XLyG', 3);


INSERT INTO empresa (id_user_fk, nombre, direccion, persona_de_contacto, correo_de_contacto, telefono_de_contacto, logo) VALUES
(2, 'WorkSphere', 'Av. de la Tecnología 45, Málaga', 'Adrian González', 'servicioAlCliente@worksphere.com', '+34 600 123 456', 'logo_1.png');

INSERT INTO empresa_candidata (id_user_fk, nombre, direccion, persona_de_contacto, correo_de_contacto, telefono_de_contacto, logo) VALUES
(4, 'InfoJobs', 'C. Salsipuedes 4A, Jaén', 'Mario Garrido', 'clientes@Infojobs.com', '+34 633 064 859', 'logo_4.png');

INSERT INTO alumno (id_user_fk, dni, email, nombre, ape1, ape2, fecha_nacimiento, direccion, foto) VALUES 
(3, '12345678A', 'adriangogu1508@gmail.com', 'Carlos', 'Gómez', 'López', '2000-05-10', 'Plaza de la corona, 3', null),
(5, '77647117Z', 'correoInstitucional@gmail.com', 'Celia', 'Garcia', 'Gómez', '2006-08-15', null, 'foto_5.png');



INSERT INTO oferta (id_empresa_fk, fecha_inicio, fecha_fin, titulo, descripcion) VALUES
(1, '2025-11-01', '2025-12-31', 'Desarrollador Junior PHP', 'Buscamos alumno de prácticas con conocimientos de PHP.'),
(1, '2024-11-01', '2025-12-31', 'Frontend con JavaScript', 'Buscamos perfil orientado a frontend y frameworks JS.');

INSERT INTO oferta_ciclo (id_ciclo_fk, id_oferta_fk) VALUES
(1, 1),
(1, 2);

INSERT INTO solicitud (id_oferta_fk, id_alumno_fk, fecha_solicitud, estado) VALUES
(1, 1, '2025-07-04', 'pendiente'),
(2, 1, '2025-03-14', 'rechazada');

INSERT INTO solicitud (id_oferta_fk, id_alumno_fk, fecha_solicitud, estado) VALUES
(1, 1, '2025-03-14', 'pendiente');

INSERT INTO estudios (id_alumno_fk, id_ciclo_fk, fecha_inicio, fecha_fin) VALUES 
(1, 1, '2023-09-01', '2025-06-30'),
(1, 2, '2021-09-01', '2023-06-30');






/*NUEVOS INSERTS*/
INSERT INTO user (nombre_usuario, password, id_rol_fk) VALUES
('TecnoEmpresa', '$2y$10$T2ZobgY5RGOfwB0KPlE15eLhTmma6otJqynBQepCK8gUxaw80XLyG', 2),
('ElectroServ', '$2y$10$T2ZobgY5RGOfwB0KPlE15eLhTmma6otJqynBQepCK8gUxaw80XLyG', 2);
INSERT INTO empresa (id_user_fk, nombre, direccion, persona_de_contacto, correo_de_contacto, telefono_de_contacto, logo) VALUES
(6, 'TecnoEmpresa', 'C/ Innovación 10, Sevilla', 'Laura Ruiz', 'contacto@tecnoemp.com', '600112233', 'logo_6.png'),
(7, 'ElectroServ', 'Av. Electricistas 40, Córdoba', 'Luis Pérez', 'info@electroserv.com', '677889900', 'logo_7.png');
INSERT INTO user (nombre_usuario, password, id_rol_fk) VALUES
('Alumno2', '$2y$10$T2ZobgY5RGOfwB0KPlE15eLhTmma6otJqynBQepCK8gUxaw80XLyG', 3),
('Alumno3', '$2y$10$T2ZobgY5RGOfwB0KPlE15eLhTmma6otJqynBQepCK8gUxaw80XLyG', 3);
INSERT INTO alumno (id_user_fk, dni, email, nombre, ape1, ape2, fecha_nacimiento, direccion, foto) VALUES
(6, '55443322B', 'alumno2@gmail.com', 'David', 'Martín', 'Ruiz', '2003-02-20', 'C/ Sol 21', null),
(7, '99887766C', 'alumno3@gmail.com', 'Lucía', 'Santos', 'Pérez', '2004-12-01', 'C/ Luna 4', null);
INSERT INTO estudios (id_alumno_fk, id_ciclo_fk, fecha_inicio, fecha_fin) VALUES 
(2, 2, '2022-09-01', '2024-06-30'),
(3, 3, '2023-09-01', '2025-06-30'),
(3, 7, '2024-01-01', '2024-12-01');
INSERT INTO oferta (id_empresa_fk, fecha_inicio, fecha_fin, titulo, descripcion) VALUES
(2, '2025-10-01', '2026-10-01', 'Técnico ASIR', 'Buscamos técnico administrador de sistemas ASIR.'),
(2, '2025-05-10', '2026-01-01', 'Prácticas en Ciberseguridad', 'Puestos orientados a estudiantes de ASIR.'),
(3, '2025-09-01', '2025-12-15', 'Técnico de Mantenimiento', 'Buscamos perfil de grado medio en sistemas y electricidad.'),
(3, '2025-03-01', '2025-11-30', 'Especialista en Robótica', 'Puesto orientado a alumnos de cursos de especialización.');
INSERT INTO oferta_ciclo (id_ciclo_fk, id_oferta_fk) VALUES
(2, 3), -- Técnico ASIR
(2, 4), -- Ciberseguridad (ASIR)
(3, 5), -- Técnico mantenimiento (SMR)
(7, 6); -- Especialista en robótica
