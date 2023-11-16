-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2023 at 09:02 AM
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

-- --------------------------------------------------------

--
-- Table structure for table `contacto`
--

CREATE TABLE `contacto` (
  `nombre` varchar(40) NOT NULL,
  `empresa` varchar(25) DEFAULT NULL,
  `email` varchar(75) DEFAULT NULL,
  `telefono` bigint(20) DEFAULT NULL,
  `num_cotizacion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `domicilio`
--

CREATE TABLE `domicilio` (
  `id_domicilio` int(11) NOT NULL,
  `pais` varchar(20) DEFAULT NULL,
  `Estado` varchar(45) DEFAULT NULL,
  `extenciion` char(1) DEFAULT NULL,
  `codigo_postal` int(6) DEFAULT NULL,
  `rfc` varchar(13) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `etiqueta`
--

CREATE TABLE `etiqueta` (
  `id_forma` int(11) NOT NULL,
  `tipo_forma` varchar(20) DEFAULT NULL,
  `medida_ancho` smallint(6) DEFAULT NULL,
  `medida_alto` smallint(6) DEFAULT NULL,
  `medida_diametro` smallint(6) DEFAULT NULL,
  `medida_circunferencia` smallint(6) DEFAULT NULL,
  `aplicacion` varchar(50) DEFAULT NULL,
  `material` varchar(30) DEFAULT NULL,
  `forma_aplicacion` int(11) DEFAULT NULL,
  `cantidad_de_colores` tinyint(4) DEFAULT NULL,
  `nombre_colores` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `venta`
--

CREATE TABLE `venta` (
  `num_cotizacion` int(11) NOT NULL,
  `id_domicilio` int(11) DEFAULT NULL,
  `id_forma` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `disenio` varchar(255) DEFAULT NULL,
  `comentarios` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`nombre`),
  ADD KEY `num_cotizacion` (`num_cotizacion`);

--
-- Indexes for table `domicilio`
--
ALTER TABLE `domicilio`
  ADD PRIMARY KEY (`id_domicilio`);

--
-- Indexes for table `etiqueta`
--
ALTER TABLE `etiqueta`
  ADD PRIMARY KEY (`id_forma`);

--
-- Indexes for table `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`num_cotizacion`),
  ADD KEY `id_domicilio` (`id_domicilio`),
  ADD KEY `id_forma` (`id_forma`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `domicilio`
--
ALTER TABLE `domicilio`
  MODIFY `id_domicilio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `etiqueta`
--
ALTER TABLE `etiqueta`
  MODIFY `id_forma` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contacto`
--
ALTER TABLE `contacto`
  ADD CONSTRAINT `contacto_ibfk_1` FOREIGN KEY (`num_cotizacion`) REFERENCES `venta` (`num_cotizacion`);

--
-- Constraints for table `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`id_domicilio`) REFERENCES `domicilio` (`id_domicilio`),
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`id_forma`) REFERENCES `etiqueta` (`id_forma`);
COMMIT;


DROP USER 'Admin'@'localhost';
DROP USER 'Usuario'@'localhost';


CREATE USER 'Admin'@'localhost' IDENTIFIED BY '123';
GRANT ALL PRIVILEGES ON *.* TO 'Admin'@'localhost';
CREATE USER 'Usuario'@'localhost' IDENTIFIED BY '2410';
GRANT INSERT, UPDATE, DELETE ON *.* TO 'Usuario'@'localhost';
FLUSH PRIVILEGES;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */; 
