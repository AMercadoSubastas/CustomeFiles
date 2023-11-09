-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-11-2018 a las 12:52:20
-- Versión del servidor: 5.6.21
-- Versión de PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `amremate`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipcomp`
--

CREATE TABLE IF NOT EXISTS `tipcomp` (
`codnum` int(11) NOT NULL COMMENT 'Código autonumérico',
  `descripcion` varchar(50) COLLATE latin1_general_ci NOT NULL COMMENT 'Descripción',
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Activo?(S/N)',
  `esfactura` tinyint(1) DEFAULT NULL COMMENT 'Es Factura? (Si/No)',
  `esprovedor` varchar(1) COLLATE latin1_general_ci DEFAULT NULL,
  `codafip` int(9) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=86 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Volcado de datos para la tabla `tipcomp`
--

INSERT INTO `tipcomp` (`codnum`, `descripcion`, `activo`, `esfactura`, `esprovedor`, `codafip`) VALUES
(66, 'Retenciones SICORE - Impto. a las Ganancias', 1, NULL, NULL, 217),
(67, 'Retenciones Contrib. Seg. Social (SUSS)', 1, NULL, NULL, 353),
(68, 'Retenciones SICORE - Imp. al Valor Agregado (IVA)', 1, NULL, NULL, 767),
(69, 'Retenciones II.BB. Capital Federal', 1, NULL, NULL, 901),
(70, 'Retenciones II.BB. Buenos Aires', 1, NULL, NULL, 902),
(71, 'Retenciones II.BB. Córdoba', 1, NULL, NULL, 904),
(72, 'Retenciones II.BB. Corrientes', 1, NULL, NULL, 905),
(73, 'Retenciones II.BB. Chubut', 1, NULL, NULL, 907),
(74, 'Retenciones II.BB. Entre Ríos', 1, NULL, NULL, 908),
(75, 'Retenciones II.BB. La Pampa', 1, NULL, NULL, 911),
(76, 'Retenciones II.BB. La Rioja', 1, NULL, NULL, 912),
(77, 'Retenciones II.BB. Mendoza', 1, NULL, NULL, 913),
(78, 'Retenciones II.BB. Neuquén', 1, NULL, NULL, 915),
(79, 'Retenciones II.BB. Río Negro', 1, NULL, NULL, 916),
(80, 'Retenciones II.BB. Salta', 1, NULL, NULL, 917),
(81, 'Retenciones II.BB. San Juan', 1, NULL, NULL, 918);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tipcomp`
--
ALTER TABLE `tipcomp`
 ADD PRIMARY KEY (`codnum`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tipcomp`
--
ALTER TABLE `tipcomp`
MODIFY `codnum` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Código autonumérico',AUTO_INCREMENT=86;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
