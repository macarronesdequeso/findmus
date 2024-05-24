-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-05-2024 a las 01:46:59
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
-- Base de datos: `music`
--
CREATE DATABASE IF NOT EXISTS `music` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `music`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `albums`
--

DROP TABLE IF EXISTS `albums`;
CREATE TABLE `albums` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `composer` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `albums`
--

INSERT INTO `albums` (`id`, `name`, `composer`) VALUES
(-1, 'No Album', 'Unknown Composer');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `composer`
--

DROP TABLE IF EXISTS `composer`;
CREATE TABLE `composer` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dateBirth` date DEFAULT NULL,
  `dateDeath` date DEFAULT NULL,
  `bio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `composer`
--

INSERT INTO `composer` (`id`, `name`, `dateBirth`, `dateDeath`, `bio`) VALUES
(-1, 'Unknown Composer', NULL, NULL, 'No Bio Available'),
(0, 'Kevin MacLeod', '1972-09-28', NULL, 'Kevin MacLeod es un compositor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genre`
--

DROP TABLE IF EXISTS `genre`;
CREATE TABLE `genre` (
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `genre`
--

INSERT INTO `genre` (`name`) VALUES
('Blues'),
('Classical'),
('Country'),
('Electronic'),
('Funk'),
('Hip Hop'),
('Jazz'),
('Other / No Genre'),
('Pop'),
('Reggae'),
('Rock');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `songs`
--

DROP TABLE IF EXISTS `songs`;
CREATE TABLE `songs` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dateCreation` date DEFAULT NULL,
  `composer` varchar(100) NOT NULL,
  `album` varchar(100) NOT NULL,
  `genre` varchar(100) NOT NULL,
  `views` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `songs`
--

INSERT INTO `songs` (`id`, `name`, `dateCreation`, `composer`, `album`, `genre`, `views`) VALUES
(-1, 'No Song', NULL, 'Unknown Composer', 'No Album', 'Other / No Genre', 0),
(0, 'Sneaky Snitch', '2007-12-17', 'Kevin MacLeod', 'No Album', 'Other / No Genre', 5),
(1, 'Monkeys Spinning Monkeys', '2005-07-06', 'Kevin MacLeod', 'No Album', 'Other / No Genre', 9);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`id`),
  ADD KEY `composer` (`composer`),
  ADD KEY `name` (`name`) USING BTREE;

--
-- Indices de la tabla `composer`
--
ALTER TABLE `composer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`) USING BTREE;

--
-- Indices de la tabla `genre`
--
ALTER TABLE `genre`
  ADD KEY `name` (`name`) USING BTREE;

--
-- Indices de la tabla `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `composer` (`composer`),
  ADD KEY `album` (`album`),
  ADD KEY `genre` (`genre`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `songs`
--
ALTER TABLE `songs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `albums`
--
ALTER TABLE `albums`
  ADD CONSTRAINT `albums_ibfk_1` FOREIGN KEY (`composer`) REFERENCES `composer` (`name`);

--
-- Filtros para la tabla `songs`
--
ALTER TABLE `songs`
  ADD CONSTRAINT `songs_ibfk_1` FOREIGN KEY (`composer`) REFERENCES `composer` (`name`),
  ADD CONSTRAINT `songs_ibfk_2` FOREIGN KEY (`album`) REFERENCES `albums` (`name`),
  ADD CONSTRAINT `songs_ibfk_3` FOREIGN KEY (`genre`) REFERENCES `genre` (`name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
