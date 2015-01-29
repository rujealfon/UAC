-- phpMyAdmin SQL Dump
-- version 3.4.8
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 26, 2015 at 04:52 PM
-- Server version: 5.6.17
-- PHP Version: 5.6.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `uac`
--

-- --------------------------------------------------------

--
-- Table structure for table `dev`
--

CREATE TABLE IF NOT EXISTS `dev` (
  `dev_id` int(11) NOT NULL AUTO_INCREMENT,
  `dev_user` int(11) NOT NULL,
  `dev_server` int(11) NOT NULL,
  `dev_status` int(11) NOT NULL,
  `dev_role` varchar(255) NOT NULL,
  `dev_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`dev_id`),
  KEY `dev_user` (`dev_user`,`dev_server`,`dev_status`,`dev_role`,`dev_created`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `server`
--

CREATE TABLE IF NOT EXISTS `server` (
  `server_id` int(11) NOT NULL AUTO_INCREMENT,
  `server_name` varchar(255) NOT NULL,
  `server_root` varchar(255) NOT NULL DEFAULT 'root',
  `server_pass` varchar(255) NOT NULL,
  `server_ip` varchar(255) NOT NULL,
  `server_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`server_id`),
  KEY `server_name` (`server_name`,`server_root`,`server_pass`,`server_ip`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_first` varchar(255) NOT NULL,
  `user_last` varchar(255) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `user_role` int(10) NOT NULL,
  `user_active` int(10) NOT NULL DEFAULT '0',
  `user_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  KEY `user_email` (`user_email`,`user_name`,`user_pass`,`user_role`,`user_active`,`user_created`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_email`, `user_name`, `user_first`, `user_last`, `user_pass`, `user_role`, `user_active`, `user_created`) VALUES
(1, 'cgalgo@openovate.com', 'clark21', 'clark', 'galgo', 'b5f430f0556608d49a8e57ec56f2a54c', 1, 1, '2015-01-23 11:53:36');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;