-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 17. Okt 2016 um 17:51
-- Server-Version: 10.1.16-MariaDB
-- PHP-Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `oxymora`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `addons`
--

CREATE TABLE `addons` (
  `name` varchar(128) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `installed` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `attempts`
--

CREATE TABLE `attempts` (
  `memberid` int(11) UNSIGNED NOT NULL,
  `ip` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `content`
--

CREATE TABLE `content` (
  `pageurl` varchar(128) NOT NULL,
  `area` varchar(128) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `content`
--

INSERT INTO `content` (`pageurl`, `area`, `content`) VALUES
('about.html', 'body', '{plugin:Text:3}{plugin:Text:57f188883eb8b1.39986556}'),
('index.html', 'body', '{plugin:Slider:1}{plugin:Text:5803c04fe22fb5.28934523}{plugin:Text:5803c04fe5bc78.89612624}{plugin:Text:2}'),
('King.html', 'body', '{plugin:Slider:57f188aef36f94.11803575}{plugin:Text:57f189bc3ffa57.15995102}{plugin:Text:57f18837099d91.62990037}');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `navigation`
--

CREATE TABLE `navigation` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(64) NOT NULL,
  `url` varchar(256) NOT NULL,
  `display` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `navigation`
--

INSERT INTO `navigation` (`id`, `title`, `url`, `display`) VALUES
(1, 'Home', '/index.html', 0),
(3, 'About', '/about.html', 2),
(4, 'Admin', '/admin', 3),
(7, 'King', '/King.html', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pages`
--

CREATE TABLE `pages` (
  `url` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `pages`
--

INSERT INTO `pages` (`url`) VALUES
('about.html'),
('index.html'),
('King.html');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pluginsettings`
--

CREATE TABLE `pluginsettings` (
  `id` int(11) NOT NULL,
  `pluginid` varchar(32) NOT NULL,
  `settingkey` varchar(64) NOT NULL,
  `settingvalue` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `pluginsettings`
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
(54, '2', 'content', '<p>Use as many boxes as you like, and put anything you want in them! They are great for just about anything, the sky''s the limit!</p>\r\n<p>Use as many boxes as you like, and put anything you want in them! They are great for just about anything, the sky''s the limit!</p>');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `session`
--

CREATE TABLE `session` (
  `memberid` int(11) UNSIGNED NOT NULL,
  `session` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `updated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `session`
--

INSERT INTO `session` (`memberid`, `session`, `token`, `updated`) VALUES
(1, '4ueWOt26qizPA9ae7gHY8LPc7eg7ZSExHzPQZ9XQzm9EzTXC7J9uNp3eZFIDEnuz', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, '5JwdfGTaeiLiLqu0kUAANe74URf8Xc4auECy7PTLjueeZf3g3A5Tt3eTWmVLrOdK', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, '64tYyBLkTlLpW9NTvQ1OkOkiDW8OqJlIcmpKTAXtC80mxcyZhovnqJcXZ5Kx29kv', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'a2mjBYhrVlsoW6CmMYIvNjvhl644AsOYYlvlEiyRarVfLv2WProeYdafXpcTfctP', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'bO3r3QnKmeftYpmMIjJPhxYwn9Xkf4x8LkhnsIo72a29UFwsnw2GCsPbmY5dO0Wv', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'c7iOO4vtnpj6CwchWAxo8eOZsh5MX1tkCUhGVViszmNn4Nm5G36eltZvHF4Ouq28', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'cXsAOuFl5qjvGEGSFXDgsCHYSPdLzrC60xnFmCleTbWt8Skk0MGwryRFK8YoBh1F', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'dPT228OvkDVpcn2TmHTNBIuHPCRQrv7V6q36czqWRO97geg3EuJgGIyQaUefgvWg', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'EfUnnf3yM1lnmxwo29jDcytpWlHqfhDRuexrvScRndJfro1nBcSgjjzCuesW0Edo', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'fhxFa4gMQJYPORxYOfM737IYKgYYoGsFEVMXGhggyCggmi3zr13ogJuPr0Qiib7F', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'gEroW5wfuUpmiouJ8SxdyzURrgJn7G4w2a2mjCE7dq5IYGF0X6VakQCMIgBazTun', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'gTqJtMKRqQudNdEOKyOYIIS91WbjE4IxyVttNUewyuAarWMekjz5AZBaeYs7ijOl', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'iAjsOZ7EiViZivBQ4MSDSJ0ksxFThJLIAdJ27qibCBn7F8jOiGa5uYm4vp4R7wor', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'IszXmmzIr70kwFG1JE2FGjd4aYDX3Yn4vlUKX4SMxtBRUBKYB2J1HC6on5DAv1nn', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'joBS0lpAwrPkzjzSH2DAnm07FjxFwEznxXgKfpdNFjBQO6hPMr6sPwQAWFtBb94w', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'K0POpnOKTyhgA3evyek1n5UoKiNc8r6PHWh2AWBmAWCbuRR2RGVdtLe7lHJfAl3e', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'ngkjaxkoMBBDkJruYPMSN57om3UUlbrxlIa0YqVvE6TjTx4Vb0XihfTJsmOLQT7U', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'nLtBz2iBJEk4ldfPiURSr2LIexlVxdMGxw11UUmluIdjOA1xYcuZiQOx6DWoiK1h', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'pjwlX7ZflpawctmhD7apgNPs90eRsc4aoAieiHpCiQ6nG3ObVEuWncFWVpOd3xTV', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'Q4byxsz6lhigvIUrDrnZXENbWL44ghyp1iTohc8aRBcawWAR5qIzYZGIbNBK09CB', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'Rz1246bOtMl8Q6SXoSal5WlTroueSUaDa6Yj8gg6LLj99fyknkmuPL1iR7ninlaz', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'S3OQfBYlmGPesgDxHi76kJ8gn2pqJpPnv4WH65930J0olW2auPmWCtUj7uOGEkWN', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'SdbZbelx1hXwelTrcXPaW7gCqYu9wPMDYwhCFoJLy4lb2tVuBUTBBeMBfwyya1Lu', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'SVgajB42gZiRxN2YfVVdhgduSdYqX1oxmHjMv7AzMWvQ4IgRi4yKut663rMX3jHe', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'UIAEsIM1XQfCtSykKYOab0pO3Up9hKxRdKoYPSTpNGpfhL8VY6cOwoKbqFUx0jwl', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'Vb6laDHt2jOOn3M7OdaiOl7L6QQYkQGbPdIqXm5fKBLfx42ixnKuyNfADX2q0cO2', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'wk4gCKdrDtN2QnhLrF1FV6wCPohUFjSgKxlKe8GKWGLhdMP0EmcYMH97YKcgL5AC', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'wWxoFSeeDT68Sed8Dl4vqEQmJjO9L8Ws7F7TRnw7W1H4vAyez4L5xMHEDFaQzu58', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'wwYgCrENWqg46HSs56AnXmFIyat9NjKHz2FtfYXITr3GEDOr8r0i83vxwDICKgrC', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'XQ74cYRV7GSR9JGjM1q9yPnRpyTERa3elsH5esZzuj9492Guw0T1JgyYCXivxhtN', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'Xv3xWtUeZQVWdj76ahW3Fo3AmCxtm956um4wKbpdteYRgDim0IYL8YWDGyr0UQgr', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'y31rIwv8dcXoltwlcUGOb64d9tPDR1xTFXTrOJI799TFtgbmFePZnWEBv7809mKg', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'YXTF0jkKplvqyPiprgqBTR6RsfU8E3xjPYnsRE37M1dih3ElnMP8vJIobXv7eOnw', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36'),
(1, 'zs5gzSNqNnRusDYXbolyoxXSk3slW9X6zHzqdjbLIkDLNPgEGZZrDWl47JI8WSBY', 'YlaZgRRCc2o7eyhHgfRMiGoG6sIikF3K3QjnGsClK3UWYpp4zXna1eGj1jeR0oV4', '2016-10-17 17:14:36');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `static`
--

CREATE TABLE `static` (
  `placeholder` varchar(64) NOT NULL,
  `value` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `static`
--

INSERT INTO `static` (`placeholder`, `value`) VALUES
('copyright', 'Copyright 2016 Khadim Fall'),
('subtitle', 'My first small Page :)'),
('title', 'Hello World');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
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
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `role`, `email`, `firstname`, `lastname`) VALUES
(1, 'admin', '$2y$10$UxfFCgfhBhEWExKXHRnN8.KaEK8QN985xlAGQYpELEZeRAxA09I8y', 'admin', 'admin@admin.com', NULL, NULL);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `addons`
--
ALTER TABLE `addons`
  ADD PRIMARY KEY (`name`);

--
-- Indizes für die Tabelle `attempts`
--
ALTER TABLE `attempts`
  ADD KEY `memberid` (`memberid`);

--
-- Indizes für die Tabelle `content`
--
ALTER TABLE `content`
  ADD UNIQUE KEY `pageurl` (`pageurl`,`area`);

--
-- Indizes für die Tabelle `navigation`
--
ALTER TABLE `navigation`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`url`);

--
-- Indizes für die Tabelle `pluginsettings`
--
ALTER TABLE `pluginsettings`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `session`
--
ALTER TABLE `session`
  ADD UNIQUE KEY `session` (`session`),
  ADD KEY `memberid` (`memberid`);

--
-- Indizes für die Tabelle `static`
--
ALTER TABLE `static`
  ADD UNIQUE KEY `key` (`placeholder`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `navigation`
--
ALTER TABLE `navigation`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT für Tabelle `pluginsettings`
--
ALTER TABLE `pluginsettings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `attempts`
--
ALTER TABLE `attempts`
  ADD CONSTRAINT `attempts_ibfk_1` FOREIGN KEY (`memberid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `session_ibfk_1` FOREIGN KEY (`memberid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
