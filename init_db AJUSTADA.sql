-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-11-2025 a las 04:53:45
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `init_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id_cita` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `id_medico` int(11) NOT NULL,
  `id_sesion` int(11) DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `duracion_min` int(11) DEFAULT 60,
  `estado` enum('PENDIENTE','CONFIRMADA','ATENDIDA','REPROGRAMADA','CANCELADA','VENCIDA','NO_ASISTE') DEFAULT 'PENDIENTE',
  `costo` decimal(10,2) DEFAULT 700.00,
  `probono` tinyint(1) DEFAULT 0,
  `observaciones` text DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id_cita`, `id_paciente`, `id_medico`, `id_sesion`, `fecha`, `duracion_min`, `estado`, `costo`, `probono`, `observaciones`, `creado_en`) VALUES
(3, 1, 3, 7, '0000-00-00 00:00:00', 60, 'CANCELADA', 700.00, 0, NULL, '2025-11-16 03:36:21'),
(4, 1, 3, 7, '0000-00-00 00:00:00', 60, 'CANCELADA', 700.00, 0, NULL, '2025-11-16 03:43:11'),
(5, 1, 3, 7, '0000-00-00 00:00:00', 60, 'CANCELADA', 700.00, 0, NULL, '2025-11-16 03:46:32'),
(6, 1, 3, 7, '0000-00-00 00:00:00', 60, 'CANCELADA', 700.00, 0, NULL, '2025-11-16 03:48:39'),
(7, 1, 3, 11, '0000-00-00 00:00:00', 60, 'CANCELADA', 700.00, 0, NULL, '2025-11-16 04:33:41'),
(8, 1, 3, 12, '0000-00-00 00:00:00', 60, 'CANCELADA', 700.00, 0, NULL, '2025-11-16 07:52:38'),
(9, 1, 3, 15, '0000-00-00 00:00:00', 60, 'CANCELADA', 700.00, 0, NULL, '2025-11-16 08:49:47'),
(10, 1, 3, 7, '0000-00-00 00:00:00', 60, 'CANCELADA', 700.00, 0, NULL, '2025-11-16 09:16:37'),
(11, 1, 3, 12, '0000-00-00 00:00:00', 60, 'CANCELADA', 700.00, 0, NULL, '2025-11-16 09:38:04'),
(12, 1, 3, 7, '0000-00-00 00:00:00', 60, 'CANCELADA', 700.00, 0, NULL, '2025-11-16 10:01:49'),
(13, 1, 3, 8, '0000-00-00 00:00:00', 60, 'CANCELADA', 700.00, 0, NULL, '2025-11-16 10:27:19'),
(14, 1, 3, 7, '0000-00-00 00:00:00', 60, 'CANCELADA', 700.00, 0, NULL, '2025-11-16 10:31:54'),
(15, 1, 3, 7, '0000-00-00 00:00:00', 60, 'CANCELADA', 700.00, 0, NULL, '2025-11-16 10:35:25'),
(16, 1, 3, 14, '0000-00-00 00:00:00', 60, 'CANCELADA', 700.00, 0, NULL, '2025-11-16 22:42:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `clave` varchar(100) NOT NULL,
  `valor` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`clave`, `valor`) VALUES
('duracion_minima', '60'),
('valor_consulta', '700');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expedientes`
--

CREATE TABLE `expedientes` (
  `id_expediente` int(11) NOT NULL,
  `id_cita` int(11) NOT NULL,
  `diagnostico` text DEFAULT NULL,
  `tratamiento` text DEFAULT NULL,
  `prescripciones` text DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id_factura` int(11) NOT NULL,
  `id_cita` int(11) NOT NULL,
  `metodo_pago` enum('EFECTIVO','TARJETA','MEDICO_PAGA') NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `idiomas`
--

CREATE TABLE `idiomas` (
  `id_idioma` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `idiomas`
--

INSERT INTO `idiomas` (`id_idioma`, `codigo`, `nombre`) VALUES
(1, 'es', 'Español'),
(2, 'en', 'English');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id_log` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `accion` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `ip_origen` varchar(45) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicos`
--

CREATE TABLE `medicos` (
  `id_medico` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `especialidad` varchar(100) NOT NULL,
  `disponible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `medicos`
--

INSERT INTO `medicos` (`id_medico`, `id_usuario`, `especialidad`, `disponible`) VALUES
(3, 2, 'Medicina General', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id_paciente` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `tipo_documento` enum('DNI','CARNET','PASAPORTE') NOT NULL,
  `numero_documento` varchar(50) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id_paciente`, `id_usuario`, `tipo_documento`, `numero_documento`, `nombre`, `fecha_nacimiento`, `telefono`, `direccion`, `correo`, `activo`) VALUES
(1, 3, 'DNI', '12345678', 'Juan Pérez', '1990-01-01', '99887766', 'Tegucigalpa', 'paciente@test.com', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre`) VALUES
(1, 'Administrador'),
(3, 'Encargada'),
(2, 'Medico'),
(4, 'paciente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones`
--

CREATE TABLE `sesiones` (
  `id_sesion` int(11) NOT NULL,
  `id_medico` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `fecha_sesion` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `duracion_minutos` int(11) DEFAULT 60,
  `cupo_maximo` int(11) DEFAULT 1,
  `descripcion` text DEFAULT NULL,
  `estado` enum('activa','cancelada','completada') DEFAULT 'activa',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sesiones`
--

INSERT INTO `sesiones` (`id_sesion`, `id_medico`, `titulo`, `fecha_sesion`, `hora_inicio`, `duracion_minutos`, `cupo_maximo`, `descripcion`, `estado`, `fecha_creacion`) VALUES
(7, 3, 'Consulta General', '2025-11-16', '09:00:00', 60, 1, 'Consultas generales de medicina familiar', 'activa', '2025-11-16 00:08:48'),
(8, 3, 'Consulta General', '2025-11-16', '10:00:00', 60, 1, 'Consultas generales de medicina familiar', 'activa', '2025-11-16 00:08:48'),
(9, 3, 'Consulta General', '2025-11-16', '11:00:00', 60, 1, 'Consultas generales de medicina familiar', 'activa', '2025-11-16 00:08:48'),
(10, 3, 'Consulta Especializada', '2025-11-17', '14:00:00', 90, 1, 'Consultas especializadas', 'activa', '2025-11-16 00:08:48'),
(11, 3, 'Consulta General', '2025-11-18', '09:00:00', 60, 1, 'Consulta matutina', 'activa', '2025-11-16 00:08:48'),
(12, 3, 'Consulta General', '2025-11-19', '15:00:00', 60, 1, 'Consulta vespertina', 'activa', '2025-11-16 00:08:48'),
(13, 3, 'Consulta General', '2025-11-17', '09:00:00', 60, 1, 'Consulta del 17', 'activa', '2025-11-16 08:49:15'),
(14, 3, 'Consulta General', '2025-11-18', '09:00:00', 60, 1, 'Consulta del 18', 'activa', '2025-11-16 08:49:15'),
(15, 3, 'Consulta General', '2025-11-19', '09:00:00', 60, 1, 'Consulta del 19', 'activa', '2025-11-16 08:49:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traducciones`
--

CREATE TABLE `traducciones` (
  `id_traduccion` int(11) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `id_idioma` int(11) NOT NULL,
  `texto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `contrasena_hash` varchar(255) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `contrasena_hash`, `id_rol`, `activo`, `fecha_creacion`) VALUES
(1, 'María González', 'doctor@example.com', '123', 2, 1, '2025-11-15 04:00:13'),
(2, 'Miguel Rodríguez', 'juan@example.com', '123', 2, 1, '2025-11-15 04:32:30'),
(3, 'Juan Pérez', 'paciente@test.com', '123456', 3, 1, '2025-11-15 06:25:24'),
(4, 'Admin Sistema', 'admin@pumacare.com', 'admin123', 1, 1, '2025-11-15 19:27:27'),
(5, 'Carlos Martínez', 'drmartinez@pumacare.com', '321', 2, 1, '2025-11-16 00:02:28'),
(6, 'María Encargada', 'encargada@test.com', '000', 3, 1, '2025-11-17 03:13:10');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id_cita`),
  ADD KEY `id_paciente` (`id_paciente`),
  ADD KEY `id_medico` (`id_medico`),
  ADD KEY `idx_sesion` (`id_sesion`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`clave`);

--
-- Indices de la tabla `expedientes`
--
ALTER TABLE `expedientes`
  ADD PRIMARY KEY (`id_expediente`),
  ADD KEY `id_cita` (`id_cita`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id_factura`),
  ADD KEY `id_cita` (`id_cita`);

--
-- Indices de la tabla `idiomas`
--
ALTER TABLE `idiomas`
  ADD PRIMARY KEY (`id_idioma`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD PRIMARY KEY (`id_medico`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id_paciente`),
  ADD UNIQUE KEY `tipo_documento` (`tipo_documento`,`numero_documento`),
  ADD KEY `fk_paciente_usuario` (`id_usuario`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD PRIMARY KEY (`id_sesion`),
  ADD KEY `idx_medico_fecha` (`id_medico`,`fecha_sesion`),
  ADD KEY `idx_fecha` (`fecha_sesion`),
  ADD KEY `idx_estado` (`estado`);

--
-- Indices de la tabla `traducciones`
--
ALTER TABLE `traducciones`
  ADD PRIMARY KEY (`id_traduccion`),
  ADD UNIQUE KEY `clave` (`clave`,`id_idioma`),
  ADD KEY `id_idioma` (`id_idioma`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `expedientes`
--
ALTER TABLE `expedientes`
  MODIFY `id_expediente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `idiomas`
--
ALTER TABLE `idiomas`
  MODIFY `id_idioma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `medicos`
--
ALTER TABLE `medicos`
  MODIFY `id_medico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  MODIFY `id_sesion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `traducciones`
--
ALTER TABLE `traducciones`
  MODIFY `id_traduccion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`id_medico`) REFERENCES `medicos` (`id_medico`),
  ADD CONSTRAINT `citas_ibfk_3` FOREIGN KEY (`id_sesion`) REFERENCES `sesiones` (`id_sesion`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_citas_sesion` FOREIGN KEY (`id_sesion`) REFERENCES `sesiones` (`id_sesion`) ON DELETE SET NULL;

--
-- Filtros para la tabla `expedientes`
--
ALTER TABLE `expedientes`
  ADD CONSTRAINT `expedientes_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`);

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`);

--
-- Filtros para la tabla `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD CONSTRAINT `medicos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `fk_paciente_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD CONSTRAINT `sesiones_ibfk_1` FOREIGN KEY (`id_medico`) REFERENCES `medicos` (`id_medico`) ON DELETE CASCADE;

--
-- Filtros para la tabla `traducciones`
--
ALTER TABLE `traducciones`
  ADD CONSTRAINT `traducciones_ibfk_1` FOREIGN KEY (`id_idioma`) REFERENCES `idiomas` (`id_idioma`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
