-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jan 11, 2017 at 11:07 AM
-- Server version: 5.5.52-cll-lve
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pripcyoy_oxymora`
--
CREATE DATABASE IF NOT EXISTS `{db}` DEFAULT CHARACTER SET utf8 COLLATE=utf8_unicode_ci;
USE `{db}`;

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

DROP TABLE IF EXISTS `{addons}`;
CREATE TABLE `{addons}` (
  `name` varchar(128) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `installed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attempts`
--

DROP TABLE IF EXISTS `{membersystem_attempt}`;
CREATE TABLE `{membersystem_attempt}` (
  `memberid` int(11) unsigned NOT NULL,
  `ip` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `memberid` (`memberid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `{membersystem_session}`;
CREATE TABLE IF NOT EXISTS `{membersystem_session}` (
  `memberid` int(11) unsigned NOT NULL,
  `session` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `session` (`session`),
  KEY `memberid` (`memberid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `{content}`;
CREATE TABLE `{content}` (
  `pageurl` varchar(128) NOT NULL,
  `area` varchar(128) NOT NULL,
  `content` text NOT NULL,
  UNIQUE KEY `pageurl` (`pageurl`,`area`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `{groups}`;
CREATE TABLE `{groups}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `color` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `group_permissions`
--

DROP TABLE IF EXISTS `{grouppermission}`;
CREATE TABLE `{grouppermission}` (
  `groupid` int(11) NOT NULL,
  `permission` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`groupid`,`permission`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `navigation`
--

DROP TABLE IF EXISTS `{navigation}`;
CREATE TABLE `{navigation}` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `url` varchar(256) NOT NULL,
  `display` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `{pages}`;
CREATE TABLE `{pages}` (
  `url` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_index`
--

DROP TABLE IF EXISTS `{permissionindex}`;
CREATE TABLE `{permissionindex}` (
  `key` varchar(128) NOT NULL,
  `title` varchar(128) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pluginsettings`
--

DROP TABLE IF EXISTS `{pluginsettings}`;
CREATE TABLE `{pluginsettings}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pluginid` varchar(32) NOT NULL,
  `settingkey` varchar(64) NOT NULL,
  `settingvalue` text,
  `settingtype` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `static`
--

DROP TABLE IF EXISTS `{staticVars}`;
CREATE TABLE `{staticVars}` (
  `placeholder` varchar(64) NOT NULL,
  `value` varchar(256) NOT NULL,
  UNIQUE KEY `key` (`placeholder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `{user}`;
CREATE TABLE `{user}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `groupid` int(9) NOT NULL,
  `email` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `widgets`
--

DROP TABLE IF EXISTS `{widgets}`;
CREATE TABLE `{widgets}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `widget` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `displayid` int(11) NOT NULL,
  UNIQUE KEY `id` (`id`,`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attempts`
--
ALTER TABLE `{membersystem_attempt}`
  ADD CONSTRAINT `{membersystem_attempt}_ibfk_1` FOREIGN KEY (`memberid`) REFERENCES `{user}` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

  ALTER TABLE `{membersystem_session}`
    ADD CONSTRAINT `{membersystem_session}_ibfk_1` FOREIGN KEY (`memberid`) REFERENCES `{user}` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `{pages}` ADD PRIMARY KEY `url`;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
