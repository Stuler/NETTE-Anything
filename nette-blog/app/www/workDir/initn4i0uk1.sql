SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE DATABASE `todos` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `todos`;

CREATE TABLE `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `task` (`id`, `label`, `is_done`) VALUES
(4,	'Rohlíky 787',	0),
(8,	'neco',	0),
(11,	'Rohlíky hoja',	0),
(12,	'hijas',	0),
(13,	'olivy',	0);