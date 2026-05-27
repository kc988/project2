-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2026 at 04:15 AM
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`reference_number`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
