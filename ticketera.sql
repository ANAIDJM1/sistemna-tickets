-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-12-2020 a las 14:50:27
-- Versión del servidor: 10.4.14-MariaDB
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ticketera`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oficina`
--

CREATE TABLE `oficina` (
  `Id_oficina` int(5) NOT NULL,
  `nombreofi` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `aforo` int(3) NOT NULL,
  `Responsable` varchar(100) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `Obs` varchar(500) COLLATE utf8mb4_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `oficina`
--

INSERT INTO `oficina` (`Id_oficina`, `nombreofi`, `aforo`, `Responsable`, `Obs`) VALUES
(1, 'SIAGI', 30, NULL, NULL),
(2, 'Mesa de Partes', 80, NULL, NULL),
(3, 'Secretaria General', 20, NULL, NULL),
(4, 'Tesoreria', 20, NULL, NULL),
(5, 'Boletas', 30, NULL, NULL),
(6, 'Personal', 20, NULL, NULL),
(7, 'Remuneraciones', 40, NULL, NULL),
(8, 'Asesoria Juridica', 20, NULL, NULL),
(9, 'Abastecimientos', 10, NULL, NULL),
(10, 'Escalafon', 20, NULL, NULL),
(11, 'Gestion Institucional', 20, NULL, NULL),
(12, 'OCI', 10, NULL, NULL),
(13, 'Contabilidad', 15, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket_e`
--

CREATE TABLE `ticket_e` (
  `ID` int(11) NOT NULL,
  `DNI_person` int(8) NOT NULL,
  `mail` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `nombre_persona` varchar(200) COLLATE utf8mb4_spanish_ci NOT NULL,
  `telf` int(9) NOT NULL,
  `fecha` date NOT NULL,
  `id_ofi` int(5) NOT NULL,
  `hora_fecha_atencion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `ticket_e`
--

INSERT INTO `ticket_e` (`ID`, `DNI_person`, `mail`, `nombre_persona`, `telf`, `fecha`, `id_ofi`, `hora_fecha_atencion`) VALUES
(1, 46969741, 'anaidjm1@gmail.com', 'Anaid Jimenez Moreano', 930225376, '2020-12-09', 6, '2020-12-10 09:00:00'),
(15, 46969742, 'sdsdds@gmail.com', '$sdsds', 123456789, '2020-12-09', 2, '2020-12-10 09:15:00'),
(31, 46969745, 'micki_cone@hotmail.com', 'asdas', 930225376, '2020-12-09', 5, '0000-00-00 00:00:00'),
(32, 25304167, 'dgamarraoz_@hotmail.com', 'ejemplo', 930225376, '2020-12-09', 7, '0000-00-00 00:00:00'),
(33, 25304168, 'dgamarraoz_@hotmail.com', 'ejemplo', 930225376, '2020-12-09', 7, '0000-00-00 00:00:00'),
(34, 25304166, 'dgamarraoz_@hotmail.com', 'ejemplo', 930225376, '2020-12-09', 7, '0000-00-00 00:00:00'),
(36, 25304162, 'dgamarraoz_@hotmail.com', 'ejemplo', 930225376, '2020-12-09', 7, '0000-00-00 00:00:00'),
(38, 46969742, 'nileve20@hotmail.com', 'ejemplo', 930225376, '2020-12-10', 8, '1969-12-31 19:33:40');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `oficina`
--
ALTER TABLE `oficina`
  ADD PRIMARY KEY (`Id_oficina`);

--
-- Indices de la tabla `ticket_e`
--
ALTER TABLE `ticket_e`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `id_ofi` (`id_ofi`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `oficina`
--
ALTER TABLE `oficina`
  MODIFY `Id_oficina` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `ticket_e`
--
ALTER TABLE `ticket_e`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ticket_e`
--
ALTER TABLE `ticket_e`
  ADD CONSTRAINT `ticket_e_ibfk_1` FOREIGN KEY (`id_ofi`) REFERENCES `oficina` (`Id_oficina`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
