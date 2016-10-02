-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2016 at 10:28 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oxymora`
--

-- --------------------------------------------------------

--
-- Table structure for table `attempts`
--

CREATE TABLE `attempts` (
  `memberid` int(11) UNSIGNED NOT NULL,
  `ip` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `pageurl` varchar(128) NOT NULL,
  `area` varchar(128) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`pageurl`, `area`, `content`) VALUES
('about.html', 'body', '{plugin:Text:3}'),
('index.html', 'body', '{plugin:Slider:1}\r\n{plugin:Text:2}'),
('King.html', 'body', '');

-- --------------------------------------------------------

--
-- Table structure for table `navigation`
--

CREATE TABLE `navigation` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(64) NOT NULL,
  `url` varchar(256) NOT NULL,
  `display` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `navigation`
--

INSERT INTO `navigation` (`id`, `title`, `url`, `display`) VALUES
(1, 'Home', '/index.html', 0),
(3, 'About', '/about.html', 2),
(4, 'Admin', '/admin', 3),
(7, 'King', '/King.html', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `url` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`url`) VALUES
('about.html'),
('index.html'),
('King.html');

-- --------------------------------------------------------

--
-- Table structure for table `pluginsettings`
--

CREATE TABLE `pluginsettings` (
  `id` int(11) NOT NULL,
  `pluginid` int(11) NOT NULL,
  `settingkey` varchar(64) NOT NULL,
  `settingvalue` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pluginsettings`
--

INSERT INTO `pluginsettings` (`id`, `pluginid`, `settingkey`, `settingvalue`) VALUES
(1, 2, 'title', 'Das ist ein kleiner <strong>Test</strong>'),
(2, 2, 'content', '<p>Use as many boxes as you like, and put anything you want in them! They are great for just about anything, the sky''s the limit!</p>\r\n<p>Use as many boxes as you like, and put anything you want in them! They are great for just about anything, the sky''s the limit!</p>'),
(3, 3, 'title', 'Das ist mein About! :D'),
(4, 3, 'content', 'Khadim Fall<br>\r\nIch bin student junge! :O<br>\r\nLeeeets get reaady!! :O');

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `memberid` int(11) UNSIGNED NOT NULL,
  `session` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `updated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`memberid`, `session`, `token`, `updated`) VALUES
(1, '4ueWOt26qizPA9ae7gHY8LPc7eg7ZSExHzPQZ9XQzm9EzTXC7J9uNp3eZFIDEnuz', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, '5JwdfGTaeiLiLqu0kUAANe74URf8Xc4auECy7PTLjueeZf3g3A5Tt3eTWmVLrOdK', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, '64tYyBLkTlLpW9NTvQ1OkOkiDW8OqJlIcmpKTAXtC80mxcyZhovnqJcXZ5Kx29kv', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'a2mjBYhrVlsoW6CmMYIvNjvhl644AsOYYlvlEiyRarVfLv2WProeYdafXpcTfctP', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'bO3r3QnKmeftYpmMIjJPhxYwn9Xkf4x8LkhnsIo72a29UFwsnw2GCsPbmY5dO0Wv', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'c7iOO4vtnpj6CwchWAxo8eOZsh5MX1tkCUhGVViszmNn4Nm5G36eltZvHF4Ouq28', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'cXsAOuFl5qjvGEGSFXDgsCHYSPdLzrC60xnFmCleTbWt8Skk0MGwryRFK8YoBh1F', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'dPT228OvkDVpcn2TmHTNBIuHPCRQrv7V6q36czqWRO97geg3EuJgGIyQaUefgvWg', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'fhxFa4gMQJYPORxYOfM737IYKgYYoGsFEVMXGhggyCggmi3zr13ogJuPr0Qiib7F', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'gEroW5wfuUpmiouJ8SxdyzURrgJn7G4w2a2mjCE7dq5IYGF0X6VakQCMIgBazTun', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'gTqJtMKRqQudNdEOKyOYIIS91WbjE4IxyVttNUewyuAarWMekjz5AZBaeYs7ijOl', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'IszXmmzIr70kwFG1JE2FGjd4aYDX3Yn4vlUKX4SMxtBRUBKYB2J1HC6on5DAv1nn', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'joBS0lpAwrPkzjzSH2DAnm07FjxFwEznxXgKfpdNFjBQO6hPMr6sPwQAWFtBb94w', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'K0POpnOKTyhgA3evyek1n5UoKiNc8r6PHWh2AWBmAWCbuRR2RGVdtLe7lHJfAl3e', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'ngkjaxkoMBBDkJruYPMSN57om3UUlbrxlIa0YqVvE6TjTx4Vb0XihfTJsmOLQT7U', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'nLtBz2iBJEk4ldfPiURSr2LIexlVxdMGxw11UUmluIdjOA1xYcuZiQOx6DWoiK1h', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'pjwlX7ZflpawctmhD7apgNPs90eRsc4aoAieiHpCiQ6nG3ObVEuWncFWVpOd3xTV', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'Q4byxsz6lhigvIUrDrnZXENbWL44ghyp1iTohc8aRBcawWAR5qIzYZGIbNBK09CB', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'Rz1246bOtMl8Q6SXoSal5WlTroueSUaDa6Yj8gg6LLj99fyknkmuPL1iR7ninlaz', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'S3OQfBYlmGPesgDxHi76kJ8gn2pqJpPnv4WH65930J0olW2auPmWCtUj7uOGEkWN', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'SdbZbelx1hXwelTrcXPaW7gCqYu9wPMDYwhCFoJLy4lb2tVuBUTBBeMBfwyya1Lu', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'SVgajB42gZiRxN2YfVVdhgduSdYqX1oxmHjMv7AzMWvQ4IgRi4yKut663rMX3jHe', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'UIAEsIM1XQfCtSykKYOab0pO3Up9hKxRdKoYPSTpNGpfhL8VY6cOwoKbqFUx0jwl', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'Vb6laDHt2jOOn3M7OdaiOl7L6QQYkQGbPdIqXm5fKBLfx42ixnKuyNfADX2q0cO2', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'wk4gCKdrDtN2QnhLrF1FV6wCPohUFjSgKxlKe8GKWGLhdMP0EmcYMH97YKcgL5AC', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'wWxoFSeeDT68Sed8Dl4vqEQmJjO9L8Ws7F7TRnw7W1H4vAyez4L5xMHEDFaQzu58', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'wwYgCrENWqg46HSs56AnXmFIyat9NjKHz2FtfYXITr3GEDOr8r0i83vxwDICKgrC', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'Xv3xWtUeZQVWdj76ahW3Fo3AmCxtm956um4wKbpdteYRgDim0IYL8YWDGyr0UQgr', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'y31rIwv8dcXoltwlcUGOb64d9tPDR1xTFXTrOJI799TFtgbmFePZnWEBv7809mKg', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38'),
(1, 'zs5gzSNqNnRusDYXbolyoxXSk3slW9X6zHzqdjbLIkDLNPgEGZZrDWl47JI8WSBY', 'SowdnbNcqXx500SOzf6nnBA7Et84j1EHp5NXTanbxYg8WQa9lywCSJ1qjoPk6aJx', '2016-10-01 23:49:38');

-- --------------------------------------------------------

--
-- Table structure for table `static`
--

CREATE TABLE `static` (
  `placeholder` varchar(64) NOT NULL,
  `value` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `static`
--

INSERT INTO `static` (`placeholder`, `value`) VALUES
('copyright', 'Copyright 2016 Khadim Fall'),
('subtitle', 'My first small Page :)'),
('title', 'Hello World');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `role`, `email`, `firstname`, `lastname`) VALUES
(1, 'admin', '$2y$10$UxfFCgfhBhEWExKXHRnN8.KaEK8QN985xlAGQYpELEZeRAxA09I8y', 'admin', 'admin@admin.com', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attempts`
--
ALTER TABLE `attempts`
  ADD KEY `memberid` (`memberid`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD UNIQUE KEY `pageurl` (`pageurl`,`area`);

--
-- Indexes for table `navigation`
--
ALTER TABLE `navigation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`url`);

--
-- Indexes for table `pluginsettings`
--
ALTER TABLE `pluginsettings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD UNIQUE KEY `session` (`session`),
  ADD KEY `memberid` (`memberid`);

--
-- Indexes for table `static`
--
ALTER TABLE `static`
  ADD UNIQUE KEY `key` (`placeholder`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `navigation`
--
ALTER TABLE `navigation`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `pluginsettings`
--
ALTER TABLE `pluginsettings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `attempts`
--
ALTER TABLE `attempts`
  ADD CONSTRAINT `attempts_ibfk_1` FOREIGN KEY (`memberid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `session_ibfk_1` FOREIGN KEY (`memberid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
