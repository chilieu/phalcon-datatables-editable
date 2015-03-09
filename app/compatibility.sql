-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 09, 2015 at 09:07 AM
-- Server version: 5.5.41-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `compatibility`
--

-- --------------------------------------------------------

--
-- Table structure for table `compatibility`
--

CREATE TABLE IF NOT EXISTS `compatibility` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `components_id` int(11) DEFAULT NULL,
  `os_id` int(11) DEFAULT NULL,
  `compatible` int(1) DEFAULT '0' COMMENT '1:yes,0:maybe,-1:no',
  `note` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Compatibility tables' AUTO_INCREMENT=35 ;

--
-- Dumping data for table `compatibility`
--

INSERT INTO `compatibility` (`id`, `components_id`, `os_id`, `compatible`, `note`) VALUES
(1, 5, 2, -1, 'afdgdsfg'),
(2, 5, 3, 0, 'b'),
(3, 5, 4, 0, 'c'),
(4, 5, 5, 0, 'testing '),
(5, 5, 6, 1, ''),
(6, 5, 7, -1, ''),
(7, 5, 8, 0, ''),
(8, 5, 9, 0, ''),
(9, 5, 10, 1, 'back 1'),
(10, 5, 11, -1, 'back'),
(11, 5, 12, 0, ''),
(12, 5, 13, 1, ''),
(13, 5, 14, 1, ''),
(14, 5, 15, 1, ''),
(15, 5, 16, 1, ''),
(16, 5, 17, 1, ''),
(17, 5, 18, 1, ''),
(18, 8, 2, -1, ''),
(19, 8, 3, -1, ''),
(20, 8, 4, 0, ''),
(21, 8, 5, 1, ''),
(22, 8, 6, 0, '123654897'),
(23, 8, 7, -1, ''),
(24, 8, 8, 0, ''),
(25, 8, 9, 0, ''),
(26, 8, 10, 0, ''),
(27, 8, 11, 0, ''),
(28, 8, 12, 0, ''),
(29, 8, 13, 0, ''),
(30, 8, 14, 0, ''),
(31, 8, 15, 0, ''),
(32, 8, 16, -1, ''),
(33, 8, 17, 1, ''),
(34, 8, 18, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `components`
--

CREATE TABLE IF NOT EXISTS `components` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manufacturer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `model` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='components table' AUTO_INCREMENT=14 ;

--
-- Dumping data for table `components`
--

INSERT INTO `components` (`id`, `type`, `manufacturer`, `model`) VALUES
(4, 'Motherboard', 'Supermicro', 'X9SCL'),
(5, 'LAN', 'Intel', 'i217'),
(8, 'LAN', 'Intel', '82579LM'),
(9, 'LAN', 'Intel', '82574L'),
(10, 'Video', 'Matrox', 'G200');

-- --------------------------------------------------------

--
-- Table structure for table `motherboard`
--

CREATE TABLE IF NOT EXISTS `motherboard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manufacturer` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `model` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='mother board table' AUTO_INCREMENT=13 ;

--
-- Dumping data for table `motherboard`
--

INSERT INTO `motherboard` (`id`, `manufacturer`, `model`, `sort`) VALUES
(1, 'SuperMicro', 'X10SLA-F0', 1),
(2, 'SuperMicro', 'X10SLA-F1', 2),
(3, 'SuperMicro', 'X10SLA-F2', 3),
(4, 'SuperMicro', 'X10SLX-F3', 4),
(5, 'SuperMicro', 'X10SRW-F4', 5),
(6, 'SuperMicro', 'X9SCL-5', 6),
(7, 'SuperMicro ', 'X9SRi-6', 7),
(8, 'Asus', 'H97M-PLUS-7', 8);

-- --------------------------------------------------------

--
-- Table structure for table `motherboard_components`
--

CREATE TABLE IF NOT EXISTS `motherboard_components` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `motherboard_id` int(11) DEFAULT NULL,
  `components_id` int(11) DEFAULT NULL,
  `compatible` int(1) DEFAULT '0' COMMENT '1:yes,0:maybe,-1:no',
  `note` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='connection table between motherboard and components' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `motherboard_components`
--

INSERT INTO `motherboard_components` (`id`, `motherboard_id`, `components_id`, `compatible`, `note`) VALUES
(1, 8, 4, 0, 'testing 01'),
(2, 8, 5, 1, 'testing 02'),
(3, 8, 8, -1, 'testing 03'),
(4, 8, 9, 1, ''),
(5, 8, 10, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `os`
--

CREATE TABLE IF NOT EXISTS `os` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `revision` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `release_year` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Operating System' AUTO_INCREMENT=19 ;

--
-- Dumping data for table `os`
--

INSERT INTO `os` (`id`, `name`, `revision`, `release_year`) VALUES
(2, 'Windows', 'XP', '2001'),
(3, 'Windows Server ', '2008 R2', '2009'),
(4, 'CentOS', '6.5', '2013'),
(5, 'CentOS', '7.0', '2014'),
(6, 'Windows', '8.1', '2013'),
(7, 'Windows', '7', '2009'),
(8, 'Red Hat Enterprise Linux', '7', '2014'),
(9, 'Red Hat Enterprise Linux', '6.5', '2013'),
(10, 'Ubuntu', '14.04.1', '2014'),
(11, 'Ubuntu ', '12.04', '2012'),
(12, 'Debian', '7.8', '2015'),
(13, 'Scientific Linux', '7', '2014'),
(14, 'Scientific Linux', '6.5', '2013'),
(15, 'PFSense', '2.2', '2015'),
(16, 'ESXi', '5.5', '2014'),
(17, 'FreeNAS', '9.3', '2014'),
(18, 'Windows Server', '2012', '2012');

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE IF NOT EXISTS `test` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='test table';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
