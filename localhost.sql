-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 31, 2013 at 12:26 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


-- --------------------------------------------------------

--
-- Table structure for table `airline`
--

CREATE TABLE IF NOT EXISTS `airline` (
  `airline_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `OverweightChargeG` float NOT NULL,
  `OverweightChargeBag` float NOT NULL,
  `OversizeCharge` float NOT NULL,
  `iata` varchar(3) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`airline_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `airline`
--

INSERT INTO `airline` (`airline_id`, `name`, `logo`, `OverweightChargeG`, `OverweightChargeBag`, `OversizeCharge`, `iata`, `notes`) VALUES
(2, 'YoloFly', 'test', 1, 2, 4, 'YF', ''),
(3, 'AirFrance', 'test', 1, 2, 4, 'AF', ''),
(4, 'Hax0rAirlinez', 'test', 1, 2, 4, 'HA', ''),
(5, 'ArkeFly', 'test', 1, 2, 4, 'AFL', ''),
(6, 'SwaggerFly', 'test', 1, 2, 4, 'SF', ''),
(7, 'KLM', '1369650934.png', 1.25, 10, 0, 'KL', ''),
(8, 'YoloFlyWithSwag', '', 5, 1.2358, 741025, 'YL', ''),
(9, 'test', 'nee', 10, 0, 0, 'Hoi', ''),
(11, 'NoobFly', 'logo.png', 5, 10, 10, 'aBc', 'Dit is een noob maatschappij'),
(12, 'hoi', '1', 1, 1, 1, '1', '2');

-- --------------------------------------------------------

--
-- Table structure for table `airlineclass`
--

CREATE TABLE IF NOT EXISTS `airlineclass` (
  `class_id` int(11) NOT NULL AUTO_INCREMENT,
  `airline` int(11) NOT NULL,
  `classnumber` int(11) NOT NULL,
  `pcsHL` int(11) NOT NULL,
  `MaxWeightHL` int(11) NOT NULL,
  `sizeLenghtHL` int(11) NOT NULL,
  `sizeHeightHL` int(11) NOT NULL,
  `SizeWidthHL` int(11) NOT NULL,
  `sizeTotalHL` int(11) NOT NULL,
  `LaptopAllowedHL` tinyint(1) NOT NULL,
  `pcsInfantHL` int(11) NOT NULL,
  `pcsLuggageInfant` int(11) NOT NULL,
  `pcsLuggageInfantMaxWeight` int(11) NOT NULL,
  `pcsLuggage` int(11) NOT NULL,
  `maxWeightLuggage` int(11) NOT NULL,
  `LoyaltyProgramme` tinyint(1) NOT NULL,
  `LPextraPcsLuggage` int(11) NOT NULL,
  `LPextraWeightLuggage` int(11) NOT NULL,
  `AbsoluteMaxPerItem` int(11) NOT NULL,
  `sizeLenghtPerItem` int(11) NOT NULL,
  `sizeHeightPerItem` int(11) NOT NULL,
  `sizeWidthPerItem` int(11) NOT NULL,
  `sizeTotalPerItem` int(11) NOT NULL,
  `Pooling` tinyint(1) NOT NULL,
  `FreeWheelChair` tinyint(1) NOT NULL,
  `FreeServiceDog` tinyint(1) NOT NULL,
  `PetsAllowed` tinyint(1) NOT NULL,
  `MaxWeightPet` int(11) NOT NULL,
  `sizeLenghtPet` int(11) NOT NULL,
  `sizeHeightPet` int(11) NOT NULL,
  `sizeWidthPet` int(11) NOT NULL,
  `sizeTotalPet` int(11) NOT NULL,
  `DeclarationOfValue` tinyint(1) NOT NULL,
  `MaxDeclarationOfValue` float NOT NULL,
  `petsAllowedHL` tinyint(1) NOT NULL,
  PRIMARY KEY (`class_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `airlineclass`
--

INSERT INTO `airlineclass` (`class_id`, `airline`, `classnumber`, `pcsHL`, `MaxWeightHL`, `sizeLenghtHL`, `sizeHeightHL`, `SizeWidthHL`, `sizeTotalHL`, `LaptopAllowedHL`, `pcsInfantHL`, `pcsLuggageInfant`, `pcsLuggageInfantMaxWeight`, `pcsLuggage`, `maxWeightLuggage`, `LoyaltyProgramme`, `LPextraPcsLuggage`, `LPextraWeightLuggage`, `AbsoluteMaxPerItem`, `sizeLenghtPerItem`, `sizeHeightPerItem`, `sizeWidthPerItem`, `sizeTotalPerItem`, `Pooling`, `FreeWheelChair`, `FreeServiceDog`, `PetsAllowed`, `MaxWeightPet`, `sizeLenghtPet`, `sizeHeightPet`, `sizeWidthPet`, `sizeTotalPet`, `DeclarationOfValue`, `MaxDeclarationOfValue`, `petsAllowedHL`) VALUES
(1, 3, 2, 98, 7, 11, 14, 100, 50, 0, 8, 2, 4, 1, 3, 0, 2, 3, 99, 23, 10, 89, 52, 0, 0, 0, 0, 1, 8, 2, 3, 5, 0, 50000, 0),
(2, 3, 1, 98, 7, 11, 14, 100, 50, 0, 8, 2, 4, 1, 3, 0, 2, 3, 99, 23, 10, 89, 52, 0, 0, 0, 0, 1, 8, 2, 3, 5, 0, 50000, 0),
(3, 8, 1, 313213213, 1, 1, 1, 1, 1, 0, 1, 2, 0, 1, 0, 0, 0, 0, 0, 1, 10, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 5, 0, 0, 2, 1, 1, 1, 1, 0, 0, 0, 2, 0, 2, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(5, 7, 0, 5, 0, 5, 5, 5, 5, 0, 5, 5, 0, 5, 0, 0, 0, 0, 0, 2, 2, 2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 654, 0),
(6, 7, 2, 1, 0, 1, 1, 1, 11, 0, 1, 1, 0, 1, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0),
(7, 6, 0, 5, 0, 5, 5, 5, 5, 0, 5, 5, 0, 5, 0, 0, 5, 5, 5, 5, 5, 5, 5, 0, 0, 0, 0, 5, 5, 5, 5, 5, 0, 5, 0),
(8, 6, 0, 5, 0, 5, 5, 5, 5, 1, 5, 5, 0, 5, 0, 1, 5, 5, 5, 5, 5, 5, 5, 1, 1, 1, 1, 5, 5, 5, 5, 5, 1, 5, 1),
(9, 12, 3, 4, 5, 6, 7, 8, 9, 0, 9, 8, 7, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `airlinespecialluggage`
--

CREATE TABLE IF NOT EXISTS `airlinespecialluggage` (
  `AirlineSpecialLuggage_id` int(11) NOT NULL AUTO_INCREMENT,
  `airline_id` int(11) NOT NULL,
  `specialLuggage_id` int(11) NOT NULL,
  `notes` varchar(1000) NOT NULL,
  PRIMARY KEY (`AirlineSpecialLuggage_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `airlinespecialluggage`
--

INSERT INTO `airlinespecialluggage` (`AirlineSpecialLuggage_id`, `airline_id`, `specialLuggage_id`, `notes`) VALUES
(1, 1, 1, 'Testbeschrijving'),
(2, 1, 1, 'Testbeschrijving');

-- --------------------------------------------------------

--
-- Table structure for table `airports`
--

CREATE TABLE IF NOT EXISTS `airports` (
  `airport_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `City` varchar(100) NOT NULL,
  PRIMARY KEY (`airport_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `airports`
--

INSERT INTO `airports` (`airport_id`, `name`, `City`) VALUES
(1, 'Schiphol(Amsterdam)', ''),
(2, 'Berlijn', ''),
(3, 'London', '');

-- --------------------------------------------------------

--
-- Table structure for table `chargeextrabag`
--

CREATE TABLE IF NOT EXISTS `chargeextrabag` (
  `ChargeExtraBag_id` int(11) NOT NULL AUTO_INCREMENT,
  `airline` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `costs` float NOT NULL,
  PRIMARY KEY (`ChargeExtraBag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `chargeextrabag`
--

INSERT INTO `chargeextrabag` (`ChargeExtraBag_id`, `airline`, `number`, `costs`) VALUES
(1, 11, 0, 1337),
(2, 11, 0, 9999),
(3, 11, 0, 9999),
(4, 11, 0, 9999);

-- --------------------------------------------------------

--
-- Table structure for table `errorlog`
--

CREATE TABLE IF NOT EXISTS `errorlog` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(100) NOT NULL,
  `error_msg` text NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `specialluggage`
--

CREATE TABLE IF NOT EXISTS `specialluggage` (
  `specialluggage_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`specialluggage_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `specialluggage`
--

INSERT INTO `specialluggage` (`specialluggage_id`, `name`) VALUES
(1, 'Testluggage');

-- --------------------------------------------------------

--
-- Table structure for table `traject`
--

CREATE TABLE IF NOT EXISTS `traject` (
  `traject_id` int(11) NOT NULL AUTO_INCREMENT,
  `airport_start_id` int(11) NOT NULL,
  `airport_stop_id` int(11) NOT NULL,
  PRIMARY KEY (`traject_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `traject`
--

INSERT INTO `traject` (`traject_id`, `airport_start_id`, `airport_stop_id`) VALUES
(1, 2, 3),
(2, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `trajectairline`
--

CREATE TABLE IF NOT EXISTS `trajectairline` (
  `TrajectAirline_id` int(11) NOT NULL AUTO_INCREMENT,
  `airline_id` int(11) NOT NULL,
  `traject_id` int(11) NOT NULL,
  `zone` int(11) NOT NULL,
  PRIMARY KEY (`TrajectAirline_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`) VALUES
(1, 'test1', 'test12');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
