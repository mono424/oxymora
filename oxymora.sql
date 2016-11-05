-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2016 at 04:25 PM
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
-- Table structure for table `addons`
--

CREATE TABLE `addons` (
  `name` varchar(128) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `installed` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `addons`
--

INSERT INTO `addons` (`name`, `active`, `installed`) VALUES
('statistics', 1, '2016-10-18 00:00:50');

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
('about.html', 'body', '{plugin:Text:3}{plugin:Text:57f188883eb8b1.39986556}'),
('index.html', 'body', '{plugin:Slider:1}{plugin:Text:5803c04fe22fb5.28934523}{plugin:Text:5803c04fe5bc78.89612624}{plugin:Text:2}'),
('King.html', 'body', '{plugin:Slider:57f188aef36f94.11803575}{plugin:Text:57f189bc3ffa57.15995102}{plugin:Text:57f18837099d91.62990037}'),
('testy.html', 'body', '');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `group_permissions`
--

CREATE TABLE `group_permissions` (
  `groupid` int(11) NOT NULL,
  `permission` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
('King.html'),
('testy.html');

-- --------------------------------------------------------

--
-- Table structure for table `pluginsettings`
--

CREATE TABLE `pluginsettings` (
  `id` int(11) NOT NULL,
  `pluginid` varchar(32) NOT NULL,
  `settingkey` varchar(64) NOT NULL,
  `settingvalue` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pluginsettings`
--

INSERT INTO `pluginsettings` (`id`, `pluginid`, `settingkey`, `settingvalue`) VALUES
(23, '3', 'title', 'Das ist mein About! :D'),
(24, '3', 'content', 'Khadim Fall<br>\r\nIch bin student junge! :O<br>\r\nLeeeets get reaady!! :O'),
(25, '57f188883eb8b1.39986556', 'title', 'Noch ne Box'),
(26, '57f188883eb8b1.39986556', 'content', 'Hier stehen meine Social Accounts tho! :O'),
(45, '57f189bc3ffa57.15995102', 'title', 'teest'),
(46, '57f189bc3ffa57.15995102', 'content', 'hgjkjgkjhgkjhgk'),
(47, '57f18837099d91.62990037', 'title', 'King'),
(48, '57f18837099d91.62990037', 'content', 'ich bin baba junge test safasdfasf'),
(49, '5803c04fe22fb5.28934523', 'title', 'test'),
(50, '5803c04fe22fb5.28934523', 'content', 'blah'),
(51, '5803c04fe5bc78.89612624', 'title', 'kidneyx'),
(52, '5803c04fe5bc78.89612624', 'content', 'lahsflkasdf'),
(53, '2', 'title', 'Das ist ein kleiner <strong>Test</strong>'),
(54, '2', 'content', '<p>Use as many boxes as you like, and put anything you want in them! They are great for just about anything, the sky\'s the limit!</p>\r\n<p>Use as many boxes as you like, and put anything you want in them! They are great for just about anything, the sky\'s the limit!</p>'),
(59, '5806b4628b5261.03091094', 'title', 'test'),
(60, '5806b4628b5261.03091094', 'content', 'kleiner test');

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
(1, '4ueWOt26qizPA9ae7gHY8LPc7eg7ZSExHzPQZ9XQzm9EzTXC7J9uNp3eZFIDEnuz', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, '5JwdfGTaeiLiLqu0kUAANe74URf8Xc4auECy7PTLjueeZf3g3A5Tt3eTWmVLrOdK', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, '64tYyBLkTlLpW9NTvQ1OkOkiDW8OqJlIcmpKTAXtC80mxcyZhovnqJcXZ5Kx29kv', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, '66gT9Sxp2VmZg5V2HAOp7HvWegjACwYz79UMLQCEcyafhD3vJBHzISg1I7nO6c0S', 'g5JfPzwLxir64UP6euFKZyYgYbmycxldIGAioNl6tiSZTF1y3awhXxAPeklTEi6o', '2016-10-25 23:59:11'),
(1, '8KZr18YCPLvgKBlRW1FCZofRF9WNEH6jmxb5Rc7Pe93THZ88fSa2yrdg67mIxYo5', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, '9DjZLTlJRgU13WrUkJHsMyzAK7olh9eSiRt6FhHhLUv7QWQvJXTtkYglNefEtB7b', 'VHlwwY11f7DJHK1YHB6nWhpSm2ASgJ4HFwpv8iynCZP1gYHl88lRmLKF0795Ib6k', '2016-11-02 13:10:59'),
(1, '9Fyl4BJ33qxKdWtxXt7O5h0u0nXfrC2PJQYWyr7ewIQqNvh5ZwP3661JFv1yrDBK', 'izLRncm10kKpRDs2WJzSTTalRcT8eK25w3VLqpzol9D3kHKkeRdUBbKUJMUuewWC', '2016-11-02 10:30:26'),
(1, 'a2mjBYhrVlsoW6CmMYIvNjvhl644AsOYYlvlEiyRarVfLv2WProeYdafXpcTfctP', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'bO3r3QnKmeftYpmMIjJPhxYwn9Xkf4x8LkhnsIo72a29UFwsnw2GCsPbmY5dO0Wv', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'c7iOO4vtnpj6CwchWAxo8eOZsh5MX1tkCUhGVViszmNn4Nm5G36eltZvHF4Ouq28', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'Cg0DsB4UCiOSWKFQP46WCgrO0SCFuKxvrsZyP9K4ZM65Sa2USeOX6kSWC8kqIPMk', 'F5iJwPd4LEcz3YDjtFK9vk5KvBxclJSloZeY2WsrP8ANpG8O63QBwThcVUXNozX1', '2016-10-18 22:29:18'),
(1, 'cXsAOuFl5qjvGEGSFXDgsCHYSPdLzrC60xnFmCleTbWt8Skk0MGwryRFK8YoBh1F', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'dpIT6itE5YlumvvmKXLcdMBoEXmvS0TQANhP2LdlAfP8P9y6wbBPxTsVZM9w7xu1', '79ypXgAD9Z5UA5ELxssYOej8bm5rld2lY5NgSTYgFDsdip6VCeDvD3bS9w3E5xUA', '2016-10-23 21:43:21'),
(1, 'dPT228OvkDVpcn2TmHTNBIuHPCRQrv7V6q36czqWRO97geg3EuJgGIyQaUefgvWg', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'dVZfHHfdTzQParrpUMuA0Hq3eNAAndyl5ouU7iHF2LkPEfJcwZkFn5coRWSh6B1U', '5Q3cgOng2OhPGfbOAPDnGJUnrIUTw9k0PBq3B9KVEu5IJxNBB7bXXNX9AlmGyZ3o', '2016-10-22 18:15:22'),
(1, 'EfUnnf3yM1lnmxwo29jDcytpWlHqfhDRuexrvScRndJfro1nBcSgjjzCuesW0Edo', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'fhxFa4gMQJYPORxYOfM737IYKgYYoGsFEVMXGhggyCggmi3zr13ogJuPr0Qiib7F', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'gEroW5wfuUpmiouJ8SxdyzURrgJn7G4w2a2mjCE7dq5IYGF0X6VakQCMIgBazTun', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'gTqJtMKRqQudNdEOKyOYIIS91WbjE4IxyVttNUewyuAarWMekjz5AZBaeYs7ijOl', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'hy5gwDzMvkqdAApJgrDkmatQSEEm6iisjqnFxwULr9C9RRsB3FomA6BvZ7SOTJ9j', 'uVNxCo1pyrcvBa3ks69IzaZXWeL3AyaCRk8Flu0lcewsqF8nd8VjrX2pvpei5nY7', '2016-10-19 22:55:57'),
(1, 'iAjsOZ7EiViZivBQ4MSDSJ0ksxFThJLIAdJ27qibCBn7F8jOiGa5uYm4vp4R7wor', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'IszXmmzIr70kwFG1JE2FGjd4aYDX3Yn4vlUKX4SMxtBRUBKYB2J1HC6on5DAv1nn', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'joBS0lpAwrPkzjzSH2DAnm07FjxFwEznxXgKfpdNFjBQO6hPMr6sPwQAWFtBb94w', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'K0POpnOKTyhgA3evyek1n5UoKiNc8r6PHWh2AWBmAWCbuRR2RGVdtLe7lHJfAl3e', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'KauR6y6rr1NNENXBe74IlmgyDzrDnhmlZiisWuALhlHZXkRTYFeGY97BhGiWbmky', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'LznZpvekFDSJUpe4XgYKtPpaAluNxO10FFrnK6Vg0oFmz8rILsFoo8f6579fX5Yk', '37HDy55HJZF1GALAG5UjuPCsH6xXVfyFHgwgf3LDneCRsmHUkUxNkEkZaOvnXglL', '2016-10-23 20:00:43'),
(1, 'MbMI3Tmm7vIIJMBKBf7nj3Wrl373CarYOIYsnqArFham8eayThep2mrc1xzbwyJS', 'UZoi8JTj9pgok4JqYFQMNhNAwFBU1kCvQc2RUbYqlvQKCPXfWAbDGnXvIsVzOinL', '2016-10-23 21:42:28'),
(1, 'ngkjaxkoMBBDkJruYPMSN57om3UUlbrxlIa0YqVvE6TjTx4Vb0XihfTJsmOLQT7U', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'nLtBz2iBJEk4ldfPiURSr2LIexlVxdMGxw11UUmluIdjOA1xYcuZiQOx6DWoiK1h', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'opk3TgjxxG7qzmtujAN52E6numHd3vp2UeDUDVUlfi1xheM65hEAD265A9a1YbaV', 'Wt8WLP7aEIyuU64IQxASfjgIHhotr6lSuw5XDHs5lvVzErBNxTvkQJZC2OdJWaaH', '2016-11-02 10:37:11'),
(1, 'oqmmPyxcW8KYlMEAhtvFsMRbMFtGNsVmzLAcgmm5nS8YamgBDGlJ0Ss9pCMXMcr9', 'SWKOakXkk8ZRDelISvXbAOJuCeKvhkszdmitd4MS6KoEWuOFbGlRolifiTB9OB8m', '2016-10-27 23:51:37'),
(1, 'pEI7ZjUDOTqHlAyNtCtIBffg0BOFMazDLtu0w1my4AJepi1eMtP5VtRbzz1xWUC6', 'h5SsSsIRiOhFatQhXmeAr1AJrkHCGS70cMVIwfnO3BMVotEVJgJmFWeR3CcdmzbF', '2016-11-04 21:30:24'),
(1, 'pjwlX7ZflpawctmhD7apgNPs90eRsc4aoAieiHpCiQ6nG3ObVEuWncFWVpOd3xTV', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'Q4byxsz6lhigvIUrDrnZXENbWL44ghyp1iTohc8aRBcawWAR5qIzYZGIbNBK09CB', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'Rz1246bOtMl8Q6SXoSal5WlTroueSUaDa6Yj8gg6LLj99fyknkmuPL1iR7ninlaz', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'S3OQfBYlmGPesgDxHi76kJ8gn2pqJpPnv4WH65930J0olW2auPmWCtUj7uOGEkWN', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'SdbZbelx1hXwelTrcXPaW7gCqYu9wPMDYwhCFoJLy4lb2tVuBUTBBeMBfwyya1Lu', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'SVgajB42gZiRxN2YfVVdhgduSdYqX1oxmHjMv7AzMWvQ4IgRi4yKut663rMX3jHe', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'TOVHDkMGprFSa7hCzH1mvDrIpaaQy2P0MDwGrQAoTiys0SZqDTkjtXEChZWyRzbv', 'dZiSlWNA7l6ahKEBcM93ccucjVvSex30HFmrou5dyRP97978YJgVWqgPRXosztYG', '2016-10-24 01:45:21'),
(1, 'UIAEsIM1XQfCtSykKYOab0pO3Up9hKxRdKoYPSTpNGpfhL8VY6cOwoKbqFUx0jwl', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'Vb6laDHt2jOOn3M7OdaiOl7L6QQYkQGbPdIqXm5fKBLfx42ixnKuyNfADX2q0cO2', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'wk4gCKdrDtN2QnhLrF1FV6wCPohUFjSgKxlKe8GKWGLhdMP0EmcYMH97YKcgL5AC', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'wWxoFSeeDT68Sed8Dl4vqEQmJjO9L8Ws7F7TRnw7W1H4vAyez4L5xMHEDFaQzu58', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'wwYgCrENWqg46HSs56AnXmFIyat9NjKHz2FtfYXITr3GEDOr8r0i83vxwDICKgrC', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'XQ74cYRV7GSR9JGjM1q9yPnRpyTERa3elsH5esZzuj9492Guw0T1JgyYCXivxhtN', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'Xv3xWtUeZQVWdj76ahW3Fo3AmCxtm956um4wKbpdteYRgDim0IYL8YWDGyr0UQgr', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'y31rIwv8dcXoltwlcUGOb64d9tPDR1xTFXTrOJI799TFtgbmFePZnWEBv7809mKg', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'YXTF0jkKplvqyPiprgqBTR6RsfU8E3xjPYnsRE37M1dih3ElnMP8vJIobXv7eOnw', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'zs5gzSNqNnRusDYXbolyoxXSk3slW9X6zHzqdjbLIkDLNPgEGZZrDWl47JI8WSBY', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42');

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
-- Table structure for table `statistics_visits`
--

CREATE TABLE `statistics_visits` (
  `id` int(10) UNSIGNED NOT NULL,
  `page` varchar(256) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `browser` varchar(30) DEFAULT NULL,
  `time` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `statistics_visits`
--

INSERT INTO `statistics_visits` (`id`, `page`, `ip`, `browser`, `time`) VALUES
(1, 'index.html', '192.168.178.33', 'Chrome', '2016-10-17 00:24:47'),
(2, 'about.html', '192.168.178.33', 'Chrome', '2016-10-17 00:25:26'),
(3, 'index.html', '::1', 'Chrome', '2016-10-17 01:17:19'),
(4, 'about.html', '::1', 'Chrome', '2016-10-18 01:20:50'),
(5, 'about.html', '::1', 'Chrome', '2016-10-18 01:21:56'),
(6, 'King.html', '::1', 'Chrome', '2016-10-18 01:22:42'),
(7, 'about.html', '192.168.178.33', 'Chrome', '2016-10-18 01:22:58'),
(8, 'index.html', '::1', 'Chrome', '2016-10-18 01:25:28'),
(9, 'index.html', '::1', 'Firefox', '2016-10-18 01:25:35'),
(10, 'King.html', '::1', 'Firefox', '2016-10-18 01:25:49'),
(11, 'index.html', '::1', 'Chrome', '2016-10-18 17:06:18'),
(12, 'index.html', '::1', 'Chrome', '2016-10-18 17:06:42'),
(13, 'index.html', '::1', 'Chrome', '2016-10-18 17:06:46'),
(14, 'index.html', '::1', 'Chrome', '2016-10-18 22:29:05'),
(15, 'index.html', '::1', 'Chrome', '2016-10-19 00:05:44'),
(16, 'index.html', '::1', 'Chrome', '2016-10-19 00:06:04'),
(17, 'index.html', '::1', 'Chrome', '2016-10-19 01:45:10'),
(18, 'index.html', '::1', 'Chrome', '2016-10-19 01:45:18'),
(19, 'testy.html', '::1', 'Chrome', '2016-10-19 01:45:20'),
(20, 'testy.html', '::1', 'Chrome', '2016-10-19 01:45:28'),
(21, 'about.html', '::1', 'Chrome', '2016-10-19 01:45:29'),
(22, 'King.html', '::1', 'Chrome', '2016-10-19 01:45:30'),
(23, 'index.html', '::1', 'Chrome', '2016-10-19 01:45:31'),
(24, 'about.html', '::1', 'Chrome', '2016-10-19 01:45:33'),
(25, 'testy.html', '::1', 'Chrome', '2016-10-19 01:45:35'),
(26, 'testy.html', '::1', 'Chrome', '2016-10-19 01:45:53'),
(27, 'testy.html', '::1', 'Chrome', '2016-10-19 01:46:15'),
(28, 'testy.html', '::1', 'Chrome', '2016-10-19 01:46:18'),
(29, 'testy.html', '::1', 'Chrome', '2016-10-19 01:46:46'),
(30, 'testy.html', '::1', 'Chrome', '2016-10-19 01:47:01'),
(31, 'testy.html', '::1', 'Chrome', '2016-10-19 01:47:09'),
(32, 'testy.html', '::1', 'Chrome', '2016-10-19 01:47:19'),
(33, 'index.html', '::1', 'Chrome', '2016-10-19 10:07:21'),
(34, 'index.html', '::1', 'Chrome', '2016-10-19 22:55:47'),
(35, 'index.html', '::1', 'Chrome', '2016-10-19 23:08:49'),
(36, 'index.html', '::1', 'Chrome', '2016-10-20 00:07:11'),
(37, 'about.html', '::1', 'Chrome', '2016-10-20 00:07:24'),
(38, 'index.html', '::1', 'Chrome', '2016-10-21 21:09:16'),
(39, 'King.html', '::1', 'Chrome', '2016-10-21 21:09:20'),
(40, 'about.html', '::1', 'Chrome', '2016-10-21 21:09:21'),
(41, 'index.html', 'localhost', 'Chrome', '2016-10-21 23:58:06'),
(42, 'index.html', 'localhost', 'Chrome', '2016-10-22 11:06:06'),
(43, 'index.html', 'localhost', 'Chrome', '2016-10-22 18:05:03'),
(44, 'index.html', 'localhost', 'Chrome', '2016-10-22 18:15:51'),
(45, 'index.html', 'localhost', 'Chrome', '2016-10-22 18:15:55'),
(46, 'index.html', 'localhost', 'Chrome', '2016-10-23 20:00:36'),
(47, 'index.html', 'localhost', 'Chrome', '2016-10-25 22:56:54'),
(48, 'index.html', 'localhost', 'Chrome', '2016-10-27 23:49:14'),
(49, 'index.html', 'localhost', 'Chrome', '2016-11-02 10:30:15'),
(50, 'index.html', 'localhost', 'Chrome', '2016-11-02 13:10:51'),
(51, 'index.html', 'localhost', 'Chrome', '2016-11-04 21:30:16'),
(52, 'index.html', 'localhost', 'Chrome', '2016-11-04 21:30:16'),
(53, 'index.html', 'localhost', 'Chrome', '2016-11-04 22:00:58'),
(54, 'index.html', 'localhost', 'Chrome', '2016-11-04 22:32:14'),
(55, 'index.html', 'localhost', 'Chrome', '2016-11-04 22:32:14'),
(56, 'index.html', 'localhost', 'Chrome', '2016-11-05 00:16:49');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `usergroup` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `usergroup`, `email`, `image`) VALUES
(1, 'admin', '$2y$10$UxfFCgfhBhEWExKXHRnN8.KaEK8QN985xlAGQYpELEZeRAxA09I8y', 'admin', 'admin@admin.com', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addons`
--
ALTER TABLE `addons`
  ADD PRIMARY KEY (`name`);

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
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `group_permissions`
--
ALTER TABLE `group_permissions`
  ADD PRIMARY KEY (`groupid`,`permission`) USING BTREE;

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
-- Indexes for table `statistics_visits`
--
ALTER TABLE `statistics_visits`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `navigation`
--
ALTER TABLE `navigation`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `pluginsettings`
--
ALTER TABLE `pluginsettings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `statistics_visits`
--
ALTER TABLE `statistics_visits`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
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
