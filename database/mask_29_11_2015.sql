-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2015 at 02:36 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mask`
--

-- --------------------------------------------------------

--
-- Table structure for table `hashtags`
--

CREATE TABLE IF NOT EXISTS `hashtags` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `tag` varchar(100) NOT NULL,
  `date_time` date NOT NULL,
  `graph_values` varchar(100) NOT NULL,
  `user_id` int(5) NOT NULL,
  `is_fav` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45 ;

--
-- Dumping data for table `hashtags`
--

INSERT INTO `hashtags` (`id`, `tag`, `date_time`, `graph_values`, `user_id`, `is_fav`) VALUES
(44, 'testing', '2015-11-29', '\n-1  0  1  2 \n 9 36 17  2 \n', 10, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tweet`
--

CREATE TABLE IF NOT EXISTS `tweet` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `neg_tweet` varchar(1000) NOT NULL,
  `pos_tweet` varchar(1000) NOT NULL,
  `tag_id` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `tweet`
--

INSERT INTO `tweet` (`id`, `neg_tweet`, `pos_tweet`, `tag_id`) VALUES
(23, '<div class=''container-twt''>\n<a href=''#'' class=''tw-icon-twt''></a>\n			<div class=''notification-box-twt''>\n			\n				<div class=''list-twt''>\n					<img src=''images/twitter-logo.jpg'' class=''avatar-twt''>\n					<div class=''content-twt''>\n					#Repost rocketdoguk with repostapp\n···\nTesting prototype #mudhugger fat #fatbike #mtb #nwalps https://t.co/hjhumNFNBb<i class=''time-twt''>1s</i>\n					</div>\n					</div>\n					\n			\n	\n			\n				<div class=''list-twt''>\n					<img src=''images/twitter-logo.jpg'' class=''avatar-twt''>\n					<div class=''content-twt''>\n					#CRMA Testing can find chronic undiagnosed spinal ligament injuries for vets. Injured? Listen Live: https://t.co/C2NYnxoIO5 #BestTalkRadio<i class=''time-twt''>1s</i>\n					</div>\n					</div>\n					\n			\n	\n			\n				<div class=''list-twt''>\n					<img src=''images/twitter-logo.jpg'' class=''avatar-twt''>\n					<div class=''content-twt''>\n					#CRMA Testing can find chronic undiagnosed spinal ligament injuries for vets. Injured? Listen Live: https://t.co/pvfrpmqRRR #BestTalkRa', '<div class=''container-twt''>\n<a href=''#'' class=''tw-icon-twt''></a>\n			<div class=''notification-box-twt''>		\n				<div class=''list-twt''>\n					<img src=''images/twitter-logo.jpg'' class=''avatar-twt''>\n					<div class=''content-twt''>\n					#socialmedia The Benefits of Testing Coupons and Discounts - Do you pay a premium to acquire new leads, or offe... https://t.co/3Ig3V9UCDv<i class=''time-twt''>1s</i>\n					</div>\n					</div>\n					\n			\n			\n				<div class=''list-twt''>\n					<img src=''images/twitter-logo.jpg'' class=''avatar-twt''>\n					<div class=''content-twt''>\n					Super great read on TDD and testing in general #JavaScript #tdd   https://t.co/QNba2oCuLv<i class=''time-twt''>1s</i>\n					</div>\n					</div>\n					\n			\n	</div></div>', 44);

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE IF NOT EXISTS `user_info` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`id`, `username`, `password`, `name`) VALUES
(10, 'Joshua', 'joshua', 'Joshua'),
(11, 'john', 'john', 'john');

-- --------------------------------------------------------

--
-- Table structure for table `word_cloud`
--

CREATE TABLE IF NOT EXISTS `word_cloud` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(100) NOT NULL,
  `date_time` date NOT NULL,
  `source_location` varchar(200) NOT NULL,
  `user_id` int(5) NOT NULL,
  `is_fav` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
