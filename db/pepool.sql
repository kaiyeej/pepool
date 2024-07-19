-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.38-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.6.0.6765
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
CREATE DATABASE IF NOT EXISTS `pepool_db` /*!40100 DEFAULT CHARACTER SET latin1 */;
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
  `chat_id` int(11) NOT NULL DEFAULT '0',
  `sender_id` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`chat_message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table pepool_db.tbl_chat_messages: ~0 rows (approximately)

-- Dumping structure for table pepool_db.tbl_contracts
CREATE TABLE IF NOT EXISTS `tbl_contracts` (
  `contract_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_post_id` int(11) NOT NULL DEFAULT '0',
  `client_user_id` int(11) NOT NULL DEFAULT '0',
  `client_did_sign` varchar(1) NOT NULL,
  `worker_user_id` int(11) NOT NULL DEFAULT '0',
  `worker_did_sign` varchar(1) NOT NULL,
  `contract_key` text NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- Dumping data for table pepool_db.tbl_job_posting: ~4 rows (approximately)
INSERT INTO `tbl_job_posting` (`job_post_id`, `user_id`, `job_type_id`, `job_title`, `job_desc`, `job_fee`, `job_post_coordinates`, `job_term`, `start_date`, `end_date`, `job_post_status`, `date_added`) VALUES
	(7, 3, 22, 'marketing associate', 'finding perfect marketing associate', 5000.00, '10.672986, 122.943688', 'Fixed', '2024-07-15', '2024-07-21', 'F', '2024-07-08 11:28:42'),
	(8, 2, 18, 'test 1', 'test', 1500.00, '10.6929534,122.9331906', 'Indefinite', NULL, NULL, 'P', '2024-07-12 15:02:30'),
	(9, 3, 8, 'Education, Teaching, and Training', 'test 2', 5000.00, '10.6929534,122.9631906', 'Indefinite', NULL, NULL, 'P', '2024-07-12 15:03:18'),
	(10, 3, 1, 'Accountant', 'test', 4.00, '10.6929534,122.9331906', 'Indefinite', NULL, NULL, 'P', '2024-07-12 15:05:31'),
	(13, 3, 23, 'FOOD PANDA DRIVER', 'deliver foods\ncontact buyer\nhave profession driver\'s license', 5000.00, '14.5995124,120.9842195', 'Indefinite', '2024-07-09', '0000-00-00', 'O', '2024-07-16 11:45:12'),
	(14, 4, 23, 'FOOD PANDA DRIVER', 'test\ntest\ntest\ntest', 15000.00, '14.6095124,121.0052195', 'Indefinite', '0000-00-00', '0000-00-00', 'P', '2024-07-17 09:46:40');

-- Dumping structure for table pepool_db.tbl_job_types
CREATE TABLE IF NOT EXISTS `tbl_job_types` (
  `job_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_type` varchar(150) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`job_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- Dumping data for table pepool_db.tbl_job_types: ~19 rows (approximately)
INSERT INTO `tbl_job_types` (`job_type_id`, `job_type`, `user_id`, `date_added`, `date_modified`) VALUES
	(1, 'Accountant', 0, '2024-07-03 14:24:25', '2024-07-03 14:24:25'),
	(2, 'Announcer', 0, '2024-07-03 14:24:45', '2024-07-03 14:24:45'),
	(4, 'Computer Hardware Engineers', 0, '2024-07-03 14:25:15', '2024-07-03 14:25:15'),
	(5, 'Computer Programmer', 0, '2024-07-03 14:25:27', '2024-07-03 14:25:27'),
	(6, 'Dancers and Choreographers', 0, '2024-07-03 14:25:44', '2024-07-03 14:25:44'),
	(7, 'Dietician and Nutritionists', 0, '2024-07-03 14:26:04', '2024-07-03 14:26:04'),
	(8, 'Education, Teaching, and Training', 0, '2024-07-03 14:26:25', '2024-07-03 14:26:25'),
	(9, 'Electrician', 0, '2024-07-03 14:26:34', '2024-07-03 14:26:34'),
	(10, 'Graphics Designer', 0, '2024-07-03 14:27:01', '2024-07-03 14:27:01'),
	(11, 'Interpreter and Translator', 0, '2024-07-03 14:27:31', '2024-07-03 14:27:31'),
	(12, 'Legal and Paralegal Services', 0, '2024-07-03 14:27:45', '2024-07-03 14:27:45'),
	(13, 'Photographer', 0, '2024-07-03 14:28:08', '2024-07-03 14:28:08'),
	(14, 'Technical Writer', 0, '2024-07-03 14:28:25', '2024-07-03 14:28:25'),
	(15, 'Web Developer', 0, '2024-07-03 14:28:35', '2024-07-03 14:28:35'),
	(16, 'Writers and Authors', 0, '2024-07-03 14:28:45', '2024-07-03 14:28:45'),
	(18, 'test 1', 2, '2024-07-08 11:05:52', '2024-07-08 11:05:52'),
	(19, 'test 2', 2, '2024-07-08 11:15:05', '2024-07-08 11:15:05'),
	(20, 'test 3', 2, '2024-07-08 11:15:51', '2024-07-08 11:15:51'),
	(21, 'test 4', 2, '2024-07-08 11:16:45', '2024-07-08 11:16:45'),
	(22, 'marketing associate', 2, '2024-07-08 11:28:21', '2024-07-08 11:28:21'),
	(23, 'FOOD PANDA DRIVER', 2, '2024-07-16 11:42:26', '2024-07-16 11:42:26');

-- Dumping structure for table pepool_db.tbl_preferred_jobs
CREATE TABLE IF NOT EXISTS `tbl_preferred_jobs` (
  `preferred_job_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_type_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`preferred_job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

-- Dumping data for table pepool_db.tbl_preferred_jobs: ~28 rows (approximately)
INSERT INTO `tbl_preferred_jobs` (`preferred_job_id`, `job_type_id`, `user_id`) VALUES
	(16, 1, 3),
	(24, 5, 3),
	(27, 6, 3),
	(28, 23, 3),
	(29, 13, 3),
	(30, 1, 2),
	(31, 2, 2),
	(32, 4, 2),
	(33, 23, 2),
	(34, 6, 2),
	(35, 23, 4),
	(36, 10, 4),
	(37, 11, 4),
	(38, 8, 5),
	(39, 9, 5),
	(40, 23, 5),
	(41, 10, 5),
	(42, 11, 5),
	(43, 23, 6),
	(44, 9, 6),
	(45, 8, 6),
	(46, 7, 6),
	(47, 6, 6),
	(48, 9, 7),
	(49, 23, 7),
	(50, 8, 7),
	(51, 7, 7),
	(52, 6, 7),
	(53, 5, 8),
	(54, 6, 8),
	(55, 23, 8);

-- Dumping structure for table pepool_db.tbl_transactions
CREATE TABLE IF NOT EXISTS `tbl_transactions` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_number` varchar(50) NOT NULL DEFAULT '',
  `job_post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bidding_amount` decimal(11,2) NOT NULL,
  `status` varchar(1) NOT NULL,
  `transaction_rating` int(1) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table pepool_db.tbl_transactions: ~3 rows (approximately)
INSERT INTO `tbl_transactions` (`transaction_id`, `reference_number`, `job_post_id`, `user_id`, `bidding_amount`, `status`, `transaction_rating`, `date_added`) VALUES
	(1, '0716240144302', 13, 2, 4500.00, 'F', 0, '2024-07-16 13:44:30'),
	(2, '0716240201463', 7, 2, 5000.00, 'F', 5, '2024-07-16 14:01:46'),
	(3, '0716240201463', 14, 2, 5000.00, 'F', 5, '2024-07-16 14:01:46');

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
  `user_rating` decimal(4,2) NOT NULL DEFAULT '0.00',
  `user_photo` text NOT NULL,
  `e_sginature` text NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table pepool_db.tbl_users: ~8 rows (approximately)
INSERT INTO `tbl_users` (`user_id`, `user_fname`, `user_mname`, `user_lname`, `user_category`, `username`, `password`, `user_email`, `user_contact_number`, `user_rating`, `user_photo`, `e_sginature`, `date_added`, `date_modified`) VALUES
	(1, '', '', '', 'U', '', '', '', '', 0.00, '', '', '2024-07-11 06:00:00', '0000-00-00 00:00:00'),
	(2, 'Jeffred', '', 'Pacheco', 'U', 'iamjeffredlim.2@gmail.com', '', 'iamjeffredlim.2@gmail.com', '09274018956', 0.00, 'https://lh3.googleusercontent.com/a/ACg8ocLSCrVFrvPfJSoyYFaDO_ai4cq25gTgv_RRR4qHKi27kuX5Tw=s96-c', '', '2024-07-11 06:00:00', '0000-00-00 00:00:00'),
	(3, 'Kaye', '', 'Lim', 'U', 'kayejacildolim@gmail.com', '', 'kayejacildolim@gmail.com', '09274018956', 0.00, 'https://lh3.googleusercontent.com/a/ACg8ocLSCrVFrvPfJSoyYFaDO_ai4cq25gTgv_RRR4qHKi27kuX5Tw=s96-c', '', '2024-07-11 06:00:00', '0000-00-00 00:00:00'),
	(4, 'User 3', '', '3', 'U', 'user3@gmail.com', '', 'user3@gmail.com', '09274018956', 0.00, '', '', '2024-07-11 06:00:00', '0000-00-00 00:00:00'),
	(5, 'User 4', '', '4', 'U', 'kayejacildolim@gmail.com', '', 'user4@gmail.com', '09274018956', 0.00, '', '', '2024-07-11 06:00:00', '0000-00-00 00:00:00'),
	(6, 'User 5', '', '5', 'U', 'kayejacildolim@gmail.com', '', 'user5@gmail.com', '09274018956', 0.00, '', '', '2024-07-11 06:00:00', '0000-00-00 00:00:00'),
	(7, 'User 6', '', '6', 'U', 'kayejacildolim@gmail.com', '', 'user6@gmail.com', '09274018956', 0.00, '', '', '2024-07-11 06:00:00', '0000-00-00 00:00:00'),
	(8, 'User 7', '', '7', 'U', 'kayejacildolim@gmail.com', '', 'user7@gmail.com', '09274018956', 0.00, '', '', '2024-07-11 06:00:00', '0000-00-00 00:00:00');

-- Dumping structure for table pepool_db.tbl_user_address
CREATE TABLE IF NOT EXISTS `tbl_user_address` (
  `user_address_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `coordinates` text,
  `address_name` varchar(50) DEFAULT NULL,
  `date_added` datetime DEFAULT NULL,
  PRIMARY KEY (`user_address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table pepool_db.tbl_user_address: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
