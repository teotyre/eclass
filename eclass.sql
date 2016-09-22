-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 29, 2015 at 02:26 PM
-- Server version: 5.5.41-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `eclass`
--

-- --------------------------------------------------------

--
-- Table structure for table `ekpaideytikos`
--

CREATE TABLE IF NOT EXISTS `ekpaideytikos` (
  `ekp_id` int(11) NOT NULL,
  `ekp_username` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `ekp_password` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `ekp_onoma` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `ekp_eponymo` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `ekp_rolos` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Εκπαιδευτικός',
  PRIMARY KEY (`ekp_id`),
  UNIQUE KEY `ekp_username_UNIQUE` (`ekp_username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ekpaideytikos`
--

INSERT INTO `ekpaideytikos` (`ekp_id`, `ekp_username`, `ekp_password`, `ekp_onoma`, `ekp_eponymo`, `ekp_rolos`) VALUES
(1, 'admin', '1234', 'Θεόδωρος', 'Τυρεκίδης', 'Εκπαιδευτικός');

-- --------------------------------------------------------

--
-- Table structure for table `ergasia`
--

CREATE TABLE IF NOT EXISTS `ergasia` (
  `erg_id` int(11) NOT NULL AUTO_INCREMENT,
  `erg_onoma` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `erg_perigrafi` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `erg_is_visible` tinyint(4) NOT NULL,
  `erg_file` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `erg_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `les_les_id` int(11) NOT NULL,
  PRIMARY KEY (`erg_id`),
  KEY `fk_Ergasies_Lesson1_idx` (`les_les_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ergasia_has_keyword`
--

CREATE TABLE IF NOT EXISTS `ergasia_has_keyword` (
  `erg_erg_id` int(11) NOT NULL,
  `keyw_keyw_id` int(11) NOT NULL,
  PRIMARY KEY (`erg_erg_id`,`keyw_keyw_id`),
  KEY `fk_Ergasies_has_Keywords_Keywords1_idx` (`keyw_keyw_id`),
  KEY `fk_Ergasies_has_Keywords_Ergasies1_idx` (`erg_erg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keyword`
--

CREATE TABLE IF NOT EXISTS `keyword` (
  `keyw_id` int(11) NOT NULL AUTO_INCREMENT,
  `keyw_word` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`keyw_id`),
  UNIQUE KEY `keyw_word_UNIQUE` (`keyw_word`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lesson`
--

CREATE TABLE IF NOT EXISTS `lesson` (
  `les_id` int(11) NOT NULL AUTO_INCREMENT,
  `les_onoma` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `les_perigrafi` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`les_id`),
  UNIQUE KEY `les_onoma_UNIQUE` (`les_onoma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mathitis`
--

CREATE TABLE IF NOT EXISTS `mathitis` (
  `math_id` int(11) NOT NULL AUTO_INCREMENT,
  `math_username` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `math_password` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `math_onoma` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `math_eponymo` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `math_rolos` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Μαθητής',
  `math_endiaferonta` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `math_birthday` date DEFAULT NULL,
  `math_diamoni` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `math_photo` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`math_id`),
  UNIQUE KEY `math_username_UNIQUE` (`math_username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `mathitis`
--

INSERT INTO `mathitis` (`math_id`, `math_username`, `math_password`, `math_onoma`, `math_eponymo`, `math_rolos`, `math_endiaferonta`, `math_birthday`, `math_diamoni`, `math_photo`) VALUES
(1, 'm1', '1234', 'Μιχάλης', 'Ιωαννίδης', 'Μαθητής', NULL, NULL, NULL, NULL),
(2, 'm2', '1234', 'Ιωάννης', 'Παπαδόπουλος', 'Μαθητής', NULL, NULL, NULL, NULL),
(3, 'm3', '1234', 'Στέλιος', 'Πετρίδης', 'Μαθητής', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `parakolouthei`
--

CREATE TABLE IF NOT EXISTS `parakolouthei` (
  `par_id` int(11) NOT NULL AUTO_INCREMENT,
  `par_les_id` int(11) DEFAULT NULL,
  `math_math_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`par_id`),
  KEY `fk_Mathitis_has_Lesson_Lesson1_idx` (`par_les_id`),
  KEY `fk_Parakolouthei_Mathitis1_idx` (`math_math_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ergasia`
--
ALTER TABLE `ergasia`
  ADD CONSTRAINT `fk_Ergasies_Lesson1` FOREIGN KEY (`les_les_id`) REFERENCES `lesson` (`les_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ergasia_has_keyword`
--
ALTER TABLE `ergasia_has_keyword`
  ADD CONSTRAINT `fk_Ergasies_has_Keywords_Ergasies1` FOREIGN KEY (`erg_erg_id`) REFERENCES `ergasia` (`erg_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Ergasies_has_Keywords_Keywords1` FOREIGN KEY (`keyw_keyw_id`) REFERENCES `keyword` (`keyw_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `parakolouthei`
--
ALTER TABLE `parakolouthei`
  ADD CONSTRAINT `fk_Mathitis_has_Lesson_Lesson1` FOREIGN KEY (`par_les_id`) REFERENCES `lesson` (`les_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Parakolouthei_Mathitis1` FOREIGN KEY (`math_math_id`) REFERENCES `mathitis` (`math_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
