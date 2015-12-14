-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 14. Dez 2015 um 16:31
-- Server Version: 5.6.21
-- PHP-Version: 5.6.3

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

DELIMITER $$
--
-- Prozeduren
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `spatialEventSearch`(IN `latitude` FLOAT, IN `longitude` FLOAT, IN `maxDistance` INT(11))
    NO SQL
BEGIN
    SELECT *, 3956 * 2 * ASIN(SQRT( POWER(SIN((lte.latitude - latitude) * pi()/180 / 2), 2) + 
						   COS(lte.latitude * pi()/180) * COS(latitude * pi()/180) * 
						   POWER(SIN((lte.longitude - longitude) * pi()/180 / 2), 2) )) as distance FROM lt_event as lte WHERE maxDistance >= (SELECT 3956 * 2 * ASIN(SQRT( POWER(SIN((lte.latitude - latitude) * pi()/180 / 2), 2) + 
						   COS(lte.latitude * pi()/180) * COS(latitude * pi()/180) * 
						   POWER(SIN((lte.longitude - longitude) * pi()/180 / 2), 2) )) as subdistance);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spatialVolunteerSearch`(IN `latitude` FLOAT, IN `longitude` FLOAT, IN `maxDistance` INT(11))
    NO SQL
BEGIN
    SELECT *, 3956 * 2 * ASIN(SQRT( POWER(SIN((ltv.latitude - latitude) * pi()/180 / 2), 2) + 
						   COS(ltv.latitude * pi()/180) * COS(latitude * pi()/180) * 
						   POWER(SIN((lte.longitude - longitude) * pi()/180 / 2), 2) )) as distance FROM lt_volunteer as ltv WHERE maxDistance >= (SELECT 3956 * 2 * ASIN(SQRT( POWER(SIN((ltv.latitude - latitude) * pi()/180 / 2), 2) + 
						   COS(ltv.latitude * pi()/180) * COS(latitude * pi()/180) * 
						   POWER(SIN((ltv.longitude - longitude) * pi()/180 / 2), 2) )) as subdistance);
END$$

DELIMITER ;

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf32;

--
-- Daten für Tabelle `lt_event`
--

INSERT INTO `lt_event` (`eventId`, `eventTime`, `maxTeachers`, `maxStudents`, `street`, `streetNumber`, `zipCode`, `city`, `country`, `latitude`, `longitude`, `eventTitle`, `eventLanguage`, `creatorUserId`) VALUES
(1, '2015-12-06 00:00:00', 2, 30, 'Saturnusgatan', '8', '41520', 'Göteborg', 'Sweden', 57.7548, 12.0762, 'Test', 'en', 1),
(2, '2015-12-17 08:30:00', 2, 30, 'Brinellvägen', '8', '114 ', 'Stockholm', 'Sweden', 59.3498, 18.0707, 'Test2', 'de', 1),
(3, '2015-12-06 12:15:00', 2, 30, 'Saturnusgatan', '8', '41520', 'Göteborg', 'Sweden', 57.7548, 12.0762, 'TestArabic', 'ar', 1),
(4, '2015-12-06 18:00:00', 0, 15, 'Saturnusgatan', '8', '41520', 'Göteborg', 'Sweden', 57.7548, 12.0762, 'Test Create action', 'ar', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lt_language`
--

CREATE TABLE IF NOT EXISTS `lt_language` (
  `langCode` varchar(5) NOT NULL,
  `languageName` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Daten für Tabelle `lt_language`
--

INSERT INTO `lt_language` (`langCode`, `languageName`) VALUES
('ar', 'العربية'),
('de', 'Deutsch'),
('en', 'English'),
('se', 'Svenska');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf32;

--
-- Daten für Tabelle `lt_security_question`
--

INSERT INTO `lt_security_question` (`securityQuestionId`, `securityQuestion`, `langCode`) VALUES
(1, 'Who was your childhood hero?', 'en'),
(2, 'What was the lastname of your teacher in third grade in elementary school?', 'en'),
(3, 'What is your mother''s maiden name?', 'en'),
(4, 'What was your dreamjob in your Childhood?', 'en');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lt_student`
--

CREATE TABLE IF NOT EXISTS `lt_student` (
  `studentId` int(11) NOT NULL,
  `nativeLanguage` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Daten für Tabelle `lt_student`
--

INSERT INTO `lt_student` (`studentId`, `nativeLanguage`) VALUES
(2, 'de');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lt_student_participates_in_event`
--

CREATE TABLE IF NOT EXISTS `lt_student_participates_in_event` (
  `eventId` int(11) NOT NULL,
  `studentId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Daten für Tabelle `lt_student_participates_in_event`
--

INSERT INTO `lt_student_participates_in_event` (`eventId`, `studentId`) VALUES
(1, 2);

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
  `userGroup` varchar(100) NOT NULL,
  `emailVerfied` tinyint(1) DEFAULT NULL,
  `registrationDate` date DEFAULT NULL,
  `emailChangedDate` date DEFAULT NULL,
  `registrationToken` varchar(300) DEFAULT NULL,
  `resetExpirationDate` date DEFAULT NULL,
  `restRequestHash` varchar(300) DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `authToken` varchar(300) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf32;

--
-- Daten für Tabelle `lt_user`
--

INSERT INTO `lt_user` (`userId`, `email`, `contactName`, `phone`, `password`, `profilePicturePath`, `userGroup`, `emailVerfied`, `registrationDate`, `emailChangedDate`, `registrationToken`, `resetExpirationDate`, `restRequestHash`, `latitude`, `longitude`, `authToken`) VALUES
(1, 'guseindo@student.gu.se', 'Dominik', NULL, '$2y$10$7Ke/0X1B56I87uy./vIm1OYunHNqvwOvuDLMGPvCyFAenvKiZr54G', NULL, 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'dominik_einkemmer@gmx.at', 'Dominik', NULL, '$2y$10$JUibddPO4KnjJmAsOOsSsuZJ.oDB84iz18ECTrnnoDm96VtNDN.ES', NULL, 'student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'domein@student.chalmers.se', 'Dominik', NULL, '$2y$10$JUibddPO4KnjJmAsOOsSsuZJ.oDB84iz18ECTrnnoDm96VtNDN.ES', NULL, 'volunteer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'drogba32@msn.com', 'Dominik', NULL, '$2y$10$eCRBbYk28MwyeYagTqyzke.ryJJLUslZbg1mXToAAhBnldxGMm4FC', NULL, 'volunteer', NULL, '2015-12-06', '2015-12-06', '9990d6fbd2ba7d8d54d74eb3db025b0c', NULL, NULL, 41.2926, -73.6794, NULL),
(8, 'asdf@asdf.com', 'Dominik', NULL, '$2y$10$vlM7EO00SujtKp/AwU1FR.2JDfL8vzZYqKkb1VqnT6B7HEHvm8Eaq', 'img/profilePictures/ceadd08d8b8a2c9654f56e46e6a07690.jpg', 'volunteer', NULL, '2015-12-06', '2015-12-06', '1d7d40e3eff670ee39c0326da0cc7b70', NULL, NULL, 57.7089, 11.9746, '3f36bfbfd537be91892ee66668744d83'),
(9, 'lobheswagh@gmail.com', 'Test', NULL, '$2y$10$3JF8IcrZCF79O0u6VWbvcenfqze5WP4a30DKbDthYE7bbM9hMsPEq', 'img/profilePictures/1b40f693391ee8ea75e6eacb9f72a2e1.jpg', 'volunteer', NULL, '2015-12-06', '2015-12-06', '6176582f9801d67b7147562f393dd877', NULL, NULL, 57.7089, 11.9746, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lt_user_security_question`
--

CREATE TABLE IF NOT EXISTS `lt_user_security_question` (
  `userId` int(11) NOT NULL,
  `securityQuestionId` int(11) NOT NULL,
  `securityQuestionAnswer` varchar(200) DEFAULT NULL,
  `langCode` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Daten für Tabelle `lt_user_security_question`
--

INSERT INTO `lt_user_security_question` (`userId`, `securityQuestionId`, `securityQuestionAnswer`, `langCode`) VALUES
(8, 1, 'test', 'en'),
(9, 1, 'test', 'en');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lt_volunteer`
--

CREATE TABLE IF NOT EXISTS `lt_volunteer` (
  `volunteerId` int(11) NOT NULL,
  `nativeLanguage` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Daten für Tabelle `lt_volunteer`
--

INSERT INTO `lt_volunteer` (`volunteerId`, `nativeLanguage`) VALUES
(3, 'de'),
(4, 'de'),
(8, 'de'),
(9, 'de');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lt_volunteer_knows_languages`
--

CREATE TABLE IF NOT EXISTS `lt_volunteer_knows_languages` (
  `volunteerId` int(11) NOT NULL,
  `langCode` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Daten für Tabelle `lt_volunteer_knows_languages`
--

INSERT INTO `lt_volunteer_knows_languages` (`volunteerId`, `langCode`) VALUES
(4, 'de'),
(8, 'de'),
(4, 'en'),
(8, 'en'),
(9, 'en'),
(9, 'se');

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
 ADD PRIMARY KEY (`userId`,`securityQuestionId`);

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
MODIFY `eventId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `lt_security_question`
--
ALTER TABLE `lt_security_question`
MODIFY `securityQuestionId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `lt_user`
--
ALTER TABLE `lt_user`
MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
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
-- Constraints der Tabelle `lt_organisation`
--
ALTER TABLE `lt_organisation`
ADD CONSTRAINT `organisationUserId` FOREIGN KEY (`organisationId`) REFERENCES `lt_user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

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
ADD CONSTRAINT `securityQuestionUserId` FOREIGN KEY (`userId`) REFERENCES `lt_user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

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
