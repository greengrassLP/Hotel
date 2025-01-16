-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 15. Jan 2025 um 23:51
-- Server-Version: 10.4.28-MariaDB
-- PHP-Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `Hotel`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news`
--

CREATE TABLE `news` (
  `news_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `text` text NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `news`
--

INSERT INTO `news` (`news_id`, `title`, `picture`, `text`, `date`) VALUES
(1, 'test', 'picture', 'lorem ipsum', '2025-01-15'),
(2, 'test2', 'picture', 'lorem ipsum lorem ipsum', '2025-01-15');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Reservations`
--

CREATE TABLE `Reservations` (
  `id` int(255) NOT NULL,
  `checkin_date` date NOT NULL,
  `checkout_date` date NOT NULL,
  `breakfast` tinyint(1) NOT NULL,
  `parking` tinyint(1) NOT NULL,
  `pets` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` enum('bestätigt','storniert') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(255) NOT NULL,
  `price` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Rooms`
--

CREATE TABLE `Rooms` (
  `id` int(255) NOT NULL,
  `room_number` int(255) NOT NULL,
  `room_type` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `price_per_night` int(255) NOT NULL,
  `status` enum('verfügbar','belegt') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `salutation` enum('Herr','Frau','Divers') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `firstname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lastname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(260) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `role` enum('user','admin') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `salutation`, `firstname`, `lastname`, `email`, `username`, `password`, `role`) VALUES
(1, 'Herr', 'Moritz', 'Heberle', 'mo@gmail.com', 'admin', '123456', 'admin'),
(3, 'Herr', 'me', 'ma', 'me@ma.com', 'mea', '$2y$10$OgfoCJOM1L83K36ntmY4fuSGpBYfKQujNZOfa2FmFrZFT331B7Hsu', 'user'),
(5, 'Herr', 'test', 'te', 'te@ma.com', 'testa', '$2y$10$G8rhSQicod7hb2mA/FgOw.y8kaH549lem6.1FwA55Ww0c32JrWJ.2', 'user'),
(13, 'Herr', 'test2', 'te2', 'te2@ma.com', 'testa2', '$2y$10$r8YB0S36YmvzkhtricitkO85S699XprqgL6RKdl6AAXjU.E/syX0u', 'user'),
(14, 'Herr', 't3', 'te3', 't3@te.com', 't3', '$2y$10$2NVQ28pYT4JzLNALW0NTvuscCs1jUlZUTVWZU6ByBONfsuYyrIWi6', 'user'),
(15, 'Herr', 'log', 'in', 'log@in.com', 'lgoin', '$2y$10$OT6XMDUFqgOse8IWUhGBtOv5EvPhjbtMZbHOYioTd7ILlkBmGlT6y', 'user'),
(16, 'Herr', 'mi', 'mi', 'mi@gm.com', 'mimi', '$2y$10$YnzISTNhwdk2/6Y2veziZuO6omSGX.A39dvxGPBcbLfvB6zR1xIkq', 'user'),
(17, 'Herr', 'ms', 'mi', 'ms@mmi.com', 'mims', '$2y$10$vW5qt8upvuS2RUn46C4oCOAMFTD4I3FSx4q8eljaeTcDD/BraVIP.', 'user'),
(18, 'Herr', 'marie', 'muster', 'marie@must.com', 'mariemus', '$2y$10$0rcm09y6fr/ctVmnXnyG2eh9bFSirW7vXR79Nr7y2WGapWDstWQOi', 'user'),
(19, 'Frau', 'a', 'b', 'c@d.com', 'ab', '$2y$10$5MC6VYA1c3ZjfecdG0kTT.txdraxQidBZDrV8Es4fp5eTm3grIaWK', 'user'),
(20, 'Herr', 'max', 'must', 'max@must.com', 'musti', '$2y$10$mp079AK5yDCSFQ1m7miMwuZuLCVRx2dgM3essF10HBFqmbE.N7R1i', 'admin');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`);

--
-- Indizes für die Tabelle `Reservations`
--
ALTER TABLE `Reservations`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `Rooms`
--
ALTER TABLE `Rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `news`
--
ALTER TABLE `news`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
