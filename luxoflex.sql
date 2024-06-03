-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2023 at 08:19 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `luxoflex`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertarVenta` (IN `cantidad_venta` INT, IN `etiqueta_id` INT, IN `comentarios_venta` VARCHAR(255), IN `domicilio_id` INT, IN `contacto_id` INT)   BEGIN
    INSERT INTO venta ( cantidad, id_etiqueta, comentarios, id_domicilio, id_contacto, fecha) 
    VALUES (cantidad_venta, etiqueta_id, comentarios_venta, domicilio_id, contacto_id, current_timestamp());
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `contacto`
--

CREATE TABLE `contacto` (
  `id_contacto` int(11) NOT NULL,
  `user_name` varchar(40) NOT NULL,
  `password` varchar(64) DEFAULT NULL,
  `email` varchar(75) DEFAULT NULL,
  `telefono` bigint(20) DEFAULT NULL,
  `empresa` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacto`
--

UPDATE contacto SET password = SHA2(password, 256);

INSERT INTO `contacto` (`id_contacto`, `user_name`, `password`, `email`, `telefono`, `empresa`) VALUES
(4, 'Aldo', SHA2('123', 256), 'aldo@ceti.mx', 15551234567, 'luxoflex'),
(5, 'Damian', SHA2('123', 256), 'damian@ceti.mx', 15552345678, 'luxoflex'),
(6, 'Andres', SHA2('123', 256), 'andres@ceti.mx', 15553456789, 'luxoflex'),
(7, 'administrador', SHA2('123', 256), 'administrador@gmail.com', 15554567890, 'luxoflex');

/*
INSERT INTO `contacto` (`id_contacto`, `user_name`, `password`, `email`, `telefono`, `empresa`) VALUES
(4, 'Aldo', '123' , 'aldo@ceti.mx', 15551234567, 'luxoflex'),
(5, 'Damian', '123' , 'damian@ceti.mx', 15552345678, 'luxoflex'),
(6, 'Andres', '123' , 'andres@ceti.mx', 15553456789, 'luxoflex'),
(7, 'administrador', '123' , 'administrador@gmail.com', 15554567890, 'luxoflex');
-- --------------------------------------------------------
*/
--
-- Table structure for table `domicilio`
--

CREATE TABLE `domicilio` (
  `id_domicilio` int(11) NOT NULL,
  `pais` varchar(20) DEFAULT NULL,
  `ciudad` varchar(20) DEFAULT NULL,
  `Estado` varchar(55) DEFAULT NULL,
  `direccion` varchar(65) DEFAULT NULL,
  `codigo_postal` int(5) DEFAULT NULL,
  `rfc` varchar(13) DEFAULT NULL,
  `id_contacto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `domicilio`
--

INSERT INTO `domicilio` (`id_domicilio`, `pais`, `ciudad`, `Estado`, `direccion`, `codigo_postal`, `rfc`, `id_contacto`) VALUES
(1, 'Mexico', 'Mexico City', 'CDMX', 'Avenida Insurgentes 123', 11000, 'RFC123456AAA', 4),
(2, 'Mexico', 'Guadalajara', 'Jalisco', 'Calle Morelos 456', 44100, 'RFC123456BBB', 4),
(3, 'Mexico', 'Monterrey', 'Nuevo Leon', 'Calle Hidalgo 789', 64000, 'RFC123456CCC', 4),
(4, 'Mexico', 'Tijuana', 'Baja California', 'Calle Revolución 123', 22000, 'RFC123456DDD', 4),
(5, 'Mexico', 'Cancún', 'Quintana Roo', 'Avenida Bonampak 456', 77500, 'RFC123456EEE', 4),

(6, 'Mexico', 'Guadalajara', 'Jalisco', 'Avenida Vallarta 101', 45000, 'RFC654321AAA', 5),
(7, 'Mexico', 'Mexico City', 'CDMX', 'Calle Reforma 202', 11000, 'RFC654321BBB', 5),
(8, 'Mexico', 'Puebla', 'Puebla', 'Avenida Juarez 303', 72000, 'RFC654321CCC', 5),
(9, 'Mexico', 'Chihuahua', 'Chihuahua', 'Calle Victoria 101', 31000, 'RFC654321DDD', 5),
(10, 'Mexico', 'Aguascalientes', 'Aguascalientes', 'Avenida Lopez Mateos 202', 20000, 'RFC654321EEE', 5),

(11, 'Mexico', 'Toluca', 'Estado de Mexico', 'Calle Benito Juarez 404', 50000, 'RFC789012AAA', 6),
(12, 'Mexico', 'Guadalajara', 'Jalisco', 'Calle Independencia 505', 44100, 'RFC789012BBB', 6),
(13, 'Mexico', 'Queretaro', 'Queretaro', 'Avenida Constituyentes 606', 76000, 'RFC789012CCC', 6),
(14, 'Mexico', 'Merida', 'Yucatan', 'Calle 60 789', 97000, 'RFC789012DDD', 6),
(15, 'Mexico', 'Chihuahua', 'Chihuahua', 'Calle Victoria 101', 31000, 'RFC789012EEE', 6);

-- --------------------------------------------------------

--
-- Table structure for table `etiqueta`
--

CREATE TABLE `etiqueta` (
  `id_etiqueta` int(11) NOT NULL,
  `tipo_forma` varchar(20) DEFAULT NULL,
  `medida_ancho` smallint(6) DEFAULT NULL,
  `medida_alto` smallint(6) DEFAULT NULL,
  `medida_circunferencia` smallint(6) DEFAULT NULL,
  `material_etiqueta` varchar(30) DEFAULT NULL,
  `laminado` varchar(9) DEFAULT NULL,
  `material_aplicacion` varchar(30) DEFAULT NULL,
  `cantidad_de_colores` smallint(6) DEFAULT NULL,
  `colores` varchar(80) DEFAULT NULL,
  `disenio` varchar(255) DEFAULT NULL,
  `id_contacto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `etiqueta`
--

INSERT INTO `etiqueta` (`id_etiqueta`, `tipo_forma`, `medida_ancho`, `medida_alto`, `medida_circunferencia`, `material_etiqueta`, `laminado`, `material_aplicacion`, `cantidad_de_colores`, `colores`, `disenio`, `id_contacto`) VALUES
(1, 'redonda', 5, 5, 15, 'papel', 'brillante', 'manual', 3, 'rojo, azul, verde', 'imagenes/disenio1.png', 4),
(2, 'rectangular', 8, 12, 20, 'plastico', 'mate', 'automatico', 2, 'negro, blanco', 'imagenes/disenio2.png', 4),
(3, 'cuadrada', 10, 10, 30, 'couche', 'mate', 'manual', 4, 'naranja, amarillo, negro, blanco', 'imagenes/disenio3.png', 4),
(4, 'ovalada', 7, 5, 15, 'papel', 'brillante', 'manual', 2, 'azul, rojo', 'imagenes/disenio4.png', 5),
(5, 'cuadrada', 6, 6, 12, 'plastico', 'mate', 'automatico', 3, 'verde, amarillo, negro', 'imagenes/disenio5.png', 5),
(6, 'redonda', 9, 9, 18, 'couche', 'brillante', 'manual', 1, 'naranja', 'imagenes/disenio6.png', 5),
(7, 'rectangular', 12, 15, 24, 'papel', 'mate', 'automatico', 4, 'rojo, azul, verde, negro', 'imagenes/disenio7.png', 6),
(8, 'ovalada', 10, 7, 21, 'plastico', 'brillante', 'manual', 2, 'blanco, negro', 'imagenes/disenio8.png', 6),
(9, 'cuadrada', 8, 8, 16, 'couche', 'mate', 'automatico', 3, 'naranja, azul, blanco', 'imagenes/disenio9.png', 6);

-- --------------------------------------------------------

--
-- Table structure for table `venta`
--

CREATE TABLE `venta` (
  `num_cotizacion` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `id_etiqueta` int(11) NOT NULL,
  `comentarios` text DEFAULT NULL,
  `id_domicilio` int(11) NOT NULL,
  `id_contacto` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `etiqueta`
--

INSERT INTO `venta` (`num_cotizacion`, `cantidad`, `id_etiqueta`, `comentarios`, `id_domicilio`, `id_contacto`) VALUES
(1, 100, 3, 'Urgente', 1, 4),
(2, 200, 2, 'Regular', 5, 4),
(3, 150, 2, 'Prioritario', 3, 4),
(4, 120, 3, 'Urgente', 1, 4),
(5, 220, 3, 'Regular', 2, 4),
(6, 160, 1, 'Prioritario', 3, 4),
(7, 310, 2, 'Normal', 5, 4),
(8, 260, 2, 'Express', 5, 4),
(9, 190, 3, 'Regular', 1, 4),
(10, 230, 1, 'Urgente', 2, 4),

(11, 300, 6, 'Normal', 6, 5),
(12, 250, 5, 'Express', 8, 5),
(13, 180, 4, 'Regular', 9, 5),
(14, 210, 6, 'Prioritario', 9, 5),
(15, 220, 4, 'Urgente', 10, 5),
(16, 270, 6, 'Normal', 6, 5),
(17, 200, 4, 'Express', 7, 5),
(18, 150, 6, 'Prioritario', 9, 5),
(19, 240, 5, 'Urgente', 7, 5),
(20, 180, 4, 'Regular', 10, 5),

(21, 320, 7, 'Normal', 11, 6),
(22, 270, 9, 'Express', 15, 6),
(23, 210, 9, 'Prioritario', 11, 6),
(24, 250, 8, 'Urgente', 14, 6),
(25, 300, 8, 'Regular', 13, 6),
(26, 220, 7, 'Normal', 13, 6),
(27, 280, 8, 'Express', 14, 6),
(28, 200, 9, 'Urgente', 12, 6),
(29, 150, 8, 'Regular', 13, 6),
(30, 240, 9, 'Prioritario', 13, 6);


-- --------------------------------------------------------

--
-- Triggers `venta`
--
DELIMITER $$
CREATE TRIGGER `before_venta_insert` BEFORE INSERT ON `venta` FOR EACH ROW BEGIN
   SET NEW.fecha = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vista_etiquetas`
-- (See below for the actual view)
--
CREATE TABLE `vista_etiquetas` (
`id_etiqueta` int(11) NOT NULL
,`cantidad` int(11)
,`medida_alto` smallint(6)
,`medida_ancho` smallint(6)
,`medida_circunferencia` smallint(6)
,`disenio` varchar(255)
,`user_name` varchar(40)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vista_ventas`
-- (See below for the actual view)
--
CREATE TABLE `vista_ventas` (
`fecha` timestamp
,`usuario` varchar(40)
,`diseño_etiqueta` varchar(255)
,`direccion` varchar(65)
,`codigo_postal` int(5)
,`id_etiqueta` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vista_detalle_etiquetas`
-- (See below for the actual view)
--
CREATE TABLE `vista_detalle_etiquetas` (
`id_etiqueta` int(11) NOT NULL
,`tipo_forma` varchar(20)
,`material_etiqueta` varchar(30)
,`laminado` varchar(9)
,`material_aplicacion` varchar(30)
,`colores` varchar(80)
,`user_name` varchar(40)
);

-- --------------------------------------------------------

--
-- Structure for view `vista_etiquetas`
--
DROP TABLE IF EXISTS `vista_etiquetas`;

/*CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_etiquetas` AS SELECT e.id_etiqueta AS id_etiqueta, e.medida_alto AS medida_alto, e.medida_ancho AS medida_ancho, e.medida_circunferencia AS medida_circunferencia, e.disenio AS disenio, c.user_name AS user_name FROM etiqueta e JOIN contacto c ON e.id_contacto = c.id_contacto;
-- --------------------------------------------------------*/

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_etiquetas` AS SELECT e.id_etiqueta AS id_etiqueta, e.medida_alto AS medida_alto, e.medida_ancho AS medida_ancho, e.medida_circunferencia AS medida_circunferencia, e.disenio AS disenio, c.user_name AS user_name, COALESCE(SUM(v.cantidad), 0) AS total_cantidad FROM etiqueta e JOIN contacto c ON e.id_contacto = c.id_contacto LEFT JOIN venta v ON e.id_etiqueta = v.id_etiqueta GROUP BY e.id_etiqueta, e.medida_alto, e.medida_ancho, e.medida_circunferencia, e.disenio, c.user_name;

--
-- Structure for view `vista_ventas`
--
DROP TABLE IF EXISTS `vista_ventas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_ventas` AS SELECT v.fecha AS fecha, c.user_name AS usuario, e.disenio AS diseño_etiqueta, d.direccion AS direccion, d.codigo_postal AS codigo_postal, v.id_etiqueta AS id_etiqueta FROM venta v JOIN contacto c ON v.id_contacto = c.id_contacto JOIN etiqueta e ON v.id_etiqueta = e.id_etiqueta JOIN domicilio d ON v.id_domicilio = d.id_domicilio;
-- --------------------------------------------------------

--
-- Structure for view `vista_detalle_etiquetas`
--
DROP TABLE IF EXISTS `vista_detalle_etiquetas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_detalle_etiquetas` AS SELECT e.id_etiqueta AS id_etiqueta, e.tipo_forma AS tipo_forma, e.material_etiqueta AS material_etiqueta, e.laminado AS laminado, e.material_aplicacion AS material_aplicacion, e.colores AS colores, e.id_contacto AS id_contacto,c.user_name AS user_name FROM etiqueta e JOIN contacto c ON e.id_contacto = c.id_contacto;
-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`id_contacto`);

--
-- Indexes for table `domicilio`
--
ALTER TABLE `domicilio`
  ADD PRIMARY KEY (`id_domicilio`),
  ADD KEY `id_contacto` (`id_contacto`);

--
-- Indexes for table `etiqueta`
--
ALTER TABLE `etiqueta`
  ADD PRIMARY KEY (`id_etiqueta`),
  ADD KEY `id_contacto` (`id_contacto`);

--
-- Indexes for table `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`num_cotizacion`),
  ADD KEY `id_etiqueta` (`id_etiqueta`),
  ADD KEY `id_domicilio` (`id_domicilio`),
  ADD KEY `id_contacto` (`id_contacto`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacto`
--
ALTER TABLE `contacto`
  MODIFY `id_contacto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `domicilio`
--
ALTER TABLE `domicilio`
  MODIFY `id_domicilio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `etiqueta`
--
ALTER TABLE `etiqueta`
  MODIFY `id_etiqueta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `venta`
--
ALTER TABLE `venta`
  MODIFY `num_cotizacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `domicilio`
--
ALTER TABLE `domicilio`
  ADD CONSTRAINT `domicilio_ibfk_1` FOREIGN KEY (`id_contacto`) REFERENCES `contacto` (`id_contacto`);

--
-- Constraints for table `etiqueta`
--
ALTER TABLE `etiqueta`
  ADD CONSTRAINT `etiqueta_ibfk_1` FOREIGN KEY (`id_contacto`) REFERENCES `contacto` (`id_contacto`);

--
-- Constraints for table `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`id_domicilio`) REFERENCES `domicilio` (`id_domicilio`),
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`id_etiqueta`) REFERENCES `etiqueta` (`id_etiqueta`),
  ADD CONSTRAINT `venta_ibfk_3` FOREIGN KEY (`id_contacto`) REFERENCES `contacto` (`id_contacto`);

-- Admin

BEGIN;
DROP USER IF EXISTS 'administrador'@'localhost';
CREATE USER 'administrador'@'localhost' IDENTIFIED BY '123';
GRANT ALL PRIVILEGES ON *.* TO 'administrador'@'localhost' WITH GRANT OPTION;

DROP USER IF EXISTS 'Aldo'@'localhost';
CREATE USER 'Aldo'@'localhost' IDENTIFIED BY '123';
GRANT SELECT, INSERT, UPDATE, DELETE ON *.* TO 'Aldo'@'localhost';

DROP USER IF EXISTS 'Damian'@'localhost';
CREATE USER 'Damian'@'localhost' IDENTIFIED BY '123';
GRANT SELECT, INSERT, UPDATE, DELETE ON *.* TO 'Damian'@'localhost';

DROP USER IF EXISTS 'Andres'@'localhost';
CREATE USER 'Andres'@'localhost' IDENTIFIED BY '123';
GRANT SELECT, INSERT, UPDATE, DELETE ON *.* TO 'Andres'@'localhost';
FLUSH PRIVILEGES;
COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
