

-- Adminer 4.7.8 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP DATABASE IF EXISTS `clients`;
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
(1,	'Peter Jure',	'454533345',	'',	'',	'Osvoboditelů 1810',	'Uherský Brod',	'688 01',	'peter.jurek@gmail.com',	'+420774287648',	'',	'',	'',	'',	'',	NULL,	'2021-03-08 21:15:00'),
(2,	'Alena Jurekova',	'64546484',	'',	'',	'Osvoboditelů',	'Uherský Brod',	'688 01',	'alena.vernusova@gmail.com',	'',	'',	'',	'',	'',	'',	NULL,	'2021-03-08 21:15:38'),
(4,	'Rastislav Jurek',	'464564566',	'',	'',	'Osvoboditelů 1810',	'Uherský Brod',	'688 01',	'peter.jurek@gmail.com',	'',	'',	'',	'',	'',	'',	NULL,	NULL),
(10,	'Moravia Cans, a.s.',	'4645615313',	'',	'',	'Tovární 532',	'Bojkovice',	'687 71',	'peter.jurek@gmail.com',	'+421774287648',	'',	'',	'',	'',	'',	NULL,	NULL),
(11,	'nejaka firma',	'4165461',	'',	'',	'Osvoboditelů, 1810',	'Uherský Brod',	'688 01',	'alena.vernusova@gmail.com',	'777598185',	'',	'',	'',	'',	'',	NULL,	NULL),
(12,	'druha firma',	'48646513',	'',	'',	'Tovární 532',	'Bojkovice',	'687 71',	'peter.jurek@gmail.com',	'+421774287648',	'',	'',	'',	'',	'',	NULL,	NULL),
(13,	'druha firma',	'4865131',	'',	'',	'Tovární 532',	'Bojkovice',	'687 71',	'peter.jurek@gmail.com',	'+421774287648',	'',	'',	'',	'',	'',	NULL,	NULL),
(14,	'tretia firma',	'4564613',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	NULL,	NULL),
(15,	'dalsia firma',	'4646',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	'',	NULL,	NULL),
(16,	'Peter Jurek 2',	'',	'',	'',	'Osvoboditelů 1810',	'Uherský Brod',	'688 01',	'peter.jurek@gmail.com',	'+420774287648',	'',	'',	'',	'',	'',	NULL,	NULL);

CREATE TABLE `client_person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(10) unsigned NOT NULL,
  `name` varchar(64) COLLATE utf8_czech_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL COMMENT 'Pracovní pozice ve firmě',
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `client_person_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `client_person` (`id`, `client_id`, `name`, `email`, `phone`, `status`) VALUES
(2,	1,	'Alena Jureková',	'alena.vernusova@gmail.com',	'777598185',	''),
(3,	10,	'Alena Jureková',	'alena.vernusova@gmail.com',	'777598185',	''),
(4,	11,	'Peter Jurek8',	'peter.jurek@gmail.com',	'+421774287648',	''),
(5,	13,	'Moravia Cans, a.s.',	'peter.jurek@gmail.com',	'+421774287648',	''),
(6,	14,	'Alena Jureková6',	'alena.vernusova@gmail.com',	'777598185',	''),
(7,	14,	'Rastislav Jurek',	'peter.jurek@gmail.com',	'',	''),
(8,	14,	'nejaka firma',	'alena.vernusova@gmail.com',	'777598185',	''),
(9,	14,	'nejaka firma',	'alena.vernusova@gmail.com',	'777598185',	''),
(10,	15,	'Peter Jurek 2',	'peter.jurek@gmail.com',	'+420774287648',	'');

CREATE TABLE `file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `is_dir` tinyint(1) DEFAULT NULL,
  `name` varchar(200) COLLATE utf8mb4_czech_ci NOT NULL,
  `file_path` varchar(250) COLLATE utf8mb4_czech_ci DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `date_created` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `level` tinyint(8) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

INSERT INTO `file` (`id`, `parent_id`, `is_dir`, `name`, `file_path`, `size`, `date_created`, `level`) VALUES
(1,	NULL,	0,	'soubor1',	NULL,	NULL,	'2021-03-19 20:21:12',	1),
(2,	NULL,	0,	'soubor2',	NULL,	NULL,	'2021-03-19 20:21:12',	1),
(3,	NULL,	1,	'slozka1',	NULL,	NULL,	'2021-03-19 20:21:54',	1),
(4,	3,	0,	'soubor3',	NULL,	NULL,	'2021-03-19 20:21:12',	2),
(5,	3,	0,	'soubor4',	NULL,	NULL,	'2021-03-19 20:21:12',	2),
(6,	3,	1,	'slozka2',	NULL,	NULL,	'2021-03-19 20:21:54',	2),
(7,	NULL,	1,	'slozka3',	NULL,	NULL,	'2021-03-19 20:21:54',	1),
(8,	7,	0,	'soubor5',	NULL,	NULL,	'2021-03-19 20:21:12',	2),
(9,	NULL,	0,	'soubor6',	NULL,	NULL,	'2021-03-19 20:21:12',	1),
(10,	6,	0,	'soubor7',	NULL,	NULL,	'2021-03-19 20:21:12',	3),
(11,	NULL,	1,	'slozka15',	NULL,	NULL,	'2021-03-19 20:25:38',	1),
(48,	11,	0,	'ryba.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/ryba.png',	27638,	'2021-03-20 13:46:34',	2),
(49,	3,	0,	'nahoru.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/nahoru.png',	4983,	'2021-03-20 13:46:25',	2),
(50,	6,	0,	'ryba.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/ryba.png',	27638,	'2021-03-20 13:40:46',	2),
(51,	6,	0,	'dolu.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/dolu.png',	5012,	'2021-03-20 13:40:57',	2),
(52,	6,	0,	'kopec.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/kopec.png',	84882,	'2021-03-20 13:41:21',	2),
(53,	7,	0,	'ryba.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/ryba.png',	27638,	'2021-03-20 13:45:59',	2),
(54,	3,	0,	'nahoru.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/nahoru.png',	4983,	'2021-03-20 13:45:51',	2),
(68,	7,	0,	'rybnik.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/rybnik.png',	276715,	'2021-03-20 20:58:46',	2),
(69,	3,	0,	'les.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/les.png',	44607,	'2021-03-20 20:59:18',	2),
(70,	11,	0,	'ryba.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/ryba.png',	27638,	'2021-03-20 20:59:54',	2),
(71,	0,	0,	'rybnik.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/rybnik.png',	276715,	'2021-03-20 21:00:39',	0),
(72,	0,	1,	'složka 9',	NULL,	NULL,	'2021-03-21 18:55:44',	1),
(73,	72,	0,	'nahoru.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/nahoru.png',	4983,	'2021-03-21 18:55:52',	2),
(74,	7,	0,	'nahoru.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/nahoru.png',	4983,	'2021-03-21 18:56:01',	2),
(75,	0,	0,	'ryba.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/ryba.png',	27638,	'2021-03-21 18:56:09',	0),
(76,	6,	0,	'nahoru.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/nahoru.png',	4983,	'2021-03-21 20:01:38',	3),
(77,	0,	0,	'doleva.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/doleva.png',	4957,	'2021-03-21 20:01:57',	1),
(78,	7,	0,	'ryba.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/ryba.png',	27638,	'2021-03-21 20:02:06',	2),
(79,	6,	0,	'kopec.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/kopec.png',	84882,	'2021-03-21 20:02:13',	3),
(80,	3,	1,	'složka 9',	NULL,	NULL,	'2021-03-21 20:02:18',	1),
(81,	7,	1,	'složka 9',	NULL,	NULL,	'2021-03-21 20:02:23',	1),
(82,	80,	1,	'složka111',	NULL,	NULL,	'2021-03-21 20:02:31',	1),
(83,	11,	1,	'složka 9',	NULL,	NULL,	'2021-03-21 20:25:53',	1),
(84,	7,	1,	'složka111',	NULL,	NULL,	'2021-03-21 20:28:30',	2),
(85,	11,	1,	'složka111',	NULL,	NULL,	'2021-03-21 20:28:37',	2),
(86,	0,	1,	'slozka15',	NULL,	NULL,	'2021-03-21 20:28:45',	1),
(87,	82,	1,	'složka 9',	NULL,	NULL,	'2021-03-21 20:28:51',	2),
(88,	82,	0,	'rybnik.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/rybnik.png',	276715,	'2021-03-21 20:28:58',	2),
(89,	83,	0,	'les.png',	'C:\\WWW\\NETTE-Anything\\nette-blog\\app\\Models\\ProcessManagers/../../www/workDir/les.png',	44607,	'2021-03-21 20:29:05',	2),
(90,	86,	1,	'slozka15',	NULL,	NULL,	'2021-03-21 20:29:13',	2);

CREATE TABLE `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  `is_done` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `task` (`id`, `label`, `is_done`) VALUES
(8,	'eco',	0),
(11,	'Rohlíky hoja5',	0),
(12,	'hijas',	0),
(13,	'olivy12',	0),
(14,	'popo',	0),
(16,	'Rohlíky 6',	0),
(17,	'olivy9',	0);

-- 2021-03-21 20:07:13
