-- ==============================================================
-- Country Club Management System - Database Schema
-- Base de datos: Country_DB
-- ==============================================================

CREATE DATABASE IF NOT EXISTS `Country_DB` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `Country_DB`;

-- --------------------------------------------------------
-- 1. Tabla de Usuarios (Administradores, Secretarias, Vigilantes)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre_completo` VARCHAR(150) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `rol` ENUM('admin', 'secretaria', 'vigilante') NOT NULL DEFAULT 'secretaria',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. Tabla de Socios del Club
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `socios` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(100) NOT NULL,
    `apellido` VARCHAR(100) NOT NULL,
    `direccion` TEXT DEFAULT NULL,
    `cedula` VARCHAR(20) NOT NULL UNIQUE,
    `numero` VARCHAR(30) DEFAULT NULL,
    `correo` VARCHAR(150) NOT NULL UNIQUE,
    `accion` VARCHAR(50) NOT NULL UNIQUE,
    `estado` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    `saldo` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    `ultimo_mes_pagado` DATETIME DEFAULT NULL,
    `fecha_nacimiento` DATE DEFAULT NULL,
    `socio_desde` DATE DEFAULT NULL,
    `vencimiento` DATE DEFAULT NULL,
    `imagen_perfil` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. Tabla de Pagos
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pagos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `socios_id` INT NOT NULL,
    `descripcion` VARCHAR(255) NOT NULL,
    `monto` DECIMAL(10, 2) NOT NULL,
    `fecha_pago` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_pagos_socios` FOREIGN KEY (`socios_id`) REFERENCES `socios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 4. Tabla de Auditoría / Movimientos
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `movimientos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `usuarios_id` INT NOT NULL,
    `tipo` VARCHAR(50) NOT NULL, -- 'Registro', 'Pago', 'Actualización', 'Login'
    `fecha` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_movimientos_usuarios` FOREIGN KEY (`usuarios_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 5. Tabla de Registro de Visitas
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `visitas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `socios_id` INT NOT NULL,
    `fecha_entrada` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_visitas_socios` FOREIGN KEY (`socios_id`) REFERENCES `socios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 6. Tabla de Tasa de Cambio Oficial (BCV)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tasa_dolar` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `tasa` DECIMAL(10, 4) NOT NULL,
    `fecha_actualizacion` DATETIME NOT NULL,
    `scraped_today` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================
-- DATOS SEMILLA (SEED DATA)
-- ==============================================================

-- Usuario Administrador por defecto:
-- Correo: admin@countryclub.com
-- Contraseña por defecto: password (hash bcrypt: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi)
INSERT INTO `usuarios` (`nombre_completo`, `email`, `password`, `rol`) VALUES
('Administrador del Club', 'admin@countryclub.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Secretaría General', 'secretaria@countryclub.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'secretaria'),
('Control de Acceso Vigilancia', 'vigilancia@countryclub.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'vigilante')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Socios de Prueba
INSERT INTO `socios` (`nombre`, `apellido`, `direccion`, `cedula`, `numero`, `correo`, `accion`, `estado`, `saldo`, `ultimo_mes_pagado`, `fecha_nacimiento`, `socio_desde`) VALUES
('Carlos', 'Mendoza', 'Av. Principal Las Mercedes, Quinta Los Robles', '12345678', '0414-1234567', 'carlos.mendoza@email.com', 'ACC-001', 'activo', 0.00, NOW(), '1985-05-15', '2015-01-10'),
('María', 'Fernández', 'Calle Los Pinos, Res. El Bosque, Apto 4B', '18765432', '0424-9876543', 'maria.fernandez@email.com', 'ACC-002', 'activo', -30.00, DATE_SUB(NOW(), INTERVAL 3 MONTH), '1990-09-20', '2018-06-01'),
('Roberto', 'Castillo', 'Urb. Alto Prado, Calle 2, Casa #15', '14523987', '0412-5554321', 'roberto.castillo@email.com', 'ACC-003', 'inactivo', -120.00, DATE_SUB(NOW(), INTERVAL 12 MONTH), '1978-11-03', '2010-03-15')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Tasa Dólar Inicial
INSERT INTO `tasa_dolar` (`tasa`, `fecha_actualizacion`, `scraped_today`) VALUES
(40.5000, NOW(), 1);

-- Registro inicial de Pagos de prueba
INSERT INTO `pagos` (`socios_id`, `descripcion`, `monto`, `fecha_pago`) VALUES
(1, 'Cuota Mensual Mantenimiento', 10.00, NOW());

-- Registro inicial de Visitas de prueba (para el socio Carlos Mendoza)
INSERT INTO `visitas` (`socios_id`, `fecha_entrada`) VALUES
(1, NOW()),
(1, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(1, DATE_SUB(NOW(), INTERVAL 5 DAY));
