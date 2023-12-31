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
  `password` varchar(25) DEFAULT NULL,
  `email` varchar(75) DEFAULT NULL,
  `telefono` bigint(20) DEFAULT NULL,
  `empresa` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacto`
--

INSERT INTO `contacto` (`id_contacto`, `user_name`, `password`, `email`, `telefono`, `empresa`) VALUES
(4, 'damian', '123', 'damian@ceti.mx', 3322052692, 'luxoflex'),
(5, 'Damian123', '123', 'damian@ceti.mx', 332205, 'luxoflex'),
(6, 'admin', '123', 'admin@gmail.com', 3322052692, 'luxoflex');

-- --------------------------------------------------------

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
(3, 'mexico', 'guadalajara', 'jalisco', 'pedro garcia C 290', 44948, '123', 5);

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
(6, 'cuadrada', 10, 10, 10, 'couche', 'mate', 'manual', 1, 'n/i', 'imagenes/', 5);

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
`medida_alto` smallint(6)
,`medida_ancho` smallint(6)
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
);

-- --------------------------------------------------------

--
-- Structure for view `vista_etiquetas`
--
DROP TABLE IF EXISTS `vista_etiquetas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_etiquetas`  AS SELECT `e`.`medida_alto` AS `medida_alto`, `e`.`medida_ancho` AS `medida_ancho`, `e`.`disenio` AS `disenio`, `c`.`user_name` AS `user_name` FROM (`etiqueta` `e` join `contacto` `c` on(`e`.`id_contacto` = `c`.`id_contacto`)) ;

-- --------------------------------------------------------

--
-- Structure for view `vista_ventas`
--
DROP TABLE IF EXISTS `vista_ventas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_ventas`  AS SELECT `venta`.`fecha` AS `fecha`, `contacto`.`user_name` AS `usuario`, `etiqueta`.`disenio` AS `diseño_etiqueta`, `domicilio`.`direccion` AS `direccion`, `domicilio`.`codigo_postal` AS `codigo_postal` FROM (((`venta` join `contacto` on(`venta`.`id_contacto` = `contacto`.`id_contacto`)) join `etiqueta` on(`venta`.`id_etiqueta` = `etiqueta`.`id_etiqueta`)) join `domicilio` on(`venta`.`id_domicilio` = `domicilio`.`id_domicilio`)) ;

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
  MODIFY `id_contacto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `domicilio`
--
ALTER TABLE `domicilio`
  MODIFY `id_domicilio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `etiqueta`
--
ALTER TABLE `etiqueta`
  MODIFY `id_etiqueta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `venta`
--
ALTER TABLE `venta`
  MODIFY `num_cotizacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
