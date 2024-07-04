-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 04, 2024 at 10:45 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pepool_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_chat`
--

CREATE TABLE `tbl_chat` (
  `chat_id` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_chat_messages`
--

CREATE TABLE `tbl_chat_messages` (
  `chat_message_id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL DEFAULT '0',
  `sender_id` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contracts`
--

CREATE TABLE `tbl_contracts` (
  `contract_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL DEFAULT '0',
  `contract_content` text NOT NULL,
  `employeer` int(11) NOT NULL DEFAULT '0',
  `employee` int(11) NOT NULL DEFAULT '0',
  `contract_key` varchar(100) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_job_posting`
--

CREATE TABLE `tbl_job_posting` (
  `job_post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_type_id` int(11) NOT NULL,
  `job_title` varchar(100) NOT NULL,
  `job_desc` text NOT NULL,
  `job_fee` decimal(11,2) NOT NULL,
  `job_post_coordinates` text NOT NULL,
  `job_post_status` varchar(1) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_job_posting`
--

INSERT INTO `tbl_job_posting` (`job_post_id`, `user_id`, `job_type_id`, `job_title`, `job_desc`, `job_fee`, `job_post_coordinates`, `job_post_status`, `date_added`) VALUES
(1, 3, 1, 'Payroll Associate', 'Qualifications:\r\n\r\nBS accountancy/BS accounting associate/or any business-related course graduate;\r\nExcellent with computer applications with above average to Advanced working knowledge on Microsoft Office platforms primarily on Excel;\r\nMust be fluent in English;\r\n3-5 years of experience;\r\nPreferably with knowledge in using accounting software platform primarily QuickBooks. Knowledge in any accounting software will do;\r\nExperience in US payroll processing is preferrable, but any payroll processing experience will also do.', '15000.00', '', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_job_types`
--

CREATE TABLE `tbl_job_types` (
  `job_type_id` int(11) NOT NULL,
  `job_type` varchar(150) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_job_types`
--

INSERT INTO `tbl_job_types` (`job_type_id`, `job_type`, `date_added`, `date_modified`) VALUES
(1, 'Software Developer', '0000-00-00 00:00:00', '2024-07-04 12:01:34'),
(3, 'Panday', '2024-07-04 16:12:41', '2024-07-04 16:12:41'),
(4, 'Kargador', '2024-07-04 16:13:01', '2024-07-04 16:13:01');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transactions`
--

CREATE TABLE `tbl_transactions` (
  `transaction_id` int(11) NOT NULL,
  `job_post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bidding_fee` decimal(11,2) NOT NULL,
  `status` varchar(1) NOT NULL,
  `transaction_rating` int(1) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `user_fname` varchar(75) NOT NULL,
  `user_mname` varchar(75) NOT NULL,
  `user_lname` varchar(75) NOT NULL,
  `user_category` varchar(15) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_photo` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `user_fname`, `user_mname`, `user_lname`, `user_category`, `username`, `password`, `user_email`, `user_photo`, `date_added`, `date_modified`) VALUES
(3, 'Juan', 'Santos', 'Dela Cruz', '', 'admin', '0cc175b9c0f1b6a831c399e269772661', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 's', 's', 's', '', 's', '03c7c0ace395d80182db07ae2c30f034', '', '', '2024-07-04 11:01:35', '2024-07-04 11:01:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_chat`
--
ALTER TABLE `tbl_chat`
  ADD PRIMARY KEY (`chat_id`);

--
-- Indexes for table `tbl_chat_messages`
--
ALTER TABLE `tbl_chat_messages`
  ADD PRIMARY KEY (`chat_message_id`);

--
-- Indexes for table `tbl_contracts`
--
ALTER TABLE `tbl_contracts`
  ADD PRIMARY KEY (`contract_id`);

--
-- Indexes for table `tbl_job_posting`
--
ALTER TABLE `tbl_job_posting`
  ADD PRIMARY KEY (`job_post_id`);

--
-- Indexes for table `tbl_job_types`
--
ALTER TABLE `tbl_job_types`
  ADD PRIMARY KEY (`job_type_id`);

--
-- Indexes for table `tbl_transactions`
--
ALTER TABLE `tbl_transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_chat`
--
ALTER TABLE `tbl_chat`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_chat_messages`
--
ALTER TABLE `tbl_chat_messages`
  MODIFY `chat_message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_contracts`
--
ALTER TABLE `tbl_contracts`
  MODIFY `contract_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_job_posting`
--
ALTER TABLE `tbl_job_posting`
  MODIFY `job_post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_job_types`
--
ALTER TABLE `tbl_job_types`
  MODIFY `job_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_transactions`
--
ALTER TABLE `tbl_transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
