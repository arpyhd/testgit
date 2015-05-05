-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2015 at 08:33 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nirbuydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type_id` int(11) NOT NULL,
  `first_name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `pass_hash` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id_hash` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `language` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `security_question` int(11) NOT NULL,
  `security_answer` text COLLATE utf8_unicode_ci NOT NULL,
  `added_date` datetime NOT NULL,
  `modified_date` datetime DEFAULT NULL,
  `user_type` enum('admin','user','business') COLLATE utf8_unicode_ci NOT NULL,
  `disabled` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=93 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type_id`, `first_name`, `last_name`, `email`, `password`, `pass_hash`, `id_hash`, `language`, `security_question`, `security_answer`, `added_date`, `modified_date`, `user_type`, `disabled`) VALUES
(58, 0, 'Book 1', 'Store', 'bookstore@test.com', '25d55ad283aa400af464c76d713c07ad', '', NULL, 'en', 0, '', '2014-08-20 00:00:00', '2015-04-07 08:01:55', 'user', NULL),
(92, 1, NULL, NULL, 'test@test.com', '098f6bcd4621d373cade4e832627b4f6', '9d70447d9f622fef3d5dc757fc8f319c', NULL, 'en', 0, '', '2015-04-08 07:51:22', '2015-04-08 07:58:07', 'user', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
