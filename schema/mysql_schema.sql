SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `nomi`;
CREATE TABLE `nomi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(127) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `soggetti`;
CREATE TABLE `soggetti` (
  `id_transazione` int(11) NOT NULL,
  `id_soggetto` int(11) NOT NULL,
  PRIMARY KEY (`id_transazione`,`id_soggetto`),
  KEY `id_benificiario` (`id_soggetto`),
  CONSTRAINT `soggetti_ibfk_2` FOREIGN KEY (`id_soggetto`) REFERENCES `nomi` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `soggetti_ibfk_3` FOREIGN KEY (`id_transazione`) REFERENCES `transazioni` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `transazioni`;
CREATE TABLE `transazioni` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pagante` int(11) NOT NULL,
  `importo` double NOT NULL,
  `causale` varchar(127) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pagante` (`id_pagante`),
  CONSTRAINT `transazioni_ibfk_3` FOREIGN KEY (`id_pagante`) REFERENCES `nomi` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- 2024-08-24 17:53:19
