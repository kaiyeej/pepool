-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.24-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.4.0.6659
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for pepool_db
CREATE DATABASE IF NOT EXISTS `pepool_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `pepool_db`;

-- Dumping structure for table pepool_db.tbl_chat
CREATE TABLE IF NOT EXISTS `tbl_chat` (
  `chat_id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  PRIMARY KEY (`chat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table pepool_db.tbl_chat: ~0 rows (approximately)

-- Dumping structure for table pepool_db.tbl_chat_messages
CREATE TABLE IF NOT EXISTS `tbl_chat_messages` (
  `chat_message_id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_id` int(11) NOT NULL DEFAULT 0,
  `sender_id` int(11) NOT NULL DEFAULT 0,
  `content` text NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`chat_message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table pepool_db.tbl_chat_messages: ~0 rows (approximately)

-- Dumping structure for table pepool_db.tbl_contracts
CREATE TABLE IF NOT EXISTS `tbl_contracts` (
  `contract_id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL DEFAULT 0,
  `contract_content` text NOT NULL,
  `employeer` int(11) NOT NULL DEFAULT 0,
  `employee` int(11) NOT NULL DEFAULT 0,
  `contract_key` varchar(100) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`contract_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table pepool_db.tbl_contracts: ~0 rows (approximately)

-- Dumping structure for table pepool_db.tbl_job_posting
CREATE TABLE IF NOT EXISTS `tbl_job_posting` (
  `job_post_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `job_type_id` int(11) NOT NULL,
  `job_title` varchar(100) NOT NULL,
  `job_desc` text NOT NULL,
  `job_fee` decimal(11,2) NOT NULL,
  `job_post_coordinates` text NOT NULL,
  `job_term` varchar(10) NOT NULL DEFAULT '',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `job_post_status` varchar(1) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`job_post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

-- Dumping data for table pepool_db.tbl_job_posting: ~9 rows (approximately)
INSERT INTO `tbl_job_posting` (`job_post_id`, `user_id`, `job_type_id`, `job_title`, `job_desc`, `job_fee`, `job_post_coordinates`, `job_term`, `start_date`, `end_date`, `job_post_status`, `date_added`) VALUES
	(18, 6, 4, 'Kargador', 'finding perfect kargador', 50000.00, '10.683689, 122.951301', '', NULL, NULL, 'P', '2024-07-07 20:42:58'),
	(19, 6, 3, 'Panday', 'finding perfect panday', 23000.00, '10.682887, 122.945421', '', NULL, NULL, 'P', '2024-07-07 20:53:19'),
	(20, 6, 1, 'Software Developer', 'finding 2 perfect software developer', 2000.00, '11.7735424,124.8886784', '', NULL, NULL, 'P', '2024-07-07 20:57:36'),
	(21, 6, 5, 'sports coach', 'sample new job type', 1000.00, '11.7735424,124.8886784', '', NULL, NULL, 'P', '2024-07-08 21:15:54'),
	(22, 6, 6, 'dietician', 'sample dietician', 5000.00, '11.7735424,124.8886784', '', NULL, NULL, 'O', '2024-07-08 21:17:01'),
	(23, 6, 7, 'accountant', 'sample', 100.00, '12.5075456,124.653088', '', NULL, NULL, 'P', '2024-07-12 17:08:09'),
	(24, 6, 8, 'football coach', 'sample', 2500.00, '12.5075456,124.693088', '', NULL, NULL, 'P', '2024-07-12 19:39:34'),
	(25, 6, 9, 'ENGLISH TUTOR', 'afternoon english tutor for 2 years old\ngive exams\ngive quizzes', 5000.00, '10.2924288,123.9678976', 'Fixed', '2024-07-15', '2024-07-19', 'P', '2024-07-14 17:54:05'),
	(26, 3, 10, 'House Cleaner', 'cleans house every day\nstay in\n', 5000.00, '10.2924288,123.9778976', 'Indefinite', '0000-00-00', '0000-00-00', 'P', '2024-07-14 17:57:58'),
	(27, 6, 11, 'graphics designer', 'design fb posts\ndesign banner\ndesign logo', 14000.00, '10.2924288,123.9678976', 'Fixed', '2024-07-15', '2024-07-31', 'P', '2024-07-14 20:52:07');

-- Dumping structure for table pepool_db.tbl_job_types
CREATE TABLE IF NOT EXISTS `tbl_job_types` (
  `job_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_type` varchar(150) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`job_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Dumping data for table pepool_db.tbl_job_types: ~9 rows (approximately)
INSERT INTO `tbl_job_types` (`job_type_id`, `job_type`, `user_id`, `date_added`, `date_modified`) VALUES
	(1, 'Software Developer', 0, '2024-07-04 16:12:41', '2024-07-07 16:38:30'),
	(3, 'Panday', 0, '2024-07-04 16:12:41', '2024-07-04 16:12:41'),
	(4, 'Kargador', 0, '2024-07-04 16:13:01', '2024-07-04 16:13:01'),
	(5, 'sports coach', 6, '2024-07-08 21:15:34', '2024-07-08 21:15:34'),
	(6, 'dietician', 6, '2024-07-08 21:16:52', '2024-07-08 21:16:52'),
	(7, 'accountant', 6, '2024-07-12 17:08:04', '2024-07-12 17:08:04'),
	(8, 'FOOTBALL COACH', 6, '2024-07-12 19:39:22', '2024-07-12 19:39:22'),
	(9, 'ENGLISH TUTOR', 3, '2024-07-14 17:46:01', '2024-07-14 17:46:01'),
	(10, 'HOUSE CLEANER', 3, '2024-07-14 17:57:16', '2024-07-14 17:57:16'),
	(11, 'GRAPHICS DESIGNER', 6, '2024-07-14 20:51:03', '2024-07-14 20:51:03');

-- Dumping structure for table pepool_db.tbl_transactions
CREATE TABLE IF NOT EXISTS `tbl_transactions` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_post_id` int(11) NOT NULL,
  `reference_number` varchar(50) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL,
  `bidding_amount` decimal(11,2) NOT NULL,
  `status` varchar(1) NOT NULL,
  `transaction_rating` int(1) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table pepool_db.tbl_transactions: ~2 rows (approximately)
INSERT INTO `tbl_transactions` (`transaction_id`, `job_post_id`, `reference_number`, `user_id`, `bidding_amount`, `status`, `transaction_rating`, `date_added`) VALUES
	(1, 25, '0714240739093', 3, 5000.00, 'P', 0, '2024-07-14 19:39:09'),
	(4, 26, '0714240813526', 6, 5000.00, 'P', 0, '2024-07-14 20:13:52');

-- Dumping structure for table pepool_db.tbl_users
CREATE TABLE IF NOT EXISTS `tbl_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_fname` varchar(75) NOT NULL,
  `user_mname` varchar(75) NOT NULL,
  `user_lname` varchar(75) NOT NULL,
  `user_category` varchar(15) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_contact_number` varchar(15) NOT NULL,
  `user_photo` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table pepool_db.tbl_users: ~2 rows (approximately)
INSERT INTO `tbl_users` (`user_id`, `user_fname`, `user_mname`, `user_lname`, `user_category`, `username`, `password`, `user_email`, `user_contact_number`, `user_photo`, `date_added`, `date_modified`) VALUES
	(3, 'Juan', 'Santos', 'Dela Cruz', '', 'admin', '0cc175b9c0f1b6a831c399e269772661', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(4, 's', 's', 's', '', 's', '03c7c0ace395d80182db07ae2c30f034', '', '', '', '2024-07-04 11:01:35', '2024-07-04 11:01:35'),
	(6, 'Jeffred', '', 'Pacheco', 'U', 'iamjeffredlim.2@gmail.com', '', 'iamjeffredlim.2@gmail.com', '', 'https://lh3.googleusercontent.com/a/ACg8ocLSCrVFrvPfJSoyYFaDO_ai4cq25gTgv_RRR4qHKi27kuX5Tw=s96-c', '2024-07-06 20:36:06', '2024-07-06 20:36:06');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
