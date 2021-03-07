

-- Adminer 4.7.8 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE DATABASE `clients` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci */;
USE `clients`;

CREATE TABLE `client` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_czech_ci NOT NULL,
  `ico` varchar(32) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `dic` varchar(32) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `number` varchar(8) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `street_num` varchar(64) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `city` varchar(64) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `zip` varchar(6) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `email` varchar(64) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `mobile` varchar(32) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `phone` varchar(32) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `fax` varchar(32) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `website` varchar(200) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `contact_person` varchar(32) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `note` varchar(256) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_last_update` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

INSERT INTO `client` (`id`, `name`, `ico`, `dic`, `number`, `street_num`, `city`, `zip`, `email`, `mobile`, `phone`, `fax`, `website`, `contact_person`, `note`, `date_created`, `date_last_update`) VALUES
(1,	'Peter Jurek',	'454533345',	'',	'',	'Osvoboditelů 1810',	'Uherský Brod',	'688 01',	'peter.jurek@gmail.com',	'+420774287648',	'',	'',	'',	'',	'',	NULL,	NULL),
(2,	'Alena Jureková',	'64546484',	'',	'',	'Osvoboditelů',	'Uherský Brod',	'688 01',	'alena.vernusova@gmail.com',	'',	'',	'',	'',	'',	'',	NULL,	NULL),
(3,	'Moravia Cans, a.s.',	'',	'',	'',	'Tovární 532',	'Bojkovice',	'687 71',	'peter.jurek@gmail.com',	'',	'',	'',	'',	'',	'',	NULL,	NULL),
(4,	'Rastislav Jurek',	'464564566',	'',	'',	'Osvoboditelů 1810',	'Uherský Brod',	'688 01',	'peter.jurek@gmail.com',	'',	'',	'',	'',	'',	'',	NULL,	NULL);

CREATE TABLE `client_person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(10) unsigned NOT NULL,
  `name` varchar(64) COLLATE utf8_czech_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Pracovní pozice ve firmě',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `task` (`id`, `label`, `is_done`) VALUES
(8,	'eco',	0),
(11,	'Rohlíky hoja',	0),
(12,	'hijas',	0),
(13,	'olivy12',	0),
(14,	'popo',	0),
(16,	'Rohlíky 6',	0),
(17,	'olivy9',	0),
(18,	'gggttt',	0);

-- 2021-03-07 07:53:00
