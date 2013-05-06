-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 06, 2013 at 12:06 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `p4p`
--
CREATE DATABASE `p4p` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `p4p`;

-- --------------------------------------------------------

--
-- Table structure for table `airline`
--

CREATE TABLE IF NOT EXISTS `airline` (
  `airline_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `OverweightChargeG` int(11) NOT NULL,
  `OverweightChargeBag` int(11) NOT NULL,
  `ChargeExtraBag` int(11) NOT NULL,
  `OversizeCharge` int(11) NOT NULL,
  PRIMARY KEY (`airline_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `airports`
--

CREATE TABLE IF NOT EXISTS `airports` (
  `airport_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`airport_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `airline_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `strollerAllowedHL` tinyint(1) NOT NULL,
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
  `MaxWeigtPet` int(11) NOT NULL,
  `sizeLenghtPet` int(11) NOT NULL,
  `sizeHeightPet` int(11) NOT NULL,
  `sizeWidthPet` int(11) NOT NULL,
  `sizeTotalPet` int(11) NOT NULL,
  `DeclarationOfValue` tinyint(1) NOT NULL,
  `MaxDeclarationOfValue` int(11) NOT NULL,
  PRIMARY KEY (`airline_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `specialluggage`
--

CREATE TABLE IF NOT EXISTS `specialluggage` (
  `specialluggage_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`specialluggage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `traject`
--

CREATE TABLE IF NOT EXISTS `traject` (
  `traject_id` int(11) NOT NULL AUTO_INCREMENT,
  `airport_start_id` int(11) NOT NULL,
  `airport_stop_id` int(11) NOT NULL,
  PRIMARY KEY (`traject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
