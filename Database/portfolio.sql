-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for personal_portfolio
CREATE DATABASE IF NOT EXISTS `personal_portfolio` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `personal_portfolio`;

-- Dumping structure for table personal_portfolio.admin
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table personal_portfolio.admin: ~0 rows (approximately)
INSERT INTO `admin` (`id`, `username`, `password`, `created_at`) VALUES
	(2, 'admin', 'admin123', '2026-02-23 09:16:45');

-- Dumping structure for table personal_portfolio.contact_messages
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table personal_portfolio.contact_messages: ~0 rows (approximately)

-- Dumping structure for table personal_portfolio.education
CREATE TABLE IF NOT EXISTS `education` (
  `id` int NOT NULL AUTO_INCREMENT,
  `level` varchar(50) DEFAULT NULL,
  `institute` varchar(150) DEFAULT NULL,
  `board_university` varchar(100) DEFAULT NULL,
  `start_year` year DEFAULT NULL,
  `end_year` year DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table personal_portfolio.education: ~0 rows (approximately)
INSERT INTO `education` (`id`, `level`, `institute`, `board_university`, `start_year`, `end_year`, `description`) VALUES
	(1, '10th', 'SMT RS High School', 'BSEB', '2017', '2019', ''),
	(2, '12th', 'GD College, Begusarai', 'BSEB', '2019', '2021', ''),
	(3, 'B.Tech', 'Ambalika Institute Of Management And Technology', 'AKTU', '2022', '2026', '');

-- Dumping structure for table personal_portfolio.experience
CREATE TABLE IF NOT EXISTS `experience` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_name` varchar(150) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `description` text,
  `is_current` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table personal_portfolio.experience: ~0 rows (approximately)
INSERT INTO `experience` (`id`, `company_name`, `role`, `start_date`, `end_date`, `description`, `is_current`) VALUES
	(3, 'E2X Infotech Technologies', 'Backend Developer', '2026-02-16', NULL, '', 1);

-- Dumping structure for table personal_portfolio.favorites
CREATE TABLE IF NOT EXISTS `favorites` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table personal_portfolio.favorites: ~0 rows (approximately)
INSERT INTO `favorites` (`id`, `category`) VALUES
	(1, 'Movie'),
	(2, 'Bike Racing'),
	(3, 'Traveling');

-- Dumping structure for table personal_portfolio.profile
CREATE TABLE IF NOT EXISTS `profile` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `title` varchar(150) DEFAULT NULL,
  `about` text,
  `profile_photo` varchar(255) DEFAULT NULL,
  `resume_pdf` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table personal_portfolio.profile: ~0 rows (approximately)
INSERT INTO `profile` (`id`, `name`, `title`, `about`, `profile_photo`, `resume_pdf`) VALUES
	(8, 'Prashant Kumar', 'Backend Developer', 'Hi, I’m Prashant Kumar, a passionate B.Tech Computer Science student from Bihar. I enjoy building web and software projects that solve real-world problems and enhance user experience. I’m constantly learning new technologies and improving my skills in PHP, MySQL, HTML, CSS, JavaScript, and more.\r\n\r\nI love creating clean, responsive, and user-friendly designs. I’m open to internships and opportunities where I can apply my skills, learn, and contribute to impactful projects.', '1771849093_profile.jpg', '1771849093_Uploaded resume.pdf');

-- Dumping structure for table personal_portfolio.projects
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_name` varchar(150) DEFAULT NULL,
  `description` text,
  `technologies` varchar(255) DEFAULT NULL,
  `project_image` varchar(255) DEFAULT NULL,
  `github_link` varchar(255) DEFAULT NULL,
  `live_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table personal_portfolio.projects: ~0 rows (approximately)
INSERT INTO `projects` (`id`, `project_name`, `description`, `technologies`, `project_image`, `github_link`, `live_link`, `created_at`) VALUES
	(4, 'Online Food Ordering System', 'A platform for users to browse restaurants, filter items by category (Veg/Non-Veg), and place orders. Admin can manage menus and orders easily through a dashboard.', 'PHP, MySQL, HTML, CSS, JavaScript', '', 'https://github.com/yourusername/online-food-ordering', 'https://github.com/yourusername/online-food-ordering', '2026-02-23 11:16:51'),
	(5, 'Daily Expense Tracker', 'A web application to track daily expenses, categorize them, and view monthly reports. Users can add, edit, and delete expenses with a clean and responsive interface.\r\nTechnologies Used: PHP, MySQL, HTML, CSS, JavaScript', 'PHP, MySQL, HTML, CSS, JavaScript', '', 'https://github.com/yourusername/daily-expense-tracker', 'https://github.com/yourusername/daily-expense-tracker', '2026-02-23 11:17:41'),
	(6, 'Doctor Appointment & Patient Records System', 'A system to manage doctor appointments and patient records efficiently. Features include adding, updating, and viewing patient details, as well as scheduling appointments with notifications.', 'PHP, MySQL, HTML, CSS, JavaScript', '', 'https://github.com/yourusername/doctor-appointment-system', 'https://github.com/yourusername/doctor-appointment-system', '2026-02-23 11:18:45');

-- Dumping structure for table personal_portfolio.skills
CREATE TABLE IF NOT EXISTS `skills` (
  `id` int NOT NULL AUTO_INCREMENT,
  `skill_name` varchar(100) DEFAULT NULL,
  `skill_type` enum('Technical','Soft') DEFAULT NULL,
  `skill_level` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table personal_portfolio.skills: ~0 rows (approximately)
INSERT INTO `skills` (`id`, `skill_name`, `skill_type`, `skill_level`) VALUES
	(5, 'php', 'Technical', 70),
	(6, 'mysql', 'Technical', 70),
	(7, 'java', 'Technical', 70),
	(8, 'html', 'Technical', 70),
	(9, 'css', 'Technical', 70),
	(10, 'javascript', 'Technical', 70),
	(11, 'laravel', 'Technical', 70),
	(12, 'ajax', 'Technical', 70),
	(13, 'jwt', 'Technical', 70),
	(14, 'authentication', 'Technical', 70),
	(15, 'oauth', 'Technical', 70),
	(16, 'authentication', 'Technical', 70);

-- Dumping structure for table personal_portfolio.travels
CREATE TABLE IF NOT EXISTS `travels` (
  `id` int NOT NULL AUTO_INCREMENT,
  `place_name` varchar(150) DEFAULT NULL,
  `travel_date` date DEFAULT NULL,
  `description` text,
  `travel_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table personal_portfolio.travels: ~0 rows (approximately)
INSERT INTO `travels` (`id`, `place_name`, `travel_date`, `description`, `travel_image`) VALUES
	(1, 'Varanasi', '2024-05-15', 'Varanasi, also known as Kashi, is one of the world’s oldest cities, located on the banks of the sacred Ganges River. Famous for its ghats, temples, and spiritual heritage, it is a vibrant center of culture, music, and traditional silk weaving. The city beautifully blends history, devotion, and tradition, offering a unique glimpse into India’s timeless essence.', '1771848432_dean.png');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
