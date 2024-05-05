-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-05-2024 a las 16:04:46
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `composer`
--

CREATE TABLE `composer` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `dateBirth` date DEFAULT NULL,
  `dateDeath` date DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `imageLocation` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `composer`
--

INSERT INTO `composer` (`id`, `name`, `dateBirth`, `dateDeath`, `bio`, `imageLocation`) VALUES
(0, 'Kevin MacLeod', '1972-09-28', NULL, 'Kevin MacLeod es un compositor y productor musical estadounidense, conocido por su extensa colección de música de dominio público disponible en su sitio web incompetech.com. Su música ha sido utilizada en miles de proyectos, incluidos películas, videos de YouTube, videojuegos y más.', '0.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `songs`
--

CREATE TABLE `songs` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `dateCreation` date DEFAULT NULL,
  `composer` varchar(255) DEFAULT NULL,
  `id_album` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `songs`
--

INSERT INTO `songs` (`id`, `name`, `dateCreation`, `composer`, `id_album`, `views`) VALUES
(0, 'Sneaky Snitch', '2007-12-17', 'Kevin MacLeod', NULL, 0),
(1, 'Monkeys Spinning Monkeys', '2005-07-06', 'Kevin MacLeod', NULL, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `composer`
--
ALTER TABLE `composer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `composer` (`composer`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `composer`
--
ALTER TABLE `composer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `songs`
--
ALTER TABLE `songs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `songs`
--
ALTER TABLE `songs`
  ADD CONSTRAINT `songs_ibfk_1` FOREIGN KEY (`composer`) REFERENCES `composer` (`name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
