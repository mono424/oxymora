-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2016 at 09:22 AM
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
-- Table structure for table `accounting_customer`
--

CREATE TABLE `accounting_customer` (
  `id` int(11) UNSIGNED NOT NULL,
  `firstname` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `street` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `plz` int(8) DEFAULT NULL,
  `ort` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounting_customer`
--

INSERT INTO `accounting_customer` (`id`, `firstname`, `lastname`, `street`, `plz`, `ort`, `email`, `created`) VALUES
(440005, 'Lukas', 'Fehling', 'Hirschgartenallee 28', 80639, 'München', 'info@lukasfehling.com', '2016-11-16 02:17:11');

-- --------------------------------------------------------

--
-- Table structure for table `accounting_invoices`
--

CREATE TABLE `accounting_invoices` (
  `id` int(11) UNSIGNED NOT NULL,
  `file` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer` int(11) NOT NULL,
  `items` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL,
  `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounting_invoices`
--

INSERT INTO `accounting_invoices` (`id`, `file`, `customer`, `items`, `status`, `created`) VALUES
(100001, 'invoice-100001.pdf', 440005, '[{"description":"PHP-Entwicklung","amount":"20","amount-type":"Stunden","price":"20.00"}]', 2, '2016-11-07 20:09:10'),
(100002, 'invoice-100002.pdf', 440005, '[{"description":"PHP-Entwicklung","amount":"74","amount-type":"Stunden","price":"20.00"}]', 2, '2016-11-30 21:36:34');

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

CREATE TABLE `addons` (
  `name` varchar(128) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `installed` TIMESTAMP NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `addons`
--

INSERT INTO `addons` (`name`, `active`, `installed`) VALUES
('accounting', 1, '2016-11-06 22:50:51'),
('statistics', 1, '2016-10-18 00:00:50');

-- --------------------------------------------------------

--
-- Table structure for table `attempts`
--

CREATE TABLE `attempts` (
  `memberid` int(11) UNSIGNED NOT NULL,
  `ip` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
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
('index.html', 'body', '');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `color` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `color`) VALUES
(1, 'Admin', 'rgb(101, 191, 129)'),
(2, 'Manager', 'rgb(77, 186, 193)');

-- --------------------------------------------------------

--
-- Table structure for table `group_permissions`
--

CREATE TABLE `group_permissions` (
  `groupid` int(11) NOT NULL,
  `permission` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `group_permissions`
--

INSERT INTO `group_permissions` (`groupid`, `permission`) VALUES
(1, 'root'),
(2, 'oxymora_addon_statistics'),
(2, 'oxymora_dashboard'),
(2, 'oxymora_pages');

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
(4, 'Admin', '/admin', 1);

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
('index.html');

-- --------------------------------------------------------

--
-- Table structure for table `pluginsettings`
--

CREATE TABLE `pluginsettings` (
  `id` int(11) NOT NULL,
  `pluginid` varchar(32) NOT NULL,
  `settingkey` varchar(64) NOT NULL,
  `settingvalue` text NOT NULL,
  `settingtype` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `memberid` int(11) UNSIGNED NOT NULL,
  `session` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`memberid`, `session`, `token`, `updated`) VALUES
(1, '3ZlP7TS8JRvo5xJWS4OtE8EDkf43hTOqQjpCvwYTlM7G0dGmqdJzoQZF8rzY3T3f', '4dfc8e8i2XsIA8ua9RAD3bbJtqox0R3jOIhBfLply7IxDlJhl9FEluGQTeOvzjeq', '2016-11-15 02:03:39'),
(1, '4ueWOt26qizPA9ae7gHY8LPc7eg7ZSExHzPQZ9XQzm9EzTXC7J9uNp3eZFIDEnuz', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, '509xVPIIhS01idaegFg04BSASPBREBi06yZE5PIPruxWL6730286D22QVmVJDbco', 'nRmec1fllbC0EwriZWNgpUHUPm6u1KrM9vxrQFBvxxH8OOYdYt8p32jvVKFyNLc9', '2016-12-06 23:45:04'),
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
(8, 'dr30TDwNXtsm7eyckqqJq0gAKpSg1yidryszse7GT80FtQpwBvmANYTNU6GxpRRj', 'DntAqIdfXmicNA3GR6ybqj35GQ9H7mhglCfJ7TZuyaYnDYQNFSPt4qH1btExIA8R', '2016-11-15 02:20:38'),
(1, 'dVZfHHfdTzQParrpUMuA0Hq3eNAAndyl5ouU7iHF2LkPEfJcwZkFn5coRWSh6B1U', '5Q3cgOng2OhPGfbOAPDnGJUnrIUTw9k0PBq3B9KVEu5IJxNBB7bXXNX9AlmGyZ3o', '2016-10-22 18:15:22'),
(8, 'EbTaBSj15xWciBAGQC8CmccANqjXnOB9DkLhGcKGya6MMotbyyJy0gDxuOMjbZEi', 'SgXiz2agXs5UkMBGAQLWW8Hp6BIqy7YmHroYScP6nYoCoxVcUA4O1Fd3SrusLRpW', '2016-11-15 02:40:42'),
(1, 'EfUnnf3yM1lnmxwo29jDcytpWlHqfhDRuexrvScRndJfro1nBcSgjjzCuesW0Edo', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'fhxFa4gMQJYPORxYOfM737IYKgYYoGsFEVMXGhggyCggmi3zr13ogJuPr0Qiib7F', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'gEroW5wfuUpmiouJ8SxdyzURrgJn7G4w2a2mjCE7dq5IYGF0X6VakQCMIgBazTun', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'gTqJtMKRqQudNdEOKyOYIIS91WbjE4IxyVttNUewyuAarWMekjz5AZBaeYs7ijOl', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'GXmx3YWTUYqhOURlS4nsSCtm4C1aqtxB2IRCr1L3ASMpzhIzkrf0yQvyd4XLESnP', '63YZHrqHNyiLLi5DSkkzn2vD6hWkr1q40qIVW2GQxFqywLS9vRkhIjss27RD1elZ', '2016-11-07 00:48:36'),
(1, 'hy5gwDzMvkqdAApJgrDkmatQSEEm6iisjqnFxwULr9C9RRsB3FomA6BvZ7SOTJ9j', 'uVNxCo1pyrcvBa3ks69IzaZXWeL3AyaCRk8Flu0lcewsqF8nd8VjrX2pvpei5nY7', '2016-10-19 22:55:57'),
(1, 'iAjsOZ7EiViZivBQ4MSDSJ0ksxFThJLIAdJ27qibCBn7F8jOiGa5uYm4vp4R7wor', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'IszXmmzIr70kwFG1JE2FGjd4aYDX3Yn4vlUKX4SMxtBRUBKYB2J1HC6on5DAv1nn', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'joBS0lpAwrPkzjzSH2DAnm07FjxFwEznxXgKfpdNFjBQO6hPMr6sPwQAWFtBb94w', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'K0POpnOKTyhgA3evyek1n5UoKiNc8r6PHWh2AWBmAWCbuRR2RGVdtLe7lHJfAl3e', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'KauR6y6rr1NNENXBe74IlmgyDzrDnhmlZiisWuALhlHZXkRTYFeGY97BhGiWbmky', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(8, 'lnlyfbFAbqWvZ41Rx6joLpA5lszjdWMM62erQLOR8sKDOnljgrTSi708gRS5Pv5g', 'TyXfKSmaEyGOJ5IvaA0wTV6Tfs36Nu1aPKZa31QlHd0Du7TYJw4sFK57sP3mTktO', '2016-11-07 00:49:18'),
(1, 'lsbli8bTAlNRYBVmGd42HdJ8MQfGXVNyip7t7nwyMZKtGZjudp5DAKOV9OVQQmCB', 'piEG1gYcwfs37IQXFOhr0NuChvHJuleMbqpZLLlEzrZEVI3SkMad9T6LgyZidJst', '2016-11-15 02:43:12'),
(1, 'LznZpvekFDSJUpe4XgYKtPpaAluNxO10FFrnK6Vg0oFmz8rILsFoo8f6579fX5Yk', '37HDy55HJZF1GALAG5UjuPCsH6xXVfyFHgwgf3LDneCRsmHUkUxNkEkZaOvnXglL', '2016-10-23 20:00:43'),
(1, 'MbMI3Tmm7vIIJMBKBf7nj3Wrl373CarYOIYsnqArFham8eayThep2mrc1xzbwyJS', 'UZoi8JTj9pgok4JqYFQMNhNAwFBU1kCvQc2RUbYqlvQKCPXfWAbDGnXvIsVzOinL', '2016-10-23 21:42:28'),
(1, 'mf26Nj657EOLkQnRygOM7AYOsEa3B16mcVKX3adsp6qp0LZSWcm7PiWTgJosMPvz', '483xZXI1oFIiatXfZrE33bl18ZX3LORuGKmyc9DAalo5h2Ky3G62uUuDgjrHhhK5', '2016-11-15 02:19:42'),
(1, 'ngkjaxkoMBBDkJruYPMSN57om3UUlbrxlIa0YqVvE6TjTx4Vb0XihfTJsmOLQT7U', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'nLtBz2iBJEk4ldfPiURSr2LIexlVxdMGxw11UUmluIdjOA1xYcuZiQOx6DWoiK1h', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(8, 'nPy4pqARBzO92C1NJs0yJnXilNptLXUyFG2MYqeTdUA6UyVSf3sfhLsHOLkSHd4v', 'IXjxthVR0MiuO4UfjEHwx49opZ8M2RvUWqkRfEqEQjuTAAhb63g1w9Ij7rVNHGZw', '2016-11-07 00:49:38'),
(1, 'nUUfIfmf4w9Xtqnk4fvRdX9RQZf6yAiinENJdzIrM3yeWu5p1WhG2jpWtGP6WgkS', 'WkkOOynRjFYik1ZfHEeE6J6Mvg7jPIxxyaOwLTN7Qb5wVU0XT8c4mOcYCHsxw3ip', '2016-11-15 02:54:54'),
(1, 'opk3TgjxxG7qzmtujAN52E6numHd3vp2UeDUDVUlfi1xheM65hEAD265A9a1YbaV', 'Wt8WLP7aEIyuU64IQxASfjgIHhotr6lSuw5XDHs5lvVzErBNxTvkQJZC2OdJWaaH', '2016-11-02 10:37:11'),
(1, 'oqmmPyxcW8KYlMEAhtvFsMRbMFtGNsVmzLAcgmm5nS8YamgBDGlJ0Ss9pCMXMcr9', 'SWKOakXkk8ZRDelISvXbAOJuCeKvhkszdmitd4MS6KoEWuOFbGlRolifiTB9OB8m', '2016-10-27 23:51:37'),
(1, 'pEI7ZjUDOTqHlAyNtCtIBffg0BOFMazDLtu0w1my4AJepi1eMtP5VtRbzz1xWUC6', 'h5SsSsIRiOhFatQhXmeAr1AJrkHCGS70cMVIwfnO3BMVotEVJgJmFWeR3CcdmzbF', '2016-11-04 21:30:24'),
(8, 'PfoNNWJRWJP7YwrHI2ErhvMCmSYkrc1ysjtwTCOLGnxfi3XNiyNQzRR9t22N1oEk', 'h8Itdms4ASX5maHZqgQzbl7dyWNBRIyjqPGVBIjKeqYXUaUKX5ucFFFWg6iLHq0M', '2016-11-15 02:50:59'),
(1, 'pjwlX7ZflpawctmhD7apgNPs90eRsc4aoAieiHpCiQ6nG3ObVEuWncFWVpOd3xTV', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'Q4byxsz6lhigvIUrDrnZXENbWL44ghyp1iTohc8aRBcawWAR5qIzYZGIbNBK09CB', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'RAu8SgQxM9uV8x14AX0WCWLWXmliwtEO9TsrcdXFxZ0FtE0wMnFQTL8MpdBQwPVo', '1uWzvRzT6s94PIw9BdmoYjDNNSBx92p7mHsYFGK71I12t8UGhi0fWLYl7qZhk6Rt', '2016-12-06 23:45:45'),
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
(1, 'XofpzVihStwMIDIEl8uOmdcd1AIovwa78GMvTbR8SioErqR4idQ2FByKpYDlLvGG', '0mRbjNfpfGlEjRdMb9KSaSuTrxzkZDn4Eg6R2EUY8k9t4PriFzfdydZAK63x0iyr', '2016-11-22 21:20:13'),
(1, 'XQ74cYRV7GSR9JGjM1q9yPnRpyTERa3elsH5esZzuj9492Guw0T1JgyYCXivxhtN', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'Xv3xWtUeZQVWdj76ahW3Fo3AmCxtm956um4wKbpdteYRgDim0IYL8YWDGyr0UQgr', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'y31rIwv8dcXoltwlcUGOb64d9tPDR1xTFXTrOJI799TFtgbmFePZnWEBv7809mKg', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'YXTF0jkKplvqyPiprgqBTR6RsfU8E3xjPYnsRE37M1dih3ElnMP8vJIobXv7eOnw', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'zs5gzSNqNnRusDYXbolyoxXSk3slW9X6zHzqdjbLIkDLNPgEGZZrDWl47JI8WSBY', '1hNIP6GcHOiFFKnyd0C935VQ4ZKuESc2YayM3MdRE9MBzxv4AIxpjXXaKwr6MT6m', '2016-10-17 21:13:42'),
(1, 'zuyXKXVtKPb8a3WsLK2cW6eykGvzphUmbSWiCnuGdRkOjbNyF9kvbeMWzCr3PqVS', '7Bh7ZlUSPkyFHasx6uQ8Jq1RI1nqQT7h8w7e0gAkDW4YPHHbk7HGmH4eEds72N6g', '2016-11-15 02:55:06');

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
  `time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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
(56, 'index.html', 'localhost', 'Chrome', '2016-11-05 00:16:49'),
(57, 'index.html', 'localhost', 'Chrome', '2016-11-05 23:29:07'),
(58, 'index.html', 'localhost', 'Chrome', '2016-11-05 23:29:07'),
(59, 'index.html', 'localhost', 'Chrome', '2016-11-06 16:31:48'),
(60, 'index.html', 'localhost', 'Chrome', '2016-11-07 00:32:52'),
(61, 'index.html', 'localhost', 'Chrome', '2016-11-07 20:04:03'),
(62, 'index.html', 'localhost', 'Chrome', '2016-11-09 00:13:53'),
(63, 'index.html', 'localhost', 'Chrome', '2016-11-09 13:38:32'),
(64, 'index.html', 'localhost', 'Chrome', '2016-11-11 14:52:24'),
(65, 'index.html', 'localhost', 'Chrome', '2016-11-11 14:52:24'),
(66, 'index.html', 'localhost', 'Chrome', '2016-11-11 23:03:40'),
(67, 'about.html', 'localhost', 'Chrome', '2016-11-11 23:03:42'),
(68, 'index.html', 'localhost', 'Chrome', '2016-11-15 00:12:13'),
(69, 'index.html', 'localhost', 'Chrome', '2016-11-15 00:12:13'),
(70, 'index.html', 'localhost', 'Chrome', '2016-11-15 01:12:34'),
(71, 'index.html', 'localhost', 'Chrome', '2016-11-15 01:12:34'),
(72, 'index.html', '192.168.178.33', 'Chrome', '2016-11-15 02:40:27'),
(73, 'index.html', 'localhost', 'Chrome', '2016-11-15 02:54:23'),
(74, 'index.html', 'localhost', 'Chrome', '2016-11-15 02:54:24'),
(75, 'index.html', 'localhost', 'Chrome', '2016-11-15 22:20:53'),
(76, 'index.html', 'localhost', 'Chrome', '2016-11-15 23:08:37'),
(77, 'King.html', 'localhost', 'Chrome', '2016-11-15 23:08:42'),
(78, 'index.html', 'localhost', 'Chrome', '2016-11-16 00:56:39'),
(79, 'index.html', 'localhost', 'Chrome', '2016-11-16 00:56:39'),
(80, 'index.html', 'localhost', 'Chrome', '2016-11-16 02:15:30'),
(81, 'index.html', 'localhost', 'Chrome', '2016-11-16 02:15:47'),
(82, 'index.html', 'localhost', 'Chrome', '2016-11-16 02:15:53'),
(83, 'index.html', 'localhost', 'Chrome', '2016-11-16 02:16:02'),
(84, 'King.html', 'localhost', 'Chrome', '2016-11-16 02:18:46'),
(85, 'index.html', 'localhost', 'Chrome', '2016-11-16 23:16:05'),
(86, 'index.html', 'localhost', 'Chrome', '2016-11-16 23:16:05'),
(87, 'index.html', 'localhost', 'Chrome', '2016-11-17 01:40:33'),
(88, 'index.html', 'localhost', 'Chrome', '2016-11-17 01:40:33'),
(89, 'index.html', 'localhost', 'Chrome', '2016-11-17 17:17:57'),
(90, 'index.html', 'localhost', 'Chrome', '2016-11-18 11:38:40'),
(91, 'index.html', 'localhost', 'Chrome', '2016-11-18 11:38:40'),
(92, 'index.html', 'localhost', 'Chrome', '2016-11-22 21:13:24'),
(93, 'index.html', '127.0.0.1', 'Chrome', '2016-11-22 21:13:24'),
(94, 'index.html', 'localhost', 'Chrome', '2016-11-22 21:20:49'),
(95, 'index.html', 'localhost', 'Chrome', '2016-11-24 00:37:25'),
(96, 'index.html', 'localhost', 'Chrome', '2016-11-24 00:37:25'),
(97, 'index.html', 'localhost', 'Chrome', '2016-11-24 01:36:20'),
(98, 'index.html', 'localhost', 'Chrome', '2016-11-24 01:36:22'),
(99, 'index.html', 'localhost', 'Chrome', '2016-11-24 01:37:32'),
(100, 'index.html', 'localhost', 'Chrome', '2016-11-24 01:37:45'),
(101, 'index.html', 'localhost', 'Chrome', '2016-11-24 01:37:46'),
(102, 'index.html', 'localhost', 'Chrome', '2016-11-24 01:37:47'),
(103, 'index.html', 'localhost', 'Chrome', '2016-11-24 01:37:47'),
(104, 'index.html', 'localhost', 'Chrome', '2016-11-24 01:38:11'),
(105, 'index.html', 'localhost', 'Chrome', '2016-11-24 01:39:43'),
(106, 'index.html', 'localhost', 'Chrome', '2016-11-24 01:39:44'),
(107, 'index.html', 'localhost', 'Chrome', '2016-11-24 01:39:44'),
(108, 'index.html', 'localhost', 'Chrome', '2016-11-24 01:41:58'),
(109, 'index.html', 'localhost', 'Chrome', '2016-11-24 01:42:48'),
(110, 'index.html', 'localhost', 'Chrome', '2016-11-24 01:43:01'),
(111, 'index.html', 'localhost', 'Chrome', '2016-11-24 01:43:35'),
(112, 'index.html', 'localhost', 'Chrome', '2016-11-24 01:51:30'),
(113, 'index.html', 'localhost', 'Chrome', '2016-11-24 01:55:22'),
(114, 'index.html', 'localhost', 'Chrome', '2016-11-24 02:11:55'),
(115, 'index.html', 'localhost', 'Chrome', '2016-11-24 02:17:21'),
(116, 'index.html', 'localhost', 'Chrome', '2016-11-24 02:17:55'),
(117, 'index.html', 'localhost', 'Chrome', '2016-11-24 02:18:13'),
(118, 'index.html', 'localhost', 'Chrome', '2016-11-24 02:18:29'),
(119, 'index.html', 'localhost', 'Chrome', '2016-11-24 02:27:29'),
(120, 'index.html', 'localhost', 'Chrome', '2016-11-24 02:28:40'),
(121, 'index.html', 'localhost', 'Chrome', '2016-11-24 02:31:30'),
(122, 'index.html', 'localhost', 'Chrome', '2016-11-24 02:31:46'),
(123, 'index.html', 'localhost', 'Chrome', '2016-11-24 02:31:52'),
(124, 'index.html', 'localhost', 'Chrome', '2016-11-24 02:32:31'),
(125, 'index.html', 'localhost', 'Chrome', '2016-11-24 02:33:20'),
(126, 'index.html', 'localhost', 'Chrome', '2016-11-24 02:35:35'),
(127, 'index.html', 'localhost', 'Chrome', '2016-11-24 02:35:38'),
(128, 'index.html', 'localhost', 'Chrome', '2016-11-24 02:36:14'),
(129, 'index.html', 'localhost', 'Chrome', '2016-11-24 02:43:55'),
(130, 'index.html', 'localhost', 'Chrome', '2016-11-29 21:58:23'),
(131, 'index.html', 'localhost', 'Chrome', '2016-11-29 23:07:57'),
(132, 'member.html', 'localhost', 'Chrome', '2016-11-29 23:13:25'),
(133, 'index.html', 'localhost', 'Chrome', '2016-11-30 21:32:08'),
(134, 'index.html', 'localhost', 'Chrome', '2016-11-30 21:32:08'),
(135, 'index.html', 'localhost', 'Chrome', '2016-12-01 19:54:06'),
(136, 'index.html', 'localhost', 'Chrome', '2016-12-01 19:54:08'),
(137, 'index.html', 'localhost', 'Chrome', '2016-12-06 13:03:12'),
(138, 'index.html', 'localhost', 'Chrome', '2016-12-06 13:03:12'),
(139, 'index.html', 'localhost', 'Chrome', '2016-12-06 13:04:52'),
(140, 'index.html', 'localhost', 'Chrome', '2016-12-06 21:03:45'),
(141, 'index.html', 'localhost', 'Chrome', '2016-12-06 21:03:45'),
(142, 'index.html', 'localhost', 'Chrome', '2016-12-06 21:37:52'),
(143, 'index.html', 'localhost', 'Chrome', '2016-12-06 21:38:20'),
(144, 'index.html', 'localhost', 'Chrome', '2016-12-06 21:38:20'),
(145, 'index.html', 'localhost', 'Chrome', '2016-12-06 21:39:09'),
(146, 'index.html', 'localhost', 'Chrome', '2016-12-06 21:39:27'),
(147, 'index.html', 'localhost', 'Chrome', '2016-12-06 21:50:59'),
(148, 'index.html', 'localhost', 'Chrome', '2016-12-06 21:52:28'),
(149, 'index.html', 'localhost', 'Chrome', '2016-12-06 21:52:30'),
(150, 'index.html', 'localhost', 'Chrome', '2016-12-06 21:52:31'),
(151, 'index.html', 'localhost', 'Chrome', '2016-12-06 21:52:31'),
(152, 'index.html', 'localhost', 'Chrome', '2016-12-06 21:52:45'),
(153, 'index.html', 'localhost', 'Chrome', '2016-12-06 21:53:03'),
(154, 'index.html', 'localhost', 'Chrome', '2016-12-06 21:58:07'),
(155, 'index', 'localhost', 'Chrome', '2016-12-06 21:58:48'),
(156, 'template/business/css/bootstrap.min.css', 'localhost', 'Chrome', '2016-12-06 21:58:48'),
(157, 'template/business/css/business-casual.css', 'localhost', 'Chrome', '2016-12-06 21:58:48'),
(158, 'template/business/js/jquery.js', 'localhost', 'Chrome', '2016-12-06 21:58:48'),
(159, 'template/business/js/bootstrap.min.js', 'localhost', 'Chrome', '2016-12-06 21:58:48'),
(160, 'index.html', 'localhost', 'Chrome', '2016-12-06 22:21:00'),
(161, 'index.html', 'localhost', 'Chrome', '2016-12-06 22:23:00'),
(162, 'index.html', 'localhost', 'Chrome', '2016-12-06 22:23:28'),
(163, 'index.html', 'localhost', 'Chrome', '2016-12-06 22:23:33'),
(164, 'index.html', 'localhost', 'Chrome', '2016-12-06 22:27:52'),
(165, 'index.html', 'localhost', 'Chrome', '2016-12-06 22:27:59'),
(166, 'index.html', 'localhost', 'Chrome', '2016-12-06 22:28:09'),
(167, 'index.html', 'localhost', 'Chrome', '2016-12-06 22:43:23'),
(168, 'index.html', 'localhost', 'Chrome', '2016-12-06 22:45:29'),
(169, 'index.html', 'localhost', 'Chrome', '2016-12-06 22:52:47'),
(170, 'index.html', 'localhost', 'Chrome', '2016-12-06 22:53:32'),
(171, 'index.html', 'localhost', 'Chrome', '2016-12-07 17:18:34'),
(172, 'index.html', 'localhost', 'Chrome', '2016-12-07 17:18:34'),
(173, 'index.html', 'localhost', 'Chrome', '2016-12-07 17:18:36'),
(174, 'index.html', 'localhost', 'Chrome', '2016-12-07 18:10:28');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `groupid` int(9) NOT NULL,
  `email` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `groupid`, `email`, `image`) VALUES
(1, 'admin', '$2y$10$UxfFCgfhBhEWExKXHRnN8.KaEK8QN985xlAGQYpELEZeRAxA09I8y', 1, 'admin@admin.com', 'profil/default.jpg'),
(8, 'User', '$2y$10$CxZW7Xq2gIpZ7CdwOmwvsugCcKn3K7gMGqZx4npeBBdr6hYkoaeCu', 2, 'user@user.com', 'profil/default.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounting_customer`
--
ALTER TABLE `accounting_customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `accounting_invoices`
--
ALTER TABLE `accounting_invoices`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `accounting_customer`
--
ALTER TABLE `accounting_customer`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=440006;
--
-- AUTO_INCREMENT for table `accounting_invoices`
--
ALTER TABLE `accounting_invoices`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100003;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `navigation`
--
ALTER TABLE `navigation`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `pluginsettings`
--
ALTER TABLE `pluginsettings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `statistics_visits`
--
ALTER TABLE `statistics_visits`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=175;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
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
