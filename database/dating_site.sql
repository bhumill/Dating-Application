-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2020 at 06:27 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dating_site`
--
CREATE DATABASE IF NOT EXISTS `dating_site` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `dating_site`;

-- --------------------------------------------------------

--
-- Table structure for table `favourite`
--

DROP TABLE IF EXISTS `favourite`;
CREATE TABLE IF NOT EXISTS `favourite` (
  `fav_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  PRIMARY KEY (`fav_id`),
  KEY `TO_USER_ID` (`user_id`,`user2_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `chat_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `message` mediumtext NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`chat_id`),
  KEY `TO_USER_ID` (`user_id`,`to_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`chat_id`, `user_id`, `to_user_id`, `message`, `time`) VALUES
(1, 1, 4, 'HI', '2020-10-05 19:27:28'),
(2, 4, 1, 'Hi There!', '2020-10-05 19:27:28'),
(3, 4, 1, 'how are you', '2020-10-05 21:20:26'),
(4, 4, 1, '?', '2020-10-05 21:36:59'),
(5, 4, 1, 'Are you fine', '2020-10-05 21:40:57'),
(6, 4, 2, 'how are you', '2020-10-05 21:42:38'),
(7, 4, 2, '?', '2020-10-05 21:43:22'),
(8, 1, 4, 'gn', '2020-10-05 23:24:01'),
(9, 7, 5, 'hii!!', '2020-10-06 02:36:17'),
(10, 5, 7, 'hello there!!', '2020-10-06 02:37:14'),
(11, 7, 5, 'nice dp!', '2020-10-06 02:38:47'),
(12, 5, 7, 'Thanks!!', '2020-10-06 02:40:50'),
(13, 5, 7, 'you have cute smile! :-)', '2020-10-06 02:41:49'),
(14, 8, 5, 'hello there!!', '2020-10-06 04:24:30'),
(15, 5, 8, 'hii!!', '2020-10-06 04:25:21');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` varchar(1) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `picture` varchar(50) NOT NULL,
  `regular` char(1) NOT NULL,
  `join_date` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `email`, `password`, `first_name`, `last_name`, `date_of_birth`, `gender`, `city`, `state`, `picture`, `regular`, `join_date`) VALUES
(1, 'chopradhruv94@gmail.com', 'dhruvc', 'David', 'chopra', '2007-06-13', 'm', 'MONTREAL', 'Quebec', '', 'y', '2020-10-04'),
(2, 'shivamarora200028@gmail.com', 'shivam', 'Shivam', 'Arora', '2002-01-25', 'm', 'MONTREAL', 'QC', 'koala.jpg', 'n', '2020-10-04'),
(3, 'ankush.s2123@gmail.com', 'ankush', 'dhruv', 'chopra', '2009-02-02', 'm', 'MONTREAL', 'Quebec', 'insurance.jpg', 'y', '2020-10-04'),
(4, 'chrislu778@gmail.com', 'christ', 'christine', 'luisa', '1996-03-21', 'f', 'Toronto', 'Ontario', 'logo.png', 'n', '2020-10-04'),
(5, 'bhumil.15beceg048@gmail.com', 'bhumil1212', 'Bhumil', 'Lakhtariya', '1996-12-12', 'm', 'montreal', 'Quebec', '99ADAD6F-7046-4170-AEC8-373A554303D7.jpg', 'n', '2020-10-05'),
(7, 'kanchanagrawal@gmail.com', 'kanchan1212', 'Kanchan', 'Agrawal', '1997-12-05', 'f', 'montreal', 'Quebec', 'download.jpg', 'n', '2020-10-05'),
(8, 'ananyapatel@gmail.com', 'ananya1212', 'Ananya', 'Patel', '1997-12-11', 'f', 'montreal', 'Quebec', 'download (1).jpg', 'n', '2020-10-06');

-- --------------------------------------------------------

--
-- Table structure for table `wink`
--

DROP TABLE IF EXISTS `wink`;
CREATE TABLE IF NOT EXISTS `wink` (
  `wink_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  PRIMARY KEY (`wink_id`),
  KEY `USER_ID` (`user_id`,`user2_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wink`
--

INSERT INTO `wink` (`wink_id`, `user_id`, `user2_id`) VALUES
(1, 1, 4),
(2, 1, 4),
(3, 4, 2),
(5, 5, 7),
(7, 5, 8),
(4, 7, 5),
(6, 8, 5);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
