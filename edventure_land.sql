-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 24, 2023 at 01:00 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `edventure_land`
--

CREATE DATABASE IF NOT EXISTS edventure_land; -- DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE edventure_land;


-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(14) NOT NULL,
  `email` varchar(320) NOT NULL,
  `password` varchar(255) NOT NULL,
  `school_id` int(11) NOT NULL,
  `verification_code` varchar(6) DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `reset_token` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Table structure for table `assignment`
--

CREATE TABLE IF NOT EXISTS `assignment` (
  `assignment_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `student_group_id` int(11) NOT NULL,
  `math_id` int(11) DEFAULT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `manual_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `badge`
--

CREATE TABLE IF NOT EXISTS `badge` (
  `badge_id` int(11) NOT NULL,
  `badge_name` varchar(60) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `badge_info` text NOT NULL,
  `badge_type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `badge`
--

INSERT INTO `badge` (`badge_id`, `badge_name`, `picture`, `badge_info`, `badge_type`) VALUES
(1, 'mathwhiz', '20_questions.png', 'You answered 20 Formula Frenzy questions correctly! You have become a Formula Frenzy Novice!', 'math'),
(2, 'mathwhiz 2.0', '40_questions.png', 'You answered 40 Formula Frenzy questions correctly! You have become a Formula Frenzy Apprentice!', 'math'),
(3, 'login_badge_1', 'three_days.png', 'You have logged in for 3 days in a row!', 'login'),
(4, 'login_badge_2', 'five_days.png', 'You have logged in for 5 days in a row!', 'login'),
(5, 'login_badge_3', 'ten_days.png', 'You have logged in for ten consecutive days!', 'login'),
(6, 'login_badge_4', 'thirty_days.png', 'You have logged in for thirty consecutive days!', 'login'),
(7, 'ssm_badge_1', 'five_questions.png', 'You have got five questions right in Subject Savvy Millionaire Games!', 'ssm'),
(8, 'ssm_badge_2', 'ten_questions.png', 'You have got ten questions right in Subject Savvy Millionaire Games!', 'ssm'),
(9, 'ssm_badge_3', 'twenty_questions.png', 'You have got twenty questions right in Subject Savvy Millionaire Games!', 'ssm'),
(10, 'ssm_badge_4', 'fifty_questions.png', 'You have got fifty questions right in Subject Savvy Millionaire Games!', 'ssm'),
(11, 'mathwhiz 3.0', '70_questions.png', 'You answered 70 Formula Frenzy questions correctly! You have become a Formula Frenzy Prodigy!', 'math'),
(12, 'mathwhiz 4.0', '100_questions.png', 'You answered 100 Formula Frenzy questions correctly! You have become a Formula Frenzy Genius!', 'math'),
(13, 'leaderboard_1_ssm', 'leaderboard_1_ssm.png', 'You have got first place on the leaderboard for the Subject Savvy Millionaire Game! Great Job!', 'leader'),
(14, 'leaderboard_5_ssm', 'leaderboard_5_ssm.png', 'You have got in the top 5 places on the leaderboard for the Subject Savvy Millionaire Game! Great Job!', 'leader'),
(15, 'leaderboard_1_math', 'leaderboard_1_math.png', 'You have got first place on the leaderboard for the Formula Frenzy Game! Great Job!', 'leader'),
(16, 'leaderboard_5_math', 'leaderboard_5_math.png', 'You have got in the top 5 places on the leaderboard for the Formula Frenzy Game! Great Job!', 'leader');

-- --------------------------------------------------------

--
-- Table structure for table `discussion_board`
--

CREATE TABLE IF NOT EXISTS `discussion_board` (
  `discussion_board_id` int(11) NOT NULL,
  `title` varchar(60) NOT NULL,
  `body` varchar(2500) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `datetime` datetime NOT NULL,
  `reply_id` int(11) DEFAULT NULL,
  `student_group_id` int(11) NOT NULL,
  `anonymous` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `leaderboard`
--

CREATE TABLE IF NOT EXISTS `leaderboard` (
  `leaderboard_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `lifelines_used` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`lifelines_used`)),
  `questions_right` smallint(6) NOT NULL,
  `topic` varchar(60) NOT NULL,
  `subject` varchar(60) NOT NULL,
  `student_id` int(11) NOT NULL,
  `difficulty` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `manual_assignment`
--

CREATE TABLE IF NOT EXISTS `manual_assignment` (
  `manual_assignment_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `student_group_id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `description` varchar(2500) NOT NULL,
  `prior_reading` varchar(10) NOT NULL,
  `points` smallint(6) NOT NULL,
  `test_datetime` datetime NOT NULL,
  `text_box` varchar(5500) DEFAULT NULL,
  `pdf_doc` mediumblob DEFAULT NULL,
  `pdf_name` varchar(255) DEFAULT NULL,
  `ytlink` varchar(60) DEFAULT NULL,
  `link` varchar(250) DEFAULT NULL,
  `submission_type` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `manual_submission`
--

CREATE TABLE IF NOT EXISTS `manual_submission` (
  `manual_submission_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `assignment_done` tinyint(1) NOT NULL,
  `result` smallint(11) DEFAULT NULL,
  `pdf_doc` mediumblob DEFAULT NULL,
  `pdf_name` varchar(255) DEFAULT NULL,
  `text_box` varchar(5500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `math_assignment`
--

CREATE TABLE IF NOT EXISTS `math_assignment` (
  `math_assignment_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `student_group_id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `description` varchar(2500) NOT NULL,
  `points` int(11) NOT NULL,
  `test_datetime` datetime NOT NULL,
  `operators` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`operators`)),
  `difficulty` varchar(6) NOT NULL,
  `duration` smallint(11) NOT NULL,
  `pass_percentage` smallint(11) NOT NULL,
  `min_no_questions` smallint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `math_leaderboard`
--

CREATE TABLE IF NOT EXISTS `math_leaderboard` (
  `leaderboard_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `questions_wrong` int(11) NOT NULL,
  `operators` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `questions_right` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `difficulty` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `math_submission`
--

CREATE TABLE IF NOT EXISTS `math_submission` (
  `math_submission_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `assignment_done` tinyint(1) NOT NULL DEFAULT 0,
  `result` smallint(11) DEFAULT NULL,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `questions_right` int(11) DEFAULT NULL,
  `questions_wrong` int(11) DEFAULT NULL,
  `QPM` int(11) DEFAULT NULL,
  `points` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `questions_and_answers`
--

CREATE TABLE IF NOT EXISTS `questions_and_answers` (
  `questionID` int(11) NOT NULL,
  `quiz_assignment_id` int(11) NOT NULL,
  `question` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`question`)),
  `answer1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`answer1`)),
  `answer2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`answer2`)),
  `answer3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`answer3`)),
  `answer4` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`answer4`)),
  `correctanswer` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`correctanswer`)),
  `hint` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`hint`)),
  `time_per_question` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_assignment`
--

CREATE TABLE IF NOT EXISTS `quiz_assignment` (
  `quiz_assignment_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `student_group_id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `description` varchar(2500) DEFAULT NULL,
  `prior_reading` varchar(10) NOT NULL,
  `points` int(11) NOT NULL,
  `test_datetime` datetime NOT NULL,
  `text_box` varchar(5500) DEFAULT NULL,
  `pdf_doc` mediumblob DEFAULT NULL,
  `pdf_name` varchar(255) DEFAULT NULL,
  `ytlink` varchar(60) DEFAULT NULL,
  `link` varchar(250) DEFAULT NULL,
  `lifelines` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`lifelines`)),
  `shuffle` tinyint(1) NOT NULL,
  `pass_percentage` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_submission`
--

CREATE TABLE IF NOT EXISTS `quiz_submission` (
  `quiz_submission_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `assignment_done` tinyint(1) NOT NULL,
  `result` smallint(11) DEFAULT NULL,
  `lifelines_used` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `attempts` int(11) DEFAULT 0,
  `questions_right` tinyint(11) DEFAULT NULL,
  `questions_total` tinyint(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `school`
--

CREATE TABLE IF NOT EXISTS `school` (
  `school_id` int(11) NOT NULL,
  `school_name` varchar(60) NOT NULL,
  `school_pin` varchar(6) NOT NULL,
  `school_email` varchar(320) NOT NULL,
  `admin_email` varchar(320) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `student_id` int(11) NOT NULL,
  `username` varchar(14) NOT NULL,
  `name` varchar(61) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `surname` varchar(30) NOT NULL,
  `email` varchar(320) NOT NULL,
  `profile_picture` varchar(30) NOT NULL,
  `student_year` int(8) NOT NULL,
  `school_id` int(11) NOT NULL,
  `last_login` date NOT NULL,
  `login_streak` int(11) NOT NULL DEFAULT 0,
  `ssm_count` int(11) NOT NULL DEFAULT 0,
  `math_count` int(11) NOT NULL DEFAULT 0,
  `reset_token` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Table structure for table `student_badge`
--

CREATE TABLE IF NOT EXISTS `student_badge` (
  `student_id` int(11) NOT NULL,
  `badge_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `student_group`
--

CREATE TABLE IF NOT EXISTS `student_group` (
  `student_group_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `number_of_students` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Student_student_group`
--

CREATE TABLE IF NOT EXISTS `Student_student_group` (
  `student_group_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE IF NOT EXISTS `teacher` (
  `teacher_id` int(11) NOT NULL,
  `username` varchar(14) NOT NULL,
  `email` varchar(320) NOT NULL,
  `institute_email` varchar(320) NOT NULL,
  `name` varchar(61) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(30) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `surname` varchar(30) NOT NULL,
  `school_id` int(11) NOT NULL,
  `reset_token` varchar(6) DEFAULT NULL,
  `approval` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Table structure for table `teacher_student`
--

CREATE TABLE IF NOT EXISTS `teacher_student` (
  `teacher_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `points` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_student_group`
--

CREATE TABLE IF NOT EXISTS `teacher_student_group` (
  `teacher_id` int(11) NOT NULL,
  `student_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `assignment`
--
ALTER TABLE `assignment`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `math_id` (`math_id`),
  ADD KEY `assignments_test_ibfk_2` (`manual_id`),
  ADD KEY `assignments_test_ibfk_1` (`quiz_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `student_group_id` (`student_group_id`);

--
-- Indexes for table `badge`
--
ALTER TABLE `badge`
  ADD PRIMARY KEY (`badge_id`);

--
-- Indexes for table `discussion_board`
--
ALTER TABLE `discussion_board`
  ADD PRIMARY KEY (`discussion_board_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `student_group_id` (`student_group_id`),
  ADD KEY `reply_id` (`reply_id`);

--
-- Indexes for table `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD PRIMARY KEY (`leaderboard_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `manual_assignment`
--
ALTER TABLE `manual_assignment`
  ADD PRIMARY KEY (`manual_assignment_id`),
  ADD KEY `student_group_id` (`student_group_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `manual_submission`
--
ALTER TABLE `manual_submission`
  ADD PRIMARY KEY (`manual_submission_id`),
  ADD KEY `assignment_id` (`assignment_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `math_assignment`
--
ALTER TABLE `math_assignment`
  ADD PRIMARY KEY (`math_assignment_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `student_group_id` (`student_group_id`);

--
-- Indexes for table `math_leaderboard`
--
ALTER TABLE `math_leaderboard`
  ADD PRIMARY KEY (`leaderboard_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `math_submission`
--
ALTER TABLE `math_submission`
  ADD PRIMARY KEY (`math_submission_id`),
  ADD KEY `assignment_id` (`assignment_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `questions_and_answers`
--
ALTER TABLE `questions_and_answers`
  ADD PRIMARY KEY (`questionID`),
  ADD KEY `question_and_answers_ibfk_1` (`quiz_assignment_id`);

--
-- Indexes for table `quiz_assignment`
--
ALTER TABLE `quiz_assignment`
  ADD PRIMARY KEY (`quiz_assignment_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `student_group_id` (`student_group_id`);

--
-- Indexes for table `quiz_submission`
--
ALTER TABLE `quiz_submission`
  ADD PRIMARY KEY (`quiz_submission_id`),
  ADD KEY `assignment_id` (`assignment_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `school`
--
ALTER TABLE `school`
  ADD PRIMARY KEY (`school_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `student_badge`
--
ALTER TABLE `student_badge`
  ADD PRIMARY KEY (`student_id`,`badge_id`),
  ADD KEY `badge_id` (`badge_id`);

--
-- Indexes for table `student_group`
--
ALTER TABLE `student_group`
  ADD PRIMARY KEY (`student_group_id`);

--
-- Indexes for table `Student_student_group`
--
ALTER TABLE `Student_student_group`
  ADD PRIMARY KEY (`student_group_id`,`student_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`teacher_id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `teacher_student`
--
ALTER TABLE `teacher_student`
  ADD PRIMARY KEY (`teacher_id`,`student_id`),
  ADD UNIQUE KEY `teacher_id` (`teacher_id`,`student_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `teacher_student_group`
--
ALTER TABLE `teacher_student_group`
  ADD PRIMARY KEY (`teacher_id`,`student_group_id`),
  ADD KEY `student_group_id` (`student_group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `assignment`
--
ALTER TABLE `assignment`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `badge`
--
ALTER TABLE `badge`
  MODIFY `badge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `discussion_board`
--
ALTER TABLE `discussion_board`
  MODIFY `discussion_board_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `leaderboard`
--
ALTER TABLE `leaderboard`
  MODIFY `leaderboard_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `manual_assignment`
--
ALTER TABLE `manual_assignment`
  MODIFY `manual_assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `manual_submission`
--
ALTER TABLE `manual_submission`
  MODIFY `manual_submission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `math_assignment`
--
ALTER TABLE `math_assignment`
  MODIFY `math_assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `math_leaderboard`
--
ALTER TABLE `math_leaderboard`
  MODIFY `leaderboard_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `math_submission`
--
ALTER TABLE `math_submission`
  MODIFY `math_submission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `questions_and_answers`
--
ALTER TABLE `questions_and_answers`
  MODIFY `questionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `quiz_assignment`
--
ALTER TABLE `quiz_assignment`
  MODIFY `quiz_assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `quiz_submission`
--
ALTER TABLE `quiz_submission`
  MODIFY `quiz_submission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `school`
--
ALTER TABLE `school`
  MODIFY `school_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `student_group`
--
ALTER TABLE `student_group`
  MODIFY `student_group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `school` (`school_id`);

--
-- Constraints for table `assignment`
--
ALTER TABLE `assignment`
  ADD CONSTRAINT `assignment_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quiz_assignment` (`quiz_assignment_id`),
  ADD CONSTRAINT `assignment_ibfk_2` FOREIGN KEY (`manual_id`) REFERENCES `manual_assignment` (`manual_assignment_id`),
  ADD CONSTRAINT `assignment_ibfk_3` FOREIGN KEY (`math_id`) REFERENCES `math_assignment` (`math_assignment_id`),
  ADD CONSTRAINT `assignment_ibfk_4` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`),
  ADD CONSTRAINT `assignment_ibfk_5` FOREIGN KEY (`student_group_id`) REFERENCES `student_group` (`student_group_id`);

--
-- Constraints for table `discussion_board`
--
ALTER TABLE `discussion_board`
  ADD CONSTRAINT `discussion_board_ibfk_1` FOREIGN KEY (`student_group_id`) REFERENCES `student_group` (`student_group_id`),
  ADD CONSTRAINT `discussion_board_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`),
  ADD CONSTRAINT `discussion_board_ibfk_3` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`),
  ADD CONSTRAINT `discussion_board_ibfk_4` FOREIGN KEY (`reply_id`) REFERENCES `discussion_board` (`discussion_board_id`) ON DELETE CASCADE;

--
-- Constraints for table `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD CONSTRAINT `leaderboard_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `manual_assignment`
--
ALTER TABLE `manual_assignment`
  ADD CONSTRAINT `manual_assignment_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`),
  ADD CONSTRAINT `manual_assignment_ibfk_2` FOREIGN KEY (`student_group_id`) REFERENCES `student_group` (`student_group_id`);

--
-- Constraints for table `manual_submission`
--
ALTER TABLE `manual_submission`
  ADD CONSTRAINT `manual_submission_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignment` (`assignment_id`),
  ADD CONSTRAINT `manual_submission_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `math_assignment`
--
ALTER TABLE `math_assignment`
  ADD CONSTRAINT `math_assignment_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`),
  ADD CONSTRAINT `math_assignment_ibfk_2` FOREIGN KEY (`student_group_id`) REFERENCES `student_group` (`student_group_id`);

--
-- Constraints for table `math_leaderboard`
--
ALTER TABLE `math_leaderboard`
  ADD CONSTRAINT `math_leaderboard_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `math_submission`
--
ALTER TABLE `math_submission`
  ADD CONSTRAINT `math_submission_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignment` (`assignment_id`),
  ADD CONSTRAINT `math_submission_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `questions_and_answers`
--
ALTER TABLE `questions_and_answers`
  ADD CONSTRAINT `questions_and_answers_ibfk_1` FOREIGN KEY (`quiz_assignment_id`) REFERENCES `quiz_assignment` (`quiz_assignment_id`);

--
-- Constraints for table `quiz_assignment`
--
ALTER TABLE `quiz_assignment`
  ADD CONSTRAINT `quiz_assignment_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`),
  ADD CONSTRAINT `quiz_assignment_ibfk_2` FOREIGN KEY (`student_group_id`) REFERENCES `student_group` (`student_group_id`);

--
-- Constraints for table `quiz_submission`
--
ALTER TABLE `quiz_submission`
  ADD CONSTRAINT `quiz_submission_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignment` (`assignment_id`),
  ADD CONSTRAINT `quiz_submission_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `school` (`school_id`);

--
-- Constraints for table `student_badge`
--
ALTER TABLE `student_badge`
  ADD CONSTRAINT `student_badge_ibfk_1` FOREIGN KEY (`badge_id`) REFERENCES `badge` (`badge_id`),
  ADD CONSTRAINT `student_badge_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `Student_student_group`
--
ALTER TABLE `Student_student_group`
  ADD CONSTRAINT `student_student_group_ibfk_1` FOREIGN KEY (`student_group_id`) REFERENCES `student_group` (`student_group_id`),
  ADD CONSTRAINT `student_student_group_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `teacher_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `school` (`school_id`);

--
-- Constraints for table `teacher_student`
--
ALTER TABLE `teacher_student`
  ADD CONSTRAINT `teacher_student_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`),
  ADD CONSTRAINT `teacher_student_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `teacher_student_group`
--
ALTER TABLE `teacher_student_group`
  ADD CONSTRAINT `teacher_student_group_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`teacher_id`),
  ADD CONSTRAINT `teacher_student_group_ibfk_2` FOREIGN KEY (`student_group_id`) REFERENCES `student_group` (`student_group_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
