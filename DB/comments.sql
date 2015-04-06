-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2014-11-21 07:44:07
-- 服务器版本： 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `helinhai`
--

-- --------------------------------------------------------

--
-- 表的结构 `comments`
--

--REATE TABLE IF NOT EXISTS `comments` (
  --`business_id` int(11) NOT NULL,
  --`review_id` int(30) NOT NULL,
  --`user_nickname` varchar(50) CHARACTER SET utf8 NOT NULL,
  --`text_excerpt` text CHARACTER SET utf8 NOT NULL,
  --`review_rating` int(2) ,
  --`product_rating` int(2) ,
  --`decoration_rating` int(2),
  --`service_rating` int(2),
  --`created_time` datetime NOT NULL
--) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE IF NOT EXISTS `comments` (
  `business_id` int(11) NOT NULL,
  `comment_1` text CHARACTER SET utf8 NOT NULL,
  `comment_2` text CHARACTER SET utf8 NOT NULL,
  `comment_3` text CHARACTER SET utf8 NOT NULL,
  `comment_4` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `comments`
 ADD PRIMARY KEY (`business_id`);


