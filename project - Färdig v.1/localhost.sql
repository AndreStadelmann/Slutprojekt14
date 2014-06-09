-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Värd: localhost
-- Skapad: 09 jun 2014 kl 13:48
-- Serverversion: 5.5.16
-- PHP-version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databas: `schoolproject`
--
CREATE DATABASE `schoolproject` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `schoolproject`;

-- --------------------------------------------------------

--
-- Tabellstruktur `class`
--

CREATE TABLE IF NOT EXISTS `class` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `subject_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `subject_ID` (`subject_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumpning av Data i tabell `class`
--

INSERT INTO `class` (`ID`, `name`, `subject_ID`) VALUES
(1, 'Matte 1c', 1),
(2, 'Matte 2c', 1),
(3, 'Matte 3c', 1),
(4, 'Fysik 1', 2);

-- --------------------------------------------------------

--
-- Tabellstruktur `concepts`
--

CREATE TABLE IF NOT EXISTS `concepts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `concept` varchar(64) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `explanation` text CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `summary_ID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `summary-ID` (`summary_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumpning av Data i tabell `concepts`
--

INSERT INTO `concepts` (`ID`, `concept`, `explanation`, `summary_ID`) VALUES
(1, 'Sin-Satsen', 'Här är en förklaring på sinus-satsen.', 1),
(2, 'Cos-satsen', 'Förklaring på Cos-satsen', 1),
(5, 'Apa', 'Ett djur som &auml;ter banan! ', 7),
(6, 'Kaktus', 'Skall inte &auml;tas med taggar p&aring;! ', 7),
(7, 'Jag kom inte p&aring; n&aring;got mer...', '#tr&aring;kig ', 7),
(8, 'Area', ' Area f&ouml;r en fyrkant;\r\nS * S ', 8);

-- --------------------------------------------------------

--
-- Tabellstruktur `part`
--

CREATE TABLE IF NOT EXISTS `part` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `class_id` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `class-id` (`class_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumpning av Data i tabell `part`
--

INSERT INTO `part` (`ID`, `name`, `class_id`) VALUES
(5, 'Algebra och funktioner', 3),
(6, 'Förändringshastigheter och derivator', 3),
(7, 'Kurvor, derivator och integraler', 3),
(8, 'Trigonometri', 3);

-- --------------------------------------------------------

--
-- Tabellstruktur `subject`
--

CREATE TABLE IF NOT EXISTS `subject` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumpning av Data i tabell `subject`
--

INSERT INTO `subject` (`ID`, `name`) VALUES
(1, 'Matte'),
(2, 'Fysik'),
(3, 'Engelska'),
(4, 'Gränssnittsdesign'),
(5, 'Historia'),
(6, 'Programmering'),
(7, 'Webbteknik'),
(8, 'Svenska');

-- --------------------------------------------------------

--
-- Tabellstruktur `summary`
--

CREATE TABLE IF NOT EXISTS `summary` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `part_ID` int(11) NOT NULL,
  `creator` varchar(64) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `part-ID` (`part_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumpning av Data i tabell `summary`
--

INSERT INTO `summary` (`ID`, `name`, `part_ID`, `creator`, `date`) VALUES
(1, 'Min sammanfattning på trigonometri', 8, 'Jag', '2014-04-30 13:52:41'),
(7, 'Trigonometrisammanfattning', 8, '', '2014-05-14 10:08:48'),
(8, 'Area', 8, '', '2014-06-03 13:20:20');

--
-- Restriktioner för dumpade tabeller
--

--
-- Restriktioner för tabell `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_ibfk_1` FOREIGN KEY (`subject_ID`) REFERENCES `subject` (`ID`);

--
-- Restriktioner för tabell `concepts`
--
ALTER TABLE `concepts`
  ADD CONSTRAINT `concepts_ibfk_1` FOREIGN KEY (`summary_ID`) REFERENCES `summary` (`ID`);

--
-- Restriktioner för tabell `part`
--
ALTER TABLE `part`
  ADD CONSTRAINT `part_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`ID`);

--
-- Restriktioner för tabell `summary`
--
ALTER TABLE `summary`
  ADD CONSTRAINT `summary_ibfk_1` FOREIGN KEY (`part_ID`) REFERENCES `part` (`ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
