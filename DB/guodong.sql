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
-- Database: `guodong`
--

-- --------------------------------------------------------

--
-- 表的结构 `businesses`
--

CREATE TABLE IF NOT EXISTS `businesses` (
  `business_id` int(11) NOT NULL,
  `name` varchar(30) CHARACTER SET utf8 NOT NULL,
  `branch_name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `address` varchar(50) CHARACTER SET utf8 NOT NULL,
  `telephone` varchar(15) CHARACTER SET utf8 NOT NULL,
  `city` varchar(10) CHARACTER SET utf8 NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `avg_rating` float DEFAULT NULL,
  `rating_img_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `rating_s_img_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `product_grade` int(11) DEFAULT NULL,
  `decoration_grade` int(11) DEFAULT NULL,
  `service_grade` int(11) DEFAULT NULL,
  `product_score` float DEFAULT NULL,
  `decoration_score` float DEFAULT NULL,
  `service_score` float DEFAULT NULL,
  `avg_price` int(11) DEFAULT NULL,
  `review_count` int(11) DEFAULT NULL,
  `review_list_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `business_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `photo_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `s_photo_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `photo_count` int(11) DEFAULT NULL,
  `photo_list_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `has_coupon` int(11) NOT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `coupon_description` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `coupon_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `has_deal` int(11) NOT NULL,
  `deals` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `deal_count` int(11) NOT NULL,
  `has_online_reservation` int(11) NOT NULL,
  `online_reservation_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `businesses`
--

INSERT INTO `businesses` (`business_id`, `name`, `branch_name`, `address`, `telephone`, `city`, `latitude`, `longitude`, `avg_rating`, `rating_img_url`, `rating_s_img_url`, `product_grade`, `decoration_grade`, `service_grade`, `product_score`, `decoration_score`, `service_score`, `avg_price`, `review_count`, `review_list_url`, `business_url`, `photo_url`, `s_photo_url`, `photo_count`, `photo_list_url`, `has_coupon`, `coupon_id`, `coupon_description`, `coupon_url`, `has_deal`, `deals`, `deal_count`, `has_online_reservation`, `online_reservation_url`, `created_by`, `created_at`, `modified_by`, `modified_at`) VALUES
(0, '', '', '', '', '', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '', 0, 0, NULL, 1, '2014-11-20 17:22:31', 0, '2014-11-20 17:22:31');

-- --------------------------------------------------------

--
-- 表的结构 `businesses_categories`
--

CREATE TABLE IF NOT EXISTS `businesses_categories` (
  `business_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
`id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- 表的结构 `regions`
--

CREATE TABLE IF NOT EXISTS `regions` (
  `business_id` int(11) NOT NULL,
  `region` varchar(30) NOT NULL,
  `business_district` varchar(30) NOT NULL,
  `sub_business_district` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `regions`
--

INSERT INTO `regions` (`business_id`, `region`, `business_district`, `sub_business_district`) VALUES
(0, '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'chai.yanlin', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `businesses`
--
ALTER TABLE `businesses`
 ADD PRIMARY KEY (`business_id`);

--
-- Indexes for table `businesses_categories`
--
ALTER TABLE `businesses_categories`
 ADD PRIMARY KEY (`business_id`,`category_id`), ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
 ADD PRIMARY KEY (`business_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- 限制导出的表
--

--
-- 限制表 `businesses_categories`
--
ALTER TABLE `businesses_categories`
ADD CONSTRAINT `businesses_categories_ibfk_1` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`business_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `businesses_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `regions`
--
ALTER TABLE `regions`
ADD CONSTRAINT `regions_ibfk_1` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`business_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
