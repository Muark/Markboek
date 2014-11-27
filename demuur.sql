-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server versie:                5.6.17 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Versie:              8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Databasestructuur van demuur wordt geschreven
CREATE DATABASE IF NOT EXISTS `demuur` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `demuur`;


-- Structuur van  tabel demuur.comment wordt geschreven
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(1500) NOT NULL,
  `datum` int(20) NOT NULL,
  `status` varchar(15) NOT NULL DEFAULT '0',
  `post_id` int(11) DEFAULT NULL,
  `gebruiker_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_comment_gebruiker` (`gebruiker_id`),
  KEY `FK_comment_post` (`post_id`),
  KEY `FK_comment_comment` (`parent_id`),
  CONSTRAINT `FK_comment_comment` FOREIGN KEY (`parent_id`) REFERENCES `comment` (`id`),
  CONSTRAINT `FK_comment_gebruiker` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`id`),
  CONSTRAINT `FK_comment_post` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

-- Dumpen data van tabel demuur.comment: ~26 rows (ongeveer)
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` (`id`, `content`, `datum`, `status`, `post_id`, `gebruiker_id`, `parent_id`) VALUES
	(1, 'Test', 1416216146, '0', 24, 10, NULL),
	(5, '    joehoe  k', 1416239167, '1', 24, 4, NULL),
	(6, ' testttttttttttttt', 1416239204, '1', 24, 4, NULL),
	(7, ' hey', 1416386069, '1', 24, 4, NULL),
	(8, ' kaas', 1416386863, '1', 24, 4, NULL),
	(9, ' lalalala', 1416386891, '1', 24, 4, NULL),
	(10, ' ads', 1416386945, '1', 24, 4, NULL),
	(11, ' sadssd', 1416386972, '1', 24, 4, NULL),
	(12, ' rgfgf', 1416386989, '1', 24, 4, NULL),
	(13, ' jaap', 1416387105, '1', 1, 10, NULL),
	(14, ' hallo', 1416387362, '1', 1, 4, NULL),
	(15, 'nee', 1416399624, '1', 24, 4, NULL),
	(16, ' jajaja', 1416491729, '1', 24, 4, NULL),
	(17, ' neeeee', 1416497155, '1', 30, 12, NULL),
	(18, ' aa', 1416500380, '1', 24, 4, NULL),
	(19, ' ja', 1416818850, '1', 31, 4, NULL),
	(20, '  1 2 3', 1416818971, '0', 31, 10, NULL),
	(21, ' ja', 1416821907, '1', 31, 4, NULL),
	(22, ' hallo', 1416822251, '1', 31, 4, NULL),
	(23, ' aa', 1416839113, '1', 33, 4, NULL),
	(24, ' 55', 1416839116, '0', 33, 4, NULL),
	(25, '  bb aa', 1416843637, '1', 38, 4, NULL),
	(26, '  345 ', 1416936796, '1', 38, 4, NULL),
	(27, ' aa', 1416937837, '0', 31, 4, NULL),
	(28, ' aaa', 1416938896, '1', 38, 4, NULL),
	(29, ' grtrrr', 1416947874, '1', 40, 14, NULL),
	(30, '  COMMENTaa', 1416995029, '1', 53, 15, NULL),
	(31, '  dasdsdsdasdassad', 1416995069, '1', 55, 15, NULL),
	(32, ' BB', 1416995093, '0', 55, 15, NULL),
	(33, ' CC', 1416995094, '0', 55, 15, NULL),
	(34, ' a', 1417097328, '0', 33, 4, NULL),
	(35, ' a', 1417097329, '0', 33, 4, NULL),
	(36, ' c', 1417097331, '0', 33, 4, NULL),
	(37, '  hoi\r\nhey \r\nhey', 1417098167, '0', 58, 4, NULL),
	(38, '  hey\r\nhey \r\nhey', 1417098358, '1', 59, 4, NULL);
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;


-- Structuur van  tabel demuur.gebruiker wordt geschreven
CREATE TABLE IF NOT EXISTS `gebruiker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `groep_id` int(11) NOT NULL DEFAULT '1',
  `persoon_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_gebruiker_groep` (`groep_id`),
  KEY `FK_gebruiker_persoon` (`persoon_id`),
  CONSTRAINT `FK_gebruiker_groep` FOREIGN KEY (`groep_id`) REFERENCES `groep` (`id`),
  CONSTRAINT `FK_gebruiker_persoon` FOREIGN KEY (`persoon_id`) REFERENCES `persoon` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- Dumpen data van tabel demuur.gebruiker: ~5 rows (ongeveer)
/*!40000 ALTER TABLE `gebruiker` DISABLE KEYS */;
INSERT INTO `gebruiker` (`id`, `email`, `password`, `status`, `groep_id`, `persoon_id`) VALUES
	(4, 'mark-rademaker@hotmail.nl', '123', 0, 2, 2),
	(10, 'kevin1302@hotmail.com', '123', 0, 1, 7),
	(11, 'frits@hotmail.com', '123', 0, 1, 8),
	(12, 'test@test.com', '12345', 1, 1, 9),
	(14, 'testaccount@hotmail.nl', '123', 1, 1, 12),
	(15, 'account@hotmail.com', '12345', 0, 1, 13);
/*!40000 ALTER TABLE `gebruiker` ENABLE KEYS */;


-- Structuur van  tabel demuur.groep wordt geschreven
CREATE TABLE IF NOT EXISTS `groep` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumpen data van tabel demuur.groep: ~2 rows (ongeveer)
/*!40000 ALTER TABLE `groep` DISABLE KEYS */;
INSERT INTO `groep` (`id`, `type`) VALUES
	(1, 'gebruiker'),
	(2, 'admin');
/*!40000 ALTER TABLE `groep` ENABLE KEYS */;


-- Structuur van  tabel demuur.likes wordt geschreven
CREATE TABLE IF NOT EXISTS `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gebruiker_id` int(11) NOT NULL,
  `type` varchar(15) NOT NULL,
  `type_id` int(11) NOT NULL,
  `datum` int(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_like_gebruiker` (`gebruiker_id`),
  KEY `FK_like_comment` (`type_id`),
  CONSTRAINT `FK_like_gebruiker` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumpen data van tabel demuur.likes: ~0 rows (ongeveer)
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
INSERT INTO `likes` (`id`, `gebruiker_id`, `type`, `type_id`, `datum`) VALUES
	(1, 4, 'post', 31, 1416965594);
/*!40000 ALTER TABLE `likes` ENABLE KEYS */;


-- Structuur van  tabel demuur.persoon wordt geschreven
CREATE TABLE IF NOT EXISTS `persoon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voornaam` varchar(50) NOT NULL,
  `achternaam` varchar(50) NOT NULL,
  `geboortedatum` int(20) NOT NULL,
  `geslacht` varchar(50) NOT NULL,
  `adres` varchar(50) NOT NULL,
  `postcode` varchar(50) NOT NULL,
  `woonplaats` varchar(50) NOT NULL,
  `telefoon` varchar(50) DEFAULT NULL,
  `mobiel` varchar(50) DEFAULT NULL,
  `avatar` varchar(250) DEFAULT 'http://kfc.com.au/media/82236/chicken-originalrecipe_1pce.jpg',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Dumpen data van tabel demuur.persoon: ~4 rows (ongeveer)
/*!40000 ALTER TABLE `persoon` DISABLE KEYS */;
INSERT INTO `persoon` (`id`, `voornaam`, `achternaam`, `geboortedatum`, `geslacht`, `adres`, `postcode`, `woonplaats`, `telefoon`, `mobiel`, `avatar`) VALUES
	(2, 'Mark', 'Rademaker', 858384000, 'Man', 'Molenhoek 22', '2793AG', 'Molenaarsgraaf', '0184642248', '0648820165', 'http://burgerplaza.nl/wp-content/uploads/burger2.jpg'),
	(7, 'aaa', 'aaa', 2014, 'Man', 'ssdsd', 'sadsasad', 'sadsasad', '11', '11', 'http://kfc.com.au/media/82236/chicken-originalrecipe_1pce.jpg'),
	(8, 'aaa', 'aaa', 1414796400, 'Man', 'jaap', 'baard', 'aa', '', '', 'http://kfc.com.au/media/82236/chicken-originalrecipe_1pce.jpg'),
	(9, 'Hallo', 'Test', 1414972800, 'Man', 'Straat', 'Postcode', 'Woonplaats', '111', '222', 'http://kfc.com.au/media/82236/chicken-originalrecipe_1pce.jpg'),
	(12, 'Test1', 'Account1', 662688000, 'Vrouw', 'Straat 121', '1234XX1', 'Stad1', '07844556671', '06112233441', 'http://kfc.com.au/media/82236/chicken-originalrecipe_1pce.jpg'),
	(13, 'Jan', 'De Boer', 789696000, 'Man', 'De Straat 123', '2973AG', 'Stadje', '0556785958', '0675849675', 'http://kfc.com.au/media/82236/chicken-originalrecipe_1pce.jpg');
/*!40000 ALTER TABLE `persoon` ENABLE KEYS */;


-- Structuur van  tabel demuur.post wordt geschreven
CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(1500) NOT NULL,
  `datum` int(20) NOT NULL,
  `status` varchar(15) NOT NULL DEFAULT '0',
  `gebruiker_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_post_gebruiker` (`gebruiker_id`),
  CONSTRAINT `FK_post_gebruiker` FOREIGN KEY (`gebruiker_id`) REFERENCES `gebruiker` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;

-- Dumpen data van tabel demuur.post: ~20 rows (ongeveer)
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT INTO `post` (`id`, `content`, `datum`, `status`, `gebruiker_id`) VALUES
	(1, 'Hallo', 1416216146, '1', 10),
	(20, 'jaap', 1416216146, '1', 4),
	(21, ' jaap', 1416216920, '1', 4),
	(22, ' kaas', 1416216946, '1', 4),
	(23, ' wow', 1416231508, '1', 4),
	(24, 'nee', 1416231514, '1', 4),
	(27, ' jo', 1416396021, '1', 4),
	(28, ' jo', 1416396057, '1', 4),
	(29, ' jo', 1416397468, '1', 4),
	(30, 'jaaaaa', 1416497137, '1', 12),
	(31, '     hoii ', 1416818624, '1', 4),
	(32, ' ', 1416818639, '1', 4),
	(33, ' joehoe', 1416818966, '0', 10),
	(34, ' aa', 1416819985, '1', 4),
	(35, ' ', 1416835519, '1', 4),
	(36, ' qq', 1416842003, '1', 4),
	(37, ' dd', 1416842551, '1', 4),
	(38, '   123 ', 1416843634, '1', 4),
	(39, ' Jo', 1416937381, '1', 4),
	(40, ' oi', 1416945688, '1', 14),
	(52, ' asas', 1416965594, '1', 4),
	(53, '  JOE A', 1416995018, '1', 15),
	(54, ' KIP', 1416995055, '1', 15),
	(55, 'AA', 1416995058, '0', 15),
	(56, ' n', 1417097133, '1', 4),
	(57, ' hallo\r\nhallo', 1417097499, '0', 4),
	(58, ' hey\r\nhallo\r\nhoi', 1417097886, '1', 4),
	(59, '  hey \r\nhey \r\nhey', 1417098353, '1', 4);
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
