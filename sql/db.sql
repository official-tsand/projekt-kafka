-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 18. Aug 2022 um 16:01
-- Server-Version: 10.4.24-MariaDB
-- PHP-Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `db_kafka`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `comments`
--

CREATE TABLE `comments` (
  `column_id` int(11) NOT NULL,
  `column_key` varchar(255) NOT NULL,
  `column_linking_key` varchar(255) NOT NULL,
  `column_check_replies` varchar(255) NOT NULL,
  `column_text` varchar(255) NOT NULL,
  `column_user` varchar(100) NOT NULL,
  `column_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `quotes`
--

CREATE TABLE `quotes` (
  `column_id` int(11) NOT NULL,
  `column_key` varchar(255) NOT NULL,
  `column_text` varchar(255) NOT NULL,
  `column_author` varchar(100) NOT NULL,
  `column_user` varchar(100) NOT NULL,
  `column_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `quotes`
--

INSERT INTO `quotes` (`column_id`, `column_key`, `column_text`, `column_author`, `column_user`, `column_date`) VALUES
(1, 'q62f11b6c9faee7.55458883', 'Willkommen bei kafka!', 'Admin', 'admin', '2022-08-18 16:00:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `replies`
--

CREATE TABLE `replies` (
  `column_id` int(11) NOT NULL,
  `column_key` varchar(255) NOT NULL,
  `column_linking_key` varchar(255) NOT NULL,
  `column_text` varchar(255) NOT NULL,
  `column_user` varchar(100) NOT NULL,
  `column_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `column_id` int(11) NOT NULL,
  `column_firstname` varchar(255) NOT NULL,
  `column_lastname` varchar(255) NOT NULL,
  `column_username` varchar(255) NOT NULL,
  `column_email` varchar(100) NOT NULL,
  `column_password` char(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`column_id`, `column_firstname`, `column_lastname`, `column_username`, `column_email`, `column_password`) VALUES
(1, 'Admin', 'Benutzer', 'admin', 'admin@gmail.com', '$2y$10$e/PpFpAXJ94ht69yXcGpgeUhmzPGycg3S3xaJfnQW7lWxIPW/T.SK');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`column_id`);

--
-- Indizes für die Tabelle `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`column_id`);

--
-- Indizes für die Tabelle `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`column_id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`column_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `comments`
--
ALTER TABLE `comments`
  MODIFY `column_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=311;

--
-- AUTO_INCREMENT für Tabelle `quotes`
--
ALTER TABLE `quotes`
  MODIFY `column_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT für Tabelle `replies`
--
ALTER TABLE `replies`
  MODIFY `column_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `column_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
