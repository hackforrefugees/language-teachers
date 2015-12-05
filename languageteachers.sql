-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 05. Dez 2015 um 15:53
-- Server-Version: 5.6.24
-- PHP-Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `languageteachers`
--
CREATE DATABASE IF NOT EXISTS `languageteachers` DEFAULT CHARACTER SET utf32 COLLATE utf32_general_ci;
USE `languageteachers`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lt_event`
--

CREATE TABLE IF NOT EXISTS `lt_event` (
  `eventId` int(11) NOT NULL,
  `eventTime` datetime NOT NULL,
  `maxTeachers` int(11) NOT NULL,
  `maxStudents` int(11) NOT NULL,
  `street` varchar(150) NOT NULL,
  `streetNumber` varchar(5) NOT NULL,
  `zipCode` varchar(10) NOT NULL,
  `city` varchar(150) NOT NULL,
  `country` varchar(150) NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `eventTitle` varchar(200) NOT NULL,
  `eventLanguage` varchar(45) NOT NULL,
  `creatorUserId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lt_language`
--

CREATE TABLE IF NOT EXISTS `lt_language` (
  `langCode` varchar(5) NOT NULL,
  `languageName` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lt_organisation`
--

CREATE TABLE IF NOT EXISTS `lt_organisation` (
  `organisationId` int(11) NOT NULL,
  `contactPersonName` varchar(150) NOT NULL,
  `contactPersonEmail` varchar(300) NOT NULL,
  `contactPersonPhone` varchar(20) DEFAULT NULL,
  `organisationDescription` text,
  `organisationWebsite` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lt_security_question`
--

CREATE TABLE IF NOT EXISTS `lt_security_question` (
  `securityQuestionId` int(11) NOT NULL,
  `securityQuestion` varchar(200) DEFAULT NULL,
  `langCode` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lt_student`
--

CREATE TABLE IF NOT EXISTS `lt_student` (
  `studentId` int(11) NOT NULL,
  `nativeLanguage` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lt_student_participates_in_event`
--

CREATE TABLE IF NOT EXISTS `lt_student_participates_in_event` (
  `eventId` int(11) NOT NULL,
  `studentId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lt_user`
--

CREATE TABLE IF NOT EXISTS `lt_user` (
  `userId` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `contactName` varchar(200) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(200) NOT NULL,
  `profilePicturePath` varchar(300) DEFAULT NULL,
  `userGroup` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf32;

--
-- Daten für Tabelle `lt_user`
--

INSERT INTO `lt_user` (`userId`, `email`, `contactName`, `phone`, `password`, `profilePicturePath`, `userGroup`) VALUES
(1, 'guseindo@student.gu.se', 'Dominik', NULL, '$2y$10$7Ke/0X1B56I87uy./vIm1OYunHNqvwOvuDLMGPvCyFAenvKiZr54G', NULL, 'admin');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lt_user_security_question`
--

CREATE TABLE IF NOT EXISTS `lt_user_security_question` (
  `userId` int(11) NOT NULL,
  `securityQuestionId` int(11) NOT NULL,
  `securityQuestionAnswer` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lt_volunteer`
--

CREATE TABLE IF NOT EXISTS `lt_volunteer` (
  `volunteerId` int(11) NOT NULL,
  `region` varchar(150) NOT NULL,
  `nativeLanguage` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lt_volunteer_knows_languages`
--

CREATE TABLE IF NOT EXISTS `lt_volunteer_knows_languages` (
  `volunteerId` int(11) NOT NULL,
  `langCode` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lt_volunteer_participates_in_event`
--

CREATE TABLE IF NOT EXISTS `lt_volunteer_participates_in_event` (
  `eventId` int(11) NOT NULL,
  `volunteerId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `lt_event`
--
ALTER TABLE `lt_event`
  ADD PRIMARY KEY (`eventId`), ADD KEY `eventLanguage_idx` (`eventLanguage`), ADD KEY `eventUserId_idx` (`creatorUserId`);

--
-- Indizes für die Tabelle `lt_language`
--
ALTER TABLE `lt_language`
  ADD PRIMARY KEY (`langCode`);

--
-- Indizes für die Tabelle `lt_organisation`
--
ALTER TABLE `lt_organisation`
  ADD PRIMARY KEY (`organisationId`);

--
-- Indizes für die Tabelle `lt_security_question`
--
ALTER TABLE `lt_security_question`
  ADD PRIMARY KEY (`securityQuestionId`,`langCode`), ADD KEY `securityQuestionLangCode_idx` (`langCode`);

--
-- Indizes für die Tabelle `lt_student`
--
ALTER TABLE `lt_student`
  ADD PRIMARY KEY (`studentId`), ADD KEY `studentNativeLanguage_idx` (`nativeLanguage`);

--
-- Indizes für die Tabelle `lt_student_participates_in_event`
--
ALTER TABLE `lt_student_participates_in_event`
  ADD PRIMARY KEY (`eventId`,`studentId`), ADD KEY `eventStudentId_idx` (`studentId`);

--
-- Indizes für die Tabelle `lt_user`
--
ALTER TABLE `lt_user`
  ADD PRIMARY KEY (`userId`);

--
-- Indizes für die Tabelle `lt_user_security_question`
--
ALTER TABLE `lt_user_security_question`
  ADD PRIMARY KEY (`userId`,`securityQuestionId`), ADD KEY `userSecurityQuestionId_idx` (`securityQuestionId`);

--
-- Indizes für die Tabelle `lt_volunteer`
--
ALTER TABLE `lt_volunteer`
  ADD PRIMARY KEY (`volunteerId`), ADD KEY `volunteerNativeLanguage_idx` (`nativeLanguage`);

--
-- Indizes für die Tabelle `lt_volunteer_knows_languages`
--
ALTER TABLE `lt_volunteer_knows_languages`
  ADD PRIMARY KEY (`volunteerId`,`langCode`), ADD KEY `volunteerLangCode_idx` (`langCode`);

--
-- Indizes für die Tabelle `lt_volunteer_participates_in_event`
--
ALTER TABLE `lt_volunteer_participates_in_event`
  ADD PRIMARY KEY (`eventId`,`volunteerId`), ADD KEY `eventVolunteerId_idx` (`volunteerId`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `lt_event`
--
ALTER TABLE `lt_event`
  MODIFY `eventId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `lt_security_question`
--
ALTER TABLE `lt_security_question`
  MODIFY `securityQuestionId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `lt_user`
--
ALTER TABLE `lt_user`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `lt_event`
--
ALTER TABLE `lt_event`
ADD CONSTRAINT `eventLanguage` FOREIGN KEY (`eventLanguage`) REFERENCES `lt_language` (`langCode`) ON UPDATE CASCADE,
ADD CONSTRAINT `eventUserId` FOREIGN KEY (`creatorUserId`) REFERENCES `lt_user` (`userId`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `lt_security_question`
--
ALTER TABLE `lt_security_question`
ADD CONSTRAINT `securityQuestionLangCode` FOREIGN KEY (`langCode`) REFERENCES `lt_language` (`langCode`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `lt_student`
--
ALTER TABLE `lt_student`
ADD CONSTRAINT `studentNativeLanguage` FOREIGN KEY (`nativeLanguage`) REFERENCES `lt_language` (`langCode`) ON UPDATE CASCADE,
ADD CONSTRAINT `studentUserId` FOREIGN KEY (`studentId`) REFERENCES `lt_user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `lt_student_participates_in_event`
--
ALTER TABLE `lt_student_participates_in_event`
ADD CONSTRAINT `eventStudentId` FOREIGN KEY (`studentId`) REFERENCES `lt_student` (`studentId`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `studentEventId` FOREIGN KEY (`eventId`) REFERENCES `lt_event` (`eventId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `lt_user_security_question`
--
ALTER TABLE `lt_user_security_question`
ADD CONSTRAINT `userSecurityQuestionId` FOREIGN KEY (`securityQuestionId`) REFERENCES `lt_security_question` (`securityQuestionId`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `lt_volunteer`
--
ALTER TABLE `lt_volunteer`
ADD CONSTRAINT `volunteerNativeLanguage` FOREIGN KEY (`nativeLanguage`) REFERENCES `lt_language` (`langCode`) ON UPDATE CASCADE,
ADD CONSTRAINT `volunteerUserId` FOREIGN KEY (`volunteerId`) REFERENCES `lt_user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `lt_volunteer_knows_languages`
--
ALTER TABLE `lt_volunteer_knows_languages`
ADD CONSTRAINT `volunteerId` FOREIGN KEY (`volunteerId`) REFERENCES `lt_volunteer` (`volunteerId`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `volunteerLangCode` FOREIGN KEY (`langCode`) REFERENCES `lt_language` (`langCode`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `lt_volunteer_participates_in_event`
--
ALTER TABLE `lt_volunteer_participates_in_event`
ADD CONSTRAINT `eventVolunteerId` FOREIGN KEY (`volunteerId`) REFERENCES `lt_volunteer` (`volunteerId`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `volunteerEventId` FOREIGN KEY (`eventId`) REFERENCES `lt_event` (`eventId`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
