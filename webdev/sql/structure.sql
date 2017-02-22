-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 01. Okt 2016 um 06:10
-- Server-Version: 5.5.50-MariaDB
-- PHP-Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `schulverwaltung`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `admin__content`
--

CREATE TABLE `admin__content` (
  `id` int(11) NOT NULL,
  `chatDisplay` tinyint(1) DEFAULT NULL COMMENT 'Should the chat feature be disabled?',
  `unitSystem` enum('i','m') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'm',
  `profilePic` tinyint(1) DEFAULT NULL COMMENT 'Should the user be displayed a profile pic?'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chat__badWords`
--

CREATE TABLE `chat__badWords` (
  `id` int(11) NOT NULL,
  `badWord` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Word that is replaced'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chat__groups`
--

CREATE TABLE `chat__groups` (
  `id` int(11) NOT NULL,
  `groupName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Name of a group chat',
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date of creation',
  `private` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is this a private chat'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chat__messages`
--

CREATE TABLE `chat__messages` (
  `id` int(11) NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Message that was send',
  `sender` int(11) NOT NULL COMMENT 'Id of the sender',
  `receiver` int(11) DEFAULT NULL COMMENT 'Receiving group of the message',
  `sendDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Date of sending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chat__online`
--

CREATE TABLE `chat__online` (
  `id` int(11) NOT NULL,
  `lastAction` datetime NOT NULL COMMENT 'Timestamp of the last action the user did',
  `userId` int(11) NOT NULL COMMENT 'Id of the user that has signed in.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chat__reports`
--

CREATE TABLE `chat__reports` (
  `id` int(11) NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'The report of the user',
  `reporter` int(11) NOT NULL COMMENT 'Who reported a user',
  `victim` int(11) NOT NULL COMMENT 'The user that was reported'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `course__overview`
--

CREATE TABLE `course__overview` (
  `id` int(11) NOT NULL COMMENT 'Id of the course',
  `teacherID` mediumint(11) UNSIGNED NOT NULL COMMENT 'Id of the teacher',
  `subject` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of the subject',
  `type` enum('G','L') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Type of the class G (o-level) or L for (a-level)',
  `abbr` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The short form of the subjectname',
  `grade` tinyint(2) UNSIGNED NOT NULL DEFAULT '5' COMMENT 'The grade of the course that it takes place in',
  `active` tinyint(1) NOT NULL COMMENT 'True if the course is active and false otherwise'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Important Information about the class';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `course__student`
--

CREATE TABLE `course__student` (
  `id` int(11) NOT NULL,
  `classID` mediumint(11) NOT NULL COMMENT 'Id of the class',
  `studentID` int(11) NOT NULL COMMENT 'Id of the student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=REDUNDANT;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `debug__debugger`
--

CREATE TABLE `debug__debugger` (
  `id` int(11) NOT NULL,
  `query` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'The query that was executed',
  `issuingPage` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'What script executed it',
  `paramList` text COLLATE utf8_unicode_ci COMMENT 'Parametrs set for the page call',
  `error` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'What was the error'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `debug__logger`
--

CREATE TABLE `debug__logger` (
  `id` int(11) NOT NULL,
  `event` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'What was done',
  `topicId` int(11) NOT NULL COMMENT 'Id of the topic',
  `issuer` int(11) NOT NULL COMMENT 'Who did it?',
  `timestamp` datetime NOT NULL COMMENT 'Timestamp of the creation'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `event__participants`
--

CREATE TABLE `event__participants` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` enum('p','g','c','o') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Type of participants',
  `value` int(11) DEFAULT NULL COMMENT 'Id of the type',
  `otherMember` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Add a user that is not in the database'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `event__ticker`
--

CREATE TABLE `event__ticker` (
  `id` int(11) NOT NULL,
  `sendDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date the notification was send',
  `grade` mediumint(11) UNSIGNED DEFAULT NULL COMMENT 'The grade the message is adressed to',
  `classId` mediumint(11) UNSIGNED DEFAULT NULL COMMENT 'Id of the class',
  `notification` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'The message of the ticker'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `event__upcoming`
--

CREATE TABLE `event__upcoming` (
  `id` int(11) NOT NULL COMMENT 'The Id of the event',
  `creatorID` int(11) NOT NULL COMMENT 'Who created this event',
  `startTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time it starts',
  `topic` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The name of the event',
  `endTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The time it ends',
  `description` text COLLATE utf8_unicode_ci COMMENT 'What is this event about',
  `participants` int(11) DEFAULT NULL COMMENT 'Refering to the external participants table',
  `private` tinyint(1) DEFAULT '0' COMMENT 'Is the event just visible to the creator'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `forum__forums`
--

CREATE TABLE `forum__forums` (
  `id` int(11) NOT NULL COMMENT 'Id of the forum',
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of the forum',
  `description` text COLLATE utf8_unicode_ci COMMENT 'Description of the purpose',
  `creatorId` int(11) NOT NULL COMMENT 'Creator Id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `forum__post`
--

CREATE TABLE `forum__post` (
  `id` int(11) NOT NULL,
  `parent` int(11) NOT NULL COMMENT 'Id of the topic this post is in',
  `post` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Message of the post',
  `postTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'The time this message was posted',
  `visiblity` enum('show','delete','hide') COLLATE utf8_unicode_ci DEFAULT 'show',
  `poster` int(11) NOT NULL COMMENT 'Id of the person, that posted that'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `forum__topic`
--

CREATE TABLE `forum__topic` (
  `id` int(11) NOT NULL COMMENT 'Id of the topic',
  `parent` int(11) NOT NULL COMMENT 'Id of the forum that contains the topic',
  `name` varchar(254) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of the topic',
  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Description of the topic',
  `creatorId` int(11) NOT NULL DEFAULT '0' COMMENT 'Id of the user that has created the forum.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `help__main`
--

CREATE TABLE `help__main` (
  `id` int(11) NOT NULL,
  `question` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Question that is dealt with in the answer',
  `answer` text COLLATE utf8_unicode_ci COMMENT 'Content of the help',
  `topic` int(11) NOT NULL COMMENT 'Id of the topic the question belongs to'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `help__topics`
--

CREATE TABLE `help__topics` (
  `id` int(11) NOT NULL,
  `topic` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of the topic'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homework__material`
--

CREATE TABLE `homework__material` (
  `id` int(11) NOT NULL,
  `hwID` int(11) NOT NULL COMMENT 'The id of the homework it is refering to',
  `book` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Tasks in the book',
  `sheets` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Tasks on the sheet',
  `others` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'other media',
  `link` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Webpage'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `homework__overview`
--

CREATE TABLE `homework__overview` (
  `id` int(11) NOT NULL,
  `topic` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Topic of the homework',
  `dueDate` datetime DEFAULT NULL COMMENT 'Timestamp of the date its due',
  `description` mediumtext COLLATE utf8_unicode_ci COMMENT 'Describe what the student has to do'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lesson__attended`
--

CREATE TABLE `lesson__attended` (
  `id` int(11) NOT NULL,
  `lessonId` int(11) NOT NULL COMMENT 'The lesson you are refering to',
  `studentId` int(11) NOT NULL COMMENT 'The student that is attending',
  `attended` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Has the student attended this lesson',
  `homeworkDone` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Has the student done the homework for this lesson'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lesson__overview`
--

CREATE TABLE `lesson__overview` (
  `id` int(11) NOT NULL,
  `classId` int(11) NOT NULL COMMENT 'Id of the class',
  `topic` text COLLATE utf8_unicode_ci COMMENT 'Topic of the lesson',
  `homework` tinyint(1) DEFAULT '0' COMMENT 'Have the students homework to do',
  `started` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The time the lesson actually started'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `permission__names`
--

CREATE TABLE `permission__names` (
  `id` int(11) NOT NULL,
  `permName` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Name of the permission',
  `permNode` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Node of the permission'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `permission__users`
--

CREATE TABLE `permission__users` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL COMMENT 'name of the person that has the permission',
  `permId` int(11) NOT NULL COMMENT 'Id of the permission'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `task__priority`
--

CREATE TABLE `task__priority` (
  `id` int(11) NOT NULL,
  `prioVal` mediumint(11) UNSIGNED NOT NULL COMMENT 'value of priority',
  `content` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'name of the priority'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `task__toDo`
--

CREATE TABLE `task__toDo` (
  `id` int(11) NOT NULL,
  `classID` mediumint(11) UNSIGNED NOT NULL COMMENT 'The class where the homework is given',
  `studentID` mediumint(11) UNSIGNED NOT NULL COMMENT 'The student that has to do the homework',
  `done` tinyint(1) NOT NULL COMMENT 'Has the student done the homework',
  `deadline` date NOT NULL COMMENT 'The date the work has to be done',
  `content` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Content of the todo',
  `typeID` tinyint(11) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'What type of homework is it?',
  `prio` int(1) NOT NULL DEFAULT '0' COMMENT 'How important is this?'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `task__type`
--

CREATE TABLE `task__type` (
  `id` int(11) NOT NULL,
  `content` char(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'name of the type'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `test__other`
--

CREATE TABLE `test__other` (
  `id` int(11) NOT NULL,
  `classId` int(11) NOT NULL COMMENT 'Id of the class the test belongs to',
  `studentId` int(11) NOT NULL COMMENT 'Id of the student who took the test',
  `markValue` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Mark that the student scored'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `test__task`
--

CREATE TABLE `test__task` (
  `id` int(11) NOT NULL,
  `taskname` varchar(200) NOT NULL COMMENT 'Name of the task',
  `description` text COMMENT 'Description of what is the task',
  `maxScore` int(11) NOT NULL COMMENT 'Max score someone can get'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `test__test`
--

CREATE TABLE `test__test` (
  `id` int(11) NOT NULL,
  `topic` varchar(200) NOT NULL COMMENT 'Topic of the test',
  `description` text COMMENT 'Closer description of the tests topic',
  `dateWritten` datetime DEFAULT NULL COMMENT 'Date when the test was written',
  `classId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table for all written tests';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `test__try`
--

CREATE TABLE `test__try` (
  `id` int(11) NOT NULL,
  `testId` int(11) NOT NULL COMMENT 'Id of the test',
  `taskNumber` int(11) NOT NULL COMMENT 'Number of th task',
  `score` int(11) NOT NULL COMMENT 'Score that has been achieved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Try to do the test';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `timetable__overview`
--

CREATE TABLE `timetable__overview` (
  `id` int(11) NOT NULL,
  `classID` mediumint(11) UNSIGNED DEFAULT NULL COMMENT 'Id of the class',
  `day` int(1) NOT NULL COMMENT 'The day',
  `lesson` int(2) NOT NULL COMMENT 'The lesson',
  `room` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The room where it takes place'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `timetable__standardTimes`
--

CREATE TABLE `timetable__standardTimes` (
  `id` int(11) NOT NULL,
  `start` time NOT NULL COMMENT 'Start of the lesson',
  `end` time NOT NULL COMMENT 'Start of the lesson'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user__friends`
--

CREATE TABLE `user__friends` (
  `id` int(11) NOT NULL,
  `fOne` int(11) NOT NULL COMMENT 'Friend One',
  `fTwo` int(11) NOT NULL COMMENT 'Friend Two',
  `accepted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Is the friendship accepted?'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user__interface`
--

CREATE TABLE `user__interface` (
  `id` int(11) NOT NULL,
  `nickName` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Should a user be referenced by a nickname',
  `markNames` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Should the mark displayed as word?',
  `darkTheme` tinyint(1) DEFAULT '0' COMMENT 'Should the dark theme apply'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user__messages`
--

CREATE TABLE `user__messages` (
  `id` int(11) NOT NULL,
  `sender` mediumint(11) UNSIGNED NOT NULL COMMENT 'The person who sent the message',
  `reciver` mediumint(11) UNSIGNED NOT NULL COMMENT 'The person the message should get',
  `subject` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No subject' COMMENT 'Subject of the message',
  `content` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'The submitted text',
  `sendDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time the message was sent',
  `readStatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Has the user read the mail',
  `deleted` timestamp NULL DEFAULT NULL COMMENT 'Moved to trash'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user__overview`
--

CREATE TABLE `user__overview` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name',
  `surname` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Last name',
  `mail` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'E Mail Adress',
  `phone` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Phone number',
  `mobile` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Mobile phone',
  `street` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Street and Hoursenumber',
  `postalcode` int(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Zip code',
  `region` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Region that belongs to the zip code',
  `birthday` date NOT NULL COMMENT 'Birthday',
  `status` enum('s','t','h') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Student or tutor or headmaster',
  `grade` int(2) UNSIGNED DEFAULT NULL COMMENT 'Grade the user is in',
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Username'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user__password`
--

CREATE TABLE `user__password` (
  `id` int(11) NOT NULL COMMENT 'User id',
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The md5 of the password',
  `passwordAppendix` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The appendix of the password',
  `forget` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Has the user clicked on the forget password button'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user__permission`
--

CREATE TABLE `user__permission` (
  `id` int(11) NOT NULL,
  `admin` tinyint(1) DEFAULT NULL COMMENT 'Is the user an admin',
  `teacher` tinyint(1) DEFAULT NULL COMMENT 'Is the user a teacher'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `admin__content`
--
ALTER TABLE `admin__content`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `chat__badWords`
--
ALTER TABLE `chat__badWords`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `badWord` (`badWord`);

--
-- Indizes für die Tabelle `chat__groups`
--
ALTER TABLE `chat__groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `groupName` (`groupName`);

--
-- Indizes für die Tabelle `chat__messages`
--
ALTER TABLE `chat__messages`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `chat__online`
--
ALTER TABLE `chat__online`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userId` (`userId`);

--
-- Indizes für die Tabelle `chat__reports`
--
ALTER TABLE `chat__reports`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `course__overview`
--
ALTER TABLE `course__overview`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Teacher` (`teacherID`);

--
-- Indizes für die Tabelle `course__student`
--
ALTER TABLE `course__student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courseID` (`classID`),
  ADD KEY `studentID` (`studentID`);

--
-- Indizes für die Tabelle `debug__debugger`
--
ALTER TABLE `debug__debugger`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `debug__logger`
--
ALTER TABLE `debug__logger`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `event__participants`
--
ALTER TABLE `event__participants`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `event__ticker`
--
ALTER TABLE `event__ticker`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `event__upcoming`
--
ALTER TABLE `event__upcoming`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `forum__forums`
--
ALTER TABLE `forum__forums`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `forum__post`
--
ALTER TABLE `forum__post`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `forum__topic`
--
ALTER TABLE `forum__topic`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `help__main`
--
ALTER TABLE `help__main`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `question` (`question`);

--
-- Indizes für die Tabelle `help__topics`
--
ALTER TABLE `help__topics`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `homework__material`
--
ALTER TABLE `homework__material`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indizes für die Tabelle `homework__overview`
--
ALTER TABLE `homework__overview`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lesson__attended`
--
ALTER TABLE `lesson__attended`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `lesson__overview`
--
ALTER TABLE `lesson__overview`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `permission__names`
--
ALTER TABLE `permission__names`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permNode` (`permNode`);

--
-- Indizes für die Tabelle `permission__users`
--
ALTER TABLE `permission__users`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `task__priority`
--
ALTER TABLE `task__priority`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prioVal` (`prioVal`);

--
-- Indizes für die Tabelle `task__toDo`
--
ALTER TABLE `task__toDo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `typeID` (`typeID`);

--
-- Indizes für die Tabelle `task__type`
--
ALTER TABLE `task__type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `content` (`content`);

--
-- Indizes für die Tabelle `test__other`
--
ALTER TABLE `test__other`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `test__task`
--
ALTER TABLE `test__task`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `test__test`
--
ALTER TABLE `test__test`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `test__try`
--
ALTER TABLE `test__try`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `timetable__overview`
--
ALTER TABLE `timetable__overview`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `timetable__standardTimes`
--
ALTER TABLE `timetable__standardTimes`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `user__friends`
--
ALTER TABLE `user__friends`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `user__interface`
--
ALTER TABLE `user__interface`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `user__messages`
--
ALTER TABLE `user__messages`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `user__overview`
--
ALTER TABLE `user__overview`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Only one username` (`name`);

--
-- Indizes für die Tabelle `user__password`
--
ALTER TABLE `user__password`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `user__permission`
--
ALTER TABLE `user__permission`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `admin__content`
--
ALTER TABLE `admin__content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT für Tabelle `chat__badWords`
--
ALTER TABLE `chat__badWords`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT für Tabelle `chat__groups`
--
ALTER TABLE `chat__groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `chat__messages`
--
ALTER TABLE `chat__messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT für Tabelle `chat__online`
--
ALTER TABLE `chat__online`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT für Tabelle `chat__reports`
--
ALTER TABLE `chat__reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `course__overview`
--
ALTER TABLE `course__overview`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id of the course', AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `course__student`
--
ALTER TABLE `course__student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `debug__debugger`
--
ALTER TABLE `debug__debugger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;
--
-- AUTO_INCREMENT für Tabelle `debug__logger`
--
ALTER TABLE `debug__logger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=403;
--
-- AUTO_INCREMENT für Tabelle `event__participants`
--
ALTER TABLE `event__participants`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `event__ticker`
--
ALTER TABLE `event__ticker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `event__upcoming`
--
ALTER TABLE `event__upcoming`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'The Id of the event', AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `forum__forums`
--
ALTER TABLE `forum__forums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id of the forum', AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT für Tabelle `forum__post`
--
ALTER TABLE `forum__post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `forum__topic`
--
ALTER TABLE `forum__topic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id of the topic', AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT für Tabelle `help__main`
--
ALTER TABLE `help__main`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT für Tabelle `help__topics`
--
ALTER TABLE `help__topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT für Tabelle `homework__material`
--
ALTER TABLE `homework__material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `homework__overview`
--
ALTER TABLE `homework__overview`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT für Tabelle `lesson__attended`
--
ALTER TABLE `lesson__attended`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `lesson__overview`
--
ALTER TABLE `lesson__overview`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT für Tabelle `permission__names`
--
ALTER TABLE `permission__names`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `permission__users`
--
ALTER TABLE `permission__users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT für Tabelle `task__priority`
--
ALTER TABLE `task__priority`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT für Tabelle `task__toDo`
--
ALTER TABLE `task__toDo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `task__type`
--
ALTER TABLE `task__type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT für Tabelle `test__other`
--
ALTER TABLE `test__other`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `test__task`
--
ALTER TABLE `test__task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `test__test`
--
ALTER TABLE `test__test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `test__try`
--
ALTER TABLE `test__try`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `timetable__overview`
--
ALTER TABLE `timetable__overview`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT für Tabelle `timetable__standardTimes`
--
ALTER TABLE `timetable__standardTimes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT für Tabelle `user__friends`
--
ALTER TABLE `user__friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT für Tabelle `user__interface`
--
ALTER TABLE `user__interface`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT für Tabelle `user__messages`
--
ALTER TABLE `user__messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT für Tabelle `user__overview`
--
ALTER TABLE `user__overview`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT für Tabelle `user__password`
--
ALTER TABLE `user__password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'User id', AUTO_INCREMENT=4;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `course__student`
--
ALTER TABLE `course__student`
  ADD CONSTRAINT `course__student_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `user__overview` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
