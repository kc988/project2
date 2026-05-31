-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2026 at 11:44 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `renew_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

CREATE TABLE `about` (
  `member_id` int(11) NOT NULL,
  `member_name` varchar(100) NOT NULL,
  `contribution` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about`
--

INSERT INTO `about` (`member_id`, `member_name`, `contribution`) VALUES
(1, 'Moni', 'Designed and developed the About Us page, helped with overall CSS styling, and GitHub page deployment.'),
(2, 'Maria', 'Created the Home page and contributed to layout structure, navigation design, and GitHub page deployment.'),
(3, 'Karim', 'Developed the Jobs page and wrote job descriptions tailored to the sustainable energy industry, as well as GitHub Page deployment and is responsible for the final testing.'),
(4, 'Ishmam', 'Built the Apply page, implemented the application form with HTML5 validation, and GitHub page deployment.');

-- --------------------------------------------------------

--
-- Table structure for table `eoi`
--

CREATE TABLE `eoi` (
  `eoi_id` int(11) NOT NULL,
  `job_reference` varchar(5) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` varchar(10) NOT NULL,
  `street` varchar(40) NOT NULL,
  `suburb` varchar(40) NOT NULL,
  `state` varchar(3) NOT NULL,
  `postcode` varchar(4) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `skills` varchar(255) DEFAULT NULL,
  `other_skills` text DEFAULT NULL,
  `status` enum('New','Current','Final') NOT NULL DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eoi`
--

INSERT INTO `eoi` (`eoi_id`, `job_reference`, `first_name`, `last_name`, `date_of_birth`, `gender`, `street`, `suburb`, `state`, `postcode`, `email`, `phone`, `skills`, `other_skills`, `status`) VALUES
(23, 'PCJ20', 'moni', 'mo', '2000-04-29', 'male', 'Westwood Drive, Burnside VIC, Australia', 'Melbourne', 'VIC', '3000', 'sam.theman1987@hotmail.com', '0400000000', 'Renewable Technology Expertise', 'as', 'Final'),
(25, 'PCJ20', 'moni', 'mo', '2000-04-29', 'male', 'Westwood Drive, Burnside VIC, Australia', 'Melbourne', 'VIC', '3000', 'sam.theman1987@hotmail.com', '0400000000', 'Data Analytics &amp; Digital Literacy', '', 'New'),
(26, 'WCC10', 'test', 'test', '2000-09-10', 'male', 'Westwood Drive, Burnside VIC, Australia', 'Melbourne', 'VIC', '3000', 'sam.theman1987@hotmail.com', '0400000000', 'Energy Auditing &amp; Efficiency', 'sd', 'New');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `reference_number` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `salary_min` int(11) DEFAULT NULL,
  `salary_max` int(11) DEFAULT NULL,
  `reporting_line` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `key_responsibilities` text DEFAULT NULL,
  `essential_requirements` text DEFAULT NULL,
  `preferable_requirements` text DEFAULT NULL,
  `location` varchar(100) DEFAULT 'Melbourne, VIC',
  `work_type` varchar(50) DEFAULT 'Full-time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`reference_number`, `title`, `salary_min`, `salary_max`, `reporting_line`, `description`, `key_responsibilities`, `essential_requirements`, `preferable_requirements`, `location`, `work_type`) VALUES
('PCJ20', 'Project Coordinator', 90000, 100000, 'Digital Manager', 'The Project Coordinator assists with planning and coordinating Projects.', 'Assist with website updates and projects.|Deploy Websites using GitHub pages.|Work with teams using Project Management tools like Jira.|Prepare reports for stakeholders.', 'Experience in project coordination.|Strong organisational skills.|Strong Team Player.', 'Experience with building websites.|Experience working in Jira such as creating Epics and User Stories.|Interest in the sustainability industry.', 'Melbourne, VIC', 'Full-time'),
('WCC10', 'Website Content Coordinator', 78000, 80000, 'Communication Manager', 'The Website Content Coordinator is responsible for updating and maintaining website content, ensuring information is accurate, up-to-date, and easy for users to understand.', 'Maintain and update website content using HTML and CSS.|Ensure HTML and CSS lines meet standard validation.|Ensure Web Accessibility Evaluation is met.|Deploy Website in GitHub.', 'Experience in HTML and CSS.|Strong written and communication skills.|Experience in using GitHub such as performing commits, pushes, and merges.', 'Experience in the sustainability industry.|Knowledge of web accessibility principles.|Adaptability and Organization Skills.', 'Melbourne, VIC', 'Full-time');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$hKrDhLp4ohUlIra1HvO8C.MW4TgrBTPIy1mEXP4cSi7u4iSZPFPn2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `eoi`
--
ALTER TABLE `eoi`
  ADD PRIMARY KEY (`eoi_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`reference_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about`
--
ALTER TABLE `about`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `eoi`
--
ALTER TABLE `eoi`
  MODIFY `eoi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
