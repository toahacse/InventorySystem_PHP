-- Adminer 4.8.1 MySQL 5.5.5-10.3.37-MariaDB-0ubuntu0.20.04.1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `categories` (`id`, `name`) VALUES
(1,	'CATEGORY 1'),
(2,	'CATEGORY 2'),
(3,	'CATEGORY 3'),
(4,	'CATEGORY 4'),
(5,	'CATEGORY 5');

DROP TABLE IF EXISTS `media`;
CREATE TABLE `media` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `buy_price` decimal(25,2) DEFAULT NULL,
  `sale_price` decimal(25,2) NOT NULL,
  `categorie_id` int(11) unsigned NOT NULL,
  `media_id` int(11) DEFAULT 0,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `categorie_id` (`categorie_id`),
  KEY `media_id` (`media_id`),
  CONSTRAINT `FK_products` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `products` (`id`, `name`, `quantity`, `buy_price`, `sale_price`, `categorie_id`, `media_id`, `date`) VALUES
(1,	'Product 1',	'38',	100.00,	500.00,	1,	0,	'2023-01-21 15:45:55'),
(2,	'Product 2',	'12000',	55.00,	130.00,	4,	0,	'2023-01-21 15:45:55'),
(3,	'Product 3',	'66',	2.00,	5.00,	2,	0,	'2023-01-21 15:45:55'),
(4,	'Product 4',	'1200',	780.00,	1069.00,	2,	0,	'2023-01-21 15:45:55'),
(5,	'Product 5',	'26',	299.00,	494.00,	5,	0,	'2023-01-21 15:45:55'),
(6,	'Product 6',	'42',	280.00,	415.00,	5,	0,	'2023-01-21 15:45:55'),
(7,	'Product 7',	'107',	3.00,	7.00,	3,	0,	'2023-01-21 15:45:55'),
(8,	'Product 8',	'110',	13.00,	20.00,	3,	0,	'2023-01-21 15:45:55'),
(9,	'Product 9',	'67',	29.00,	55.00,	3,	0,	'2023-01-21 15:45:55'),
(10,	'Product 10',	'106',	219.00,	322.00,	3,	0,	'2023-01-21 15:45:55'),
(11,	'Product 11',	'78',	21.00,	31.00,	4,	0,	'2023-01-21 15:45:55');

DROP TABLE IF EXISTS `sales`;
CREATE TABLE `sales` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) unsigned NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(25,2) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `SK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `sales` (`id`, `product_id`, `qty`, `price`, `date`) VALUES
(1,	1,	5,	2500.00,	'2023-01-21'),
(2,	3,	3,	15.00,	'2023-01-21'),
(3,	1,	5,	2500.00,	'2023-01-21');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `status` int(1) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_level` (`user_level`),
  CONSTRAINT `FK_user` FOREIGN KEY (`user_level`) REFERENCES `user_groups` (`group_level`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `users` (`id`, `name`, `username`, `password`, `user_level`, `image`, `status`, `last_login`) VALUES
(1,	'Toaha',	'Admin',	'd033e22ae348aeb5660fc2140aec35850c4da997',	1,	'no_image.png',	1,	'2023-01-21 15:41:14');

DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_level` (`group_level`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `user_groups` (`id`, `group_name`, `group_level`, `group_status`) VALUES
(1,	'Admin',	1,	1),
(2,	'special',	2,	1),
(3,	'User',	3,	1);

-- 2023-01-21 15:50:43
