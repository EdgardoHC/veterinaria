-- Script SQL para crear la tabla de auditoría
-- Grupo 6: Seguridad, auditoría y respaldo de datos

CREATE TABLE IF NOT EXISTS `auditoria` (
  `idAuditoria` INT(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` INT(11) NULL DEFAULT NULL,
  `accion` VARCHAR(50) NOT NULL,
  `tabla` VARCHAR(100) NULL DEFAULT NULL,
  `idRegistro` INT(11) NULL DEFAULT NULL,
  `datosAnteriores` TEXT NULL DEFAULT NULL,
  `datosNuevos` TEXT NULL DEFAULT NULL,
  `ip` VARCHAR(45) NULL DEFAULT NULL,
  `userAgent` VARCHAR(255) NULL DEFAULT NULL,
  `fecha` DATETIME NOT NULL,
  PRIMARY KEY (`idAuditoria`),
  INDEX `idx_usuario` (`idUsuario`),
  INDEX `idx_accion` (`accion`),
  INDEX `idx_tabla` (`tabla`),
  INDEX `idx_fecha` (`fecha`),
  CONSTRAINT `fk_auditoria_usuario` FOREIGN KEY (`idUsuario`) 
    REFERENCES `usuarios` (`idUsuario`) 
    ON DELETE SET NULL 
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

