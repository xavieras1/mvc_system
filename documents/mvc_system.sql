-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 27, 2013 at 12:49 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mvc_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `area`
--

CREATE TABLE IF NOT EXISTS `area` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `area`
--

INSERT INTO `area` (`id`, `nombre`, `descripcion`) VALUES
(2, 'Instruccion', 'Es el area encargada de la formacion integral de los emevecistas en la fe de la iglesia y espiritualidad sodalite.'),
(3, 'Apostolado', 'Area apostolica'),
(4, 'Espiritualidad', 'Espiritualidad'),
(5, 'Temporalidades', 'Tempo'),
(6, 'Comunicaciones', 'Comunicaciones');

-- --------------------------------------------------------

--
-- Table structure for table `cargo`
--

CREATE TABLE IF NOT EXISTS `cargo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `cargo`
--

INSERT INTO `cargo` (`id`, `nombre`, `descripcion`) VALUES
(5, 'Superadmin', 'SuperAdmin'),
(6, 'Encargado', 'Encargado'),
(7, 'Animador', 'Dirige una asociacion');

-- --------------------------------------------------------

--
-- Table structure for table `instancia_permanencia`
--

CREATE TABLE IF NOT EXISTS `instancia_permanencia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  `fecha_creacion` date DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `instancia_permanencia_id` int(11) DEFAULT NULL,
  `tipo_instancia_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `permisos`
--

CREATE TABLE IF NOT EXISTS `permisos` (
  `cargo_id` int(11) NOT NULL DEFAULT '0',
  `area_id` int(11) NOT NULL DEFAULT '0',
  `tipo_instancia_id` int(11) NOT NULL,
  `permiso` enum('ver','editar','nada') NOT NULL,
  `id_tipo_instancia` int(11) NOT NULL,
  `nivel` int(11) DEFAULT NULL,
  PRIMARY KEY (`cargo_id`,`area_id`,`tipo_instancia_id`,`permiso`,`id_tipo_instancia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permisos`
--

INSERT INTO `permisos` (`cargo_id`, `area_id`, `tipo_instancia_id`, `permiso`, `id_tipo_instancia`, `nivel`) VALUES
(5, 0, 0, '', 0, 1),
(5, 0, 0, 'ver', 13, 1),
(5, 0, 0, 'ver', 14, 1),
(5, 0, 0, 'ver', 15, 1),
(5, 0, 0, 'ver', 16, 1),
(5, 0, 0, 'editar', 17, 1),
(5, 0, 0, 'editar', 18, 1),
(5, 0, 0, 'editar', 19, 1),
(5, 0, 0, 'nada', 20, 1),
(6, 2, 0, '', 0, 3),
(6, 2, 0, 'ver', 13, 3),
(6, 2, 0, 'ver', 17, 3),
(6, 2, 0, 'ver', 18, 3),
(6, 2, 0, 'ver', 19, 3),
(6, 2, 0, 'ver', 20, 3),
(6, 2, 0, 'editar', 14, 3),
(6, 2, 0, 'editar', 15, 3),
(6, 2, 0, 'editar', 16, 3),
(6, 3, 13, '', 0, 3),
(6, 3, 13, 'ver', 17, 3),
(6, 3, 13, 'editar', 13, 3),
(6, 3, 13, 'editar', 14, 3),
(6, 3, 13, 'editar', 15, 3),
(6, 3, 13, 'editar', 16, 3),
(6, 3, 13, 'editar', 18, 3),
(6, 3, 13, 'nada', 19, 3),
(6, 3, 13, 'nada', 20, 3),
(7, 0, 13, '', 0, 4),
(7, 0, 13, 'ver', 14, 4),
(7, 0, 13, 'ver', 15, 4),
(7, 0, 13, 'ver', 16, 4),
(7, 0, 13, 'ver', 17, 4),
(7, 0, 13, 'ver', 18, 4),
(7, 0, 13, 'editar', 13, 4),
(7, 0, 13, 'nada', 19, 4),
(7, 0, 13, 'nada', 20, 4);

-- --------------------------------------------------------

--
-- Table structure for table `persona`
--

CREATE TABLE IF NOT EXISTS `persona` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `ciudad` enum('Santiago de Guayaquil','San Pablo de Manta','San Francisco de Quito') NOT NULL DEFAULT 'Santiago de Guayaquil',
  `sexo` enum('Hombre','Mujer') NOT NULL,
  `edad` int(11) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `domicilio` varchar(150) DEFAULT NULL,
  `nivel_estudio` varchar(20) DEFAULT NULL,
  `institucion` varchar(100) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `celular_claro` varchar(15) DEFAULT NULL,
  `celular_movistar` varchar(15) DEFAULT NULL,
  `pin` varchar(10) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `facebook` varchar(20) DEFAULT NULL,
  `twitter` varchar(20) DEFAULT NULL,
  `foto` varchar(80) DEFAULT NULL,
  `usuario` varchar(20) DEFAULT NULL,
  `contrasena` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `persona`
--

INSERT INTO `persona` (`id`, `nombre`, `apellido`, `ciudad`, `sexo`, `edad`, `fecha_nacimiento`, `domicilio`, `nivel_estudio`, `institucion`, `telefono`, `celular_claro`, `celular_movistar`, `pin`, `email`, `facebook`, `twitter`, `foto`, `usuario`, `contrasena`) VALUES
(1, 'Andrea', 'Simbana', 'Santiago de Guayaquil', 'Mujer', 23, '1990-11-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'andy', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `persona_cargo_area_instancia`
--

CREATE TABLE IF NOT EXISTS `persona_cargo_area_instancia` (
  `persona_id` int(11) NOT NULL,
  `cargo_id` int(11) NOT NULL DEFAULT '0',
  `tipo_instancia_id` varchar(45) DEFAULT NULL,
  `area_id` int(11) NOT NULL DEFAULT '0',
  `instancia_permanencia_id` int(11) NOT NULL DEFAULT '0',
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  PRIMARY KEY (`persona_id`,`cargo_id`,`area_id`,`instancia_permanencia_id`),
  KEY `fk_persona_cargo_area_instancia_tipo_instancia_idx` (`tipo_instancia_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `persona_cargo_area_instancia`
--

INSERT INTO `persona_cargo_area_instancia` (`persona_id`, `cargo_id`, `tipo_instancia_id`, `area_id`, `instancia_permanencia_id`, `fecha_inicio`, `fecha_fin`) VALUES
(1, 5, '', 0, 0, '2013-09-12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tipo_instancia`
--

CREATE TABLE IF NOT EXISTS `tipo_instancia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  `logo` varchar(300) DEFAULT NULL,
  `clasificacion` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `tipo_instancia`
--

INSERT INTO `tipo_instancia` (`id`, `nombre`, `descripcion`, `logo`, `clasificacion`) VALUES
(13, 'Agrupaciones Marianas Masculinas', 'grupo de hombres', 'C:fakepathfano.jpg', 'permanencia'),
(14, 'Rosario Comunitario', 'rezo del rosario en comunidad', '', 'despliegue'),
(15, 'Servicio Solidario', 'Ayuda a los demas', '', 'despliegue'),
(16, 'Jornada Espiritual', 'Formacion', '', 'despliegue'),
(17, 'Centros Apostolicos', 'centros de la ciudad', '', 'permanencia '),
(18, 'Campania Navidenia', 'Navidad es Jesus', '', 'despliegue'),
(19, 'Vivencia', 'Vivencia', '', 'despliegue'),
(20, 'Convivio', 'congreso de colegiales catolicos', '', 'despliegue');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
