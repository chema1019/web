-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-07-2025 a las 22:10:38
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `registro_nomina`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bajas_nomina`
--

CREATE TABLE `bajas_nomina` (
  `id` int(11) NOT NULL,
  `id_conductor` int(11) NOT NULL,
  `fecha_baja` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductores`
--

CREATE TABLE `conductores` (
  `id` int(11) NOT NULL,
  `t_documento` varchar(12) NOT NULL,
  `n_documento` varchar(12) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido_paterno` varchar(45) NOT NULL,
  `apellido_materno` varchar(45) NOT NULL,
  `licencia_conducir` varchar(10) NOT NULL,
  `categoria` varchar(25) NOT NULL,
  `fecha_expedicion` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `estado` enum('Vigente','Vencido','Baja') NOT NULL DEFAULT 'Vigente',
  `foto` varchar(50) NOT NULL,
  `id_record` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `id_vpago` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `conductores`
--

INSERT INTO `conductores` (`id`, `t_documento`, `n_documento`, `nombre`, `apellido_paterno`, `apellido_materno`, `licencia_conducir`, `categoria`, `fecha_expedicion`, `fecha_vencimiento`, `estado`, `foto`, `id_record`, `id_empresa`, `id_vpago`) VALUES
(1, 'DNI', '12345678', 'CARLOS', 'RAMIREZ', 'LOPEZ', 'A12345678', 'A-I', '2020-01-01', '2026-01-01', 'Vigente', '20.png', 1, 1, 1),
(2, 'DNI', '22334455', 'Elena', 'Flores', 'Vargas', 'F22334455', 'A-IIIb', '2023-02-01', '2029-02-01', 'Vigente', '8.png', 6, 1, 1),
(3, 'DNI', '33445566', 'Jorge', 'Reyes', 'Gutierrez', 'G33445566', 'A-IIIc', '2020-06-18', '2026-06-18', 'Vigente', '6.png', 7, 1, 1),
(4, 'DNI', '44556677', 'Lucia', 'Mendoza', 'Quispe', 'H44556677', 'B-IIa', '2022-08-14', '2028-08-14', 'Vigente', '9.png', 8, 1, 3),
(5, 'DNI', '18192031', 'Franco', 'Morales', 'Valdez', 'U18192031', 'A-IIIc', '2020-05-05', '2026-05-05', 'Vigente', '23.png', 21, 1, 3),
(6, 'DNI', '88990022', 'PAOLA', 'SILVA', 'CARRILLO', 'L88990022', 'A-IIb', '2021-12-01', '2027-12-01', 'Vigente', '13.png', 12, 1, 2),
(7, 'DNI', '55667788', 'ANA', 'MARTINEZ', 'RAMOS', 'D55667788', 'A-IIb', '2022-07-20', '2028-07-20', 'Vigente', '12.png', 4, 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `ruc` varchar(11) NOT NULL,
  `razon_social` varchar(150) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `autorizacion` varchar(100) NOT NULL,
  `fecha_vigencia` date NOT NULL,
  `documento` varchar(255) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id`, `ruc`, `razon_social`, `direccion`, `telefono`, `correo`, `autorizacion`, `fecha_vigencia`, `documento`, `estado`) VALUES
(1, '20613980645', 'TRANSPORTES ALTO PERU E.I.R.L.', 'MZ F LOTE 11 AA.HH SANTA SOFIA', '988421619', 'wiltoncr1303@gmail.com', 'RGR N 000365-2025-GRLL-GGR-GRTC', '2035-05-07', 'Assets/documentos/686381c98d3ae_RESOLUCION GERENCIAL REGIONAL-000483-2025-GRLL-GGR-GRTC-Trujillo-2025.pdf', 1),
(2, '20604447705', 'TURISMO EXPRESS SEÑOR DE LOS MILAGROS S.A.C.', 'CALLA ALAMEDA MZ°8 LOTE11, URB, VICENTE', '985555545', 'turismoexpress@gmail.com', 'RGR N 000418-2025-GRLL-GGR-GRTC', '2029-06-20', 'Assets/documentos/68640f878805c_RESOLUCION GERENCIAL REGIONAL-000483-2025-GRLL-GGR-GRTC-Trujillo-2025.pdf', 1),
(3, '20613908308', 'SERVICIOS DE TRANSPORTE Y TURISMO HERMANOS ROSAS S.A.C.', 'MZ 2 LT.2 P.J NN, ASENTAMIENTO HUMANO LA LIBERTAD', '985555421', 'serviciostransp@gmail.com', 'RGR N 000424-2025-GRLL-GGR-GRTC', '2035-06-01', 'Assets/documentos/6864113f810f9_RESOLUCION GERENCIAL REGIONAL-000483-2025-GRLL-GGR-GRTC-Trujillo-2025.pdf', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id_log` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `accion` text DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `id_empresa` int(11) NOT NULL,
  `codigo_pago` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_pago` date NOT NULL,
  `recibo` varchar(45) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `id_empresa`, `codigo_pago`, `descripcion`, `monto`, `fecha_pago`, `recibo`, `estado`) VALUES
(1, 1, '110', 'Registro de conductor', 37.50, '2025-07-01', '68638490c7a9b.pdf', 0),
(2, 1, '120', 'Baja de nómina', 100.00, '2025-06-30', '686406924b698.pdf', 1),
(3, 1, '110', 'Registro de conductor', 37.50, '2025-06-30', '68640a5965fa3.pdf', 0),
(4, 1, '110', 'Registro de conductor', 37.50, '2025-07-16', '68640b324f059.pdf', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `record_conductores`
--

CREATE TABLE `record_conductores` (
  `id` int(11) NOT NULL,
  `t_documento` varchar(12) NOT NULL,
  `n_documento` varchar(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido_paterno` varchar(100) NOT NULL,
  `apellido_materno` varchar(15) NOT NULL,
  `licencia_conducir` varchar(10) NOT NULL,
  `categoria` varchar(10) NOT NULL,
  `fecha_expedicion` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `estado` enum('vigente','vencido') NOT NULL DEFAULT 'vigente',
  `foto` varchar(25) NOT NULL DEFAULT 'adm.png',
  `fecha_verificacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `record_conductores`
--

INSERT INTO `record_conductores` (`id`, `t_documento`, `n_documento`, `nombre`, `apellido_paterno`, `apellido_materno`, `licencia_conducir`, `categoria`, `fecha_expedicion`, `fecha_vencimiento`, `estado`, `foto`, `fecha_verificacion`) VALUES
(1, 'DNI', '12345678', 'CARLOS', 'RAMIREZ', 'LOPEZ', 'A12345678', 'A-I', '2020-01-01', '2026-01-01', 'vigente', '20.png', '2025-06-19 10:00:00'),
(2, 'CE', '87654321', 'Maria', 'Gomez', 'Perez', 'B87654321', 'B-I', '2019-05-10', '2025-05-10', 'vigente', '11.png', '2025-06-19 10:10:00'),
(3, 'DNI', '11223344', 'Luis', 'Torres', 'Sanchez', 'C11223344', 'A-IIa', '2021-03-15', '2027-03-15', 'vigente', '2.png', '2025-06-19 10:20:00'),
(4, 'DNI', '55667788', 'Ana', 'Martinez', 'Ramos', 'D55667788', 'A-IIb', '2022-07-20', '2028-07-20', 'vigente', '12.png', '2025-06-19 10:30:00'),
(5, 'DNI', '99887766', 'Pedro', 'Chavez', 'Delgado', 'E99887766', 'A-IIIa', '2018-11-25', '2024-11-25', 'vencido', '4.png', '2025-06-19 10:40:00'),
(6, 'DNI', '22334455', 'Elena', 'Flores', 'Vargas', 'F22334455', 'A-IIIb', '2023-02-01', '2029-02-01', 'vigente', '8.png', '2025-06-19 10:50:00'),
(7, 'DNI', '33445566', 'Jorge', 'Reyes', 'Gutierrez', 'G33445566', 'A-IIIc', '2020-06-18', '2026-06-18', 'vigente', '6.png', '2025-06-19 11:00:00'),
(8, 'DNI', '44556677', 'Lucia', 'Mendoza', 'Quispe', 'H44556677', 'B-IIa', '2022-08-14', '2028-08-14', 'vigente', '9.png', '2025-06-19 11:10:00'),
(9, 'DNI', '55667799', 'Victor', 'Lozano', 'Salazar', 'I55667799', 'B-IIb', '2021-01-12', '2027-01-12', 'vigente', '7.png', '2025-06-19 11:20:00'),
(10, 'DNI', '66778800', 'Rosa', 'Campos', 'Molina', 'J66778800', 'A-I', '2017-04-30', '2023-04-30', 'vencido', '22.png', '2025-06-19 11:30:00'),
(11, 'DNI', '77889911', 'Eduardo', 'Vega', 'Castro', 'K77889911', 'A-IIa', '2023-09-10', '2029-09-10', 'vigente', '17.png', '2025-06-19 11:40:00'),
(12, 'DNI', '88990022', 'Paola', 'Silva', 'Carrillo', 'L88990022', 'A-IIb', '2021-12-01', '2027-12-01', 'vigente', '13.png', '2025-06-19 11:50:00'),
(13, 'DNI', '99001133', 'Ricardo', 'Rojas', 'Ibarra', 'M99001133', 'A-IIIa', '2020-10-05', '2026-10-05', 'vigente', '14.png', '2025-06-19 12:00:00'),
(14, 'DNI', '10111223', 'Carmen', 'Aguilar', 'Montes', 'N10111223', 'A-IIIb', '2019-07-15', '2025-07-15', 'vigente', '20.png', '2025-06-19 12:10:00'),
(15, 'DNI', '12131425', 'Oscar', 'Benitez', 'Farfan', 'O12131425', 'A-IIIc', '2018-03-25', '2024-03-25', 'vencido', '16.png', '2025-06-19 12:20:00'),
(16, 'DNI', '13141526', 'Sandra', 'Paredes', 'Bravo', 'P13141526', 'B-I', '2022-06-01', '2028-06-01', 'vigente', '19.png', '2025-06-19 12:30:00'),
(17, 'DNI', '14151627', 'Javier', 'Cornejo', 'Mejia', 'Q14151627', 'A-IIa', '2020-09-20', '2026-09-20', 'vigente', '15.png', '2025-06-19 12:40:00'),
(18, 'DNI', '15161728', 'Diana', 'Tello', 'Rivas', 'R15161728', 'A-IIb', '2019-12-11', '2025-12-11', 'vigente', '18.png', '2025-06-19 12:50:00'),
(19, 'DNI', '16171829', 'Martin', 'Ortega', 'Zamora', 'S16171829', 'A-IIIa', '2021-08-01', '2027-08-01', 'vigente', '3.png', '2025-06-19 13:00:00'),
(20, 'DNI', '17181930', 'Nadia', 'Quinteros', 'Yupanqui', 'T17181930', 'A-IIIb', '2023-01-15', '2029-01-15', 'vigente', '5.png', '2025-06-19 13:10:00'),
(21, 'DNI', '18192031', 'Franco', 'Morales', 'Valdez', 'U18192031', 'A-IIIc', '2020-05-05', '2026-05-05', 'vigente', '23.png', '2025-06-19 13:20:00'),
(22, 'DNI', '19202132', 'Vanessa', 'Herrera', 'Gonzales', 'V19202132', 'B-I', '2018-02-10', '2024-02-10', 'vencido', '1.png', '2025-06-19 13:30:00'),
(23, 'DNI', '20212233', 'Kevin', 'Ibanez', 'Ortiz', 'W20212233', 'A-IIa', '2022-10-22', '2028-10-22', 'vigente', '25.png', '2025-06-19 13:40:00'),
(24, 'DNI', '21222334', 'Tatiana', 'Salas', 'Chavez', 'X21222334', 'A-I', '2023-11-11', '2029-11-11', 'vigente', '21.png', '2025-06-19 13:50:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `dni` varchar(8) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `apellido_paterno` varchar(20) NOT NULL,
  `apellido_materno` varchar(20) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `area` varchar(15) NOT NULL DEFAULT 'ATFSTT',
  `perfil` varchar(50) NOT NULL DEFAULT 'avatar.svg',
  `clave` varchar(100) NOT NULL,
  `rol` enum('Administrador','Usuario') NOT NULL DEFAULT 'Usuario',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `dni`, `nombre`, `apellido_paterno`, `apellido_materno`, `correo`, `telefono`, `direccion`, `area`, `perfil`, `clave`, `rol`, `fecha`, `estado`) VALUES
(1, 'admin', '72463144', 'Jorge Daniel', 'Rodriguez', 'Garcia', 'jdrodriguezg@ucvvirtual.edu.pe', '787878787', 'AndresBelaunde #812 BUENOS AIRES SUR', 'ATFSTT', 'avatar.svg', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 'Administrador', '2025-07-01 15:55:20', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `validacion_pago`
--

CREATE TABLE `validacion_pago` (
  `id` int(11) NOT NULL,
  `id_pago` int(11) NOT NULL,
  `expediente` varchar(50) NOT NULL,
  `fecha_expediente` date NOT NULL,
  `modalidad` varchar(50) NOT NULL,
  `n_conductores` varchar(25) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `validacion_pago`
--

INSERT INTO `validacion_pago` (`id`, `id_pago`, `expediente`, `fecha_expediente`, `modalidad`, `n_conductores`, `fecha_registro`, `estado`) VALUES
(1, 1, 'OTD0002025112074', '2025-06-30', 'Transporte en Autocolectivo', '0', '2025-07-01 01:51:00', 0),
(2, 4, 'OTD0002025112040', '2025-07-04', 'Transporte en Autocolectivo', '1', '2025-07-01 11:58:04', 1),
(3, 3, 'OTD0002025112558', '2025-07-01', 'Transporte en Autocolectivo', '0', '2025-07-01 12:46:42', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bajas_nomina`
--
ALTER TABLE `bajas_nomina`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_conductor` (`id_conductor`);

--
-- Indices de la tabla `conductores`
--
ALTER TABLE `conductores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_record` (`id_record`),
  ADD KEY `id_empresa` (`id_empresa`),
  ADD KEY `id_vpago` (`id_vpago`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ruc` (`ruc`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_empresa` (`id_empresa`) USING BTREE;

--
-- Indices de la tabla `record_conductores`
--
ALTER TABLE `record_conductores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `validacion_pago`
--
ALTER TABLE `validacion_pago`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pago` (`id_pago`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bajas_nomina`
--
ALTER TABLE `bajas_nomina`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conductores`
--
ALTER TABLE `conductores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `record_conductores`
--
ALTER TABLE `record_conductores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `validacion_pago`
--
ALTER TABLE `validacion_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bajas_nomina`
--
ALTER TABLE `bajas_nomina`
  ADD CONSTRAINT `bajas_nomina_ibfk_1` FOREIGN KEY (`id_conductor`) REFERENCES `conductores` (`id`);

--
-- Filtros para la tabla `conductores`
--
ALTER TABLE `conductores`
  ADD CONSTRAINT `conductores_ibfk_2` FOREIGN KEY (`id_record`) REFERENCES `record_conductores` (`id`),
  ADD CONSTRAINT `conductores_ibfk_3` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id`),
  ADD CONSTRAINT `conductores_ibfk_4` FOREIGN KEY (`id_vpago`) REFERENCES `validacion_pago` (`id`);

--
-- Filtros para la tabla `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id`);

--
-- Filtros para la tabla `validacion_pago`
--
ALTER TABLE `validacion_pago`
  ADD CONSTRAINT `validacion_pago_ibfk_1` FOREIGN KEY (`id_pago`) REFERENCES `pagos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
