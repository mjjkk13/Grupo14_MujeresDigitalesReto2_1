-- Creación y Selección de la Base de Datos
CREATE DATABASE cultutickets DEFAULT CHARACTER SET utf8;
USE cultutickets;

-- -----------------------------------------------------
-- Table `eventos`
-- -----------------------------------------------------
CREATE TABLE `eventos` (
  `ideventos` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` VARCHAR(255) NOT NULL,
  `municipio` VARCHAR(45) NOT NULL,
  `departamento` VARCHAR(45) NOT NULL,
  `fecha_horaInicio` DATETIME NOT NULL,
  `fecha_horaFin` DATETIME NOT NULL,
  PRIMARY KEY (`ideventos`)
);

-- -----------------------------------------------------
-- Table `localidades`
-- -----------------------------------------------------
CREATE TABLE `localidades` (
  `idlocalidad` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idlocalidad`)
);

-- -----------------------------------------------------
-- Table `boleteria`
-- -----------------------------------------------------
CREATE TABLE `boleteria` (
  `idboleteria` INT NOT NULL AUTO_INCREMENT,
  `ideventos` INT NOT NULL,
  `idlocalidad` INT NOT NULL,
  `valor` INT NOT NULL,
  `stock` INT NOT NULL,
  PRIMARY KEY (`idboleteria`),
  FOREIGN KEY (`ideventos`) REFERENCES `eventos` (`ideventos`),
  FOREIGN KEY (`idlocalidad`) REFERENCES `localidades` (`idlocalidad`)
);

-- -----------------------------------------------------
-- Table `artistas`
-- -----------------------------------------------------
CREATE TABLE `artistas` (
  `idartistas` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `apellido` VARCHAR(45) NOT NULL,
  `genero` VARCHAR(45) NOT NULL,
  `ciudad` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idartistas`)
);

-- -----------------------------------------------------
-- Table `evento_artista` (Tabla Intermedia N:N)
-- -----------------------------------------------------
CREATE TABLE `evento_artista` (
  `ideventos` INT NOT NULL,
  `idartistas` INT NOT NULL,
  PRIMARY KEY (`ideventos`, `idartistas`),
  FOREIGN KEY (`ideventos`) REFERENCES `eventos` (`ideventos`),
  FOREIGN KEY (`idartistas`) REFERENCES `artistas` (`idartistas`)
);

-- -----------------------------------------------------
-- INSERCIÓN DE DATOS DE EJEMPLO
-- -----------------------------------------------------

-- Inserts para `localidades`
INSERT INTO `localidades` (`idlocalidad`, `nombre`) VALUES
(1, 'VIP'),
(2, 'General'),
(3, 'Preferencial');

-- Inserts para `eventos`
INSERT INTO `eventos` (`ideventos`, `nombre`, `descripcion`, `municipio`, `departamento`, `fecha_horaInicio`, `fecha_horaFin`) VALUES
(1, 'Concierto Rock Histórico', 'Un viaje musical con las mejores bandas de rock de los 80s y 90s.', 'Medellín', 'Antioquia', '2025-11-15 20:00:00', '2025-11-16 00:00:00'),
(2, 'Festival Gastronómico Sabores del Mundo', 'Muestras culinarias, talleres de cocina y música en vivo.', 'Cali', 'Valle del Cauca', '2025-12-05 10:00:00', '2025-12-07 22:00:00'),
(3, 'Obra de Teatro: La Comedia de Errores', 'Una adaptación moderna de la clásica obra de Shakespeare.', 'Bogotá', 'Cundinamarca', '2026-01-20 19:30:00', '2026-01-20 21:30:00'),
(4, 'Gran Baile de Salsa Clásica', 'Noche de pura salsa con orquestas legendarias.', 'Pereira', 'Risaralda', '2025-11-28 21:00:00', '2025-11-29 03:00:00');

-- Inserts para `artistas`
INSERT INTO `artistas` (`idartistas`, `nombre`, `apellido`, `genero`, `ciudad`) VALUES
(101, 'Los', 'Inmortales', 'Rock', 'Medellín'),
(102, 'Sazón', 'Total', 'Salsa', 'Cali'),
(103, 'Anya', 'Teatro', 'Comedia', 'Bogotá'),
(104, 'Maestro', 'Cebolla', 'Chef', 'Lima'),
(105, 'Banda', 'Épica', 'Rock', 'Pereira'),
(106, 'Clásico', 'Ritmo', 'Salsa', 'Barranquilla');

-- Inserts para `boleteria`
INSERT INTO `boleteria` (`idboleteria`, `ideventos`, `idlocalidad`, `valor`, `stock`) VALUES
(1001, 1, 1, 50000, 500),  
(1002, 1, 2, 150000, 100), 
(1003, 1, 3, 80000, 300),  
(2001, 2, 1, 30000, 1000), 
(2002, 2, 2, 80000, 200),  
(3001, 3, 3, 60000, 250);  

