-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour weddingplanner_test
CREATE DATABASE IF NOT EXISTS `weddingplanner_test` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `weddingplanner_test`;

-- Listage de la structure de table weddingplanner_test. appointment
CREATE TABLE IF NOT EXISTS `appointment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime DEFAULT NULL,
  `title` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FE38F844A76ED395` (`user_id`),
  CONSTRAINT `FK_FE38F844A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.appointment : ~0 rows (environ)
DELETE FROM `appointment`;

-- Listage de la structure de table weddingplanner_test. batch
CREATE TABLE IF NOT EXISTS `batch` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.batch : ~0 rows (environ)
DELETE FROM `batch`;

-- Listage de la structure de table weddingplanner_test. bill
CREATE TABLE IF NOT EXISTS `bill` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quotation_id` int NOT NULL,
  `bill_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_creation` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_7A2119E3B4EA4E60` (`quotation_id`),
  CONSTRAINT `FK_7A2119E3B4EA4E60` FOREIGN KEY (`quotation_id`) REFERENCES `quotation` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.bill : ~0 rows (environ)
DELETE FROM `bill`;

-- Listage de la structure de table weddingplanner_test. booking
CREATE TABLE IF NOT EXISTS `booking` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `reservation_id` int NOT NULL,
  `quantite` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E00CEDDE4584665A` (`product_id`),
  KEY `IDX_E00CEDDEB83297E7` (`reservation_id`),
  CONSTRAINT `FK_E00CEDDE4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  CONSTRAINT `FK_E00CEDDEB83297E7` FOREIGN KEY (`reservation_id`) REFERENCES `reservation` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.booking : ~0 rows (environ)
DELETE FROM `booking`;

-- Listage de la structure de table weddingplanner_test. budget
CREATE TABLE IF NOT EXISTS `budget` (
  `id` int NOT NULL AUTO_INCREMENT,
  `min_price` int NOT NULL,
  `max_price` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.budget : ~0 rows (environ)
DELETE FROM `budget`;

-- Listage de la structure de table weddingplanner_test. category
CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.category : ~0 rows (environ)
DELETE FROM `category`;

-- Listage de la structure de table weddingplanner_test. comment
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `user_id` int NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_post` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526C166D1F9C` (`project_id`),
  KEY `IDX_9474526CA76ED395` (`user_id`),
  CONSTRAINT `FK_9474526C166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.comment : ~0 rows (environ)
DELETE FROM `comment`;

-- Listage de la structure de table weddingplanner_test. creation
CREATE TABLE IF NOT EXISTS `creation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `pictures` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_57EE857412469DE2` (`category_id`),
  CONSTRAINT `FK_57EE857412469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.creation : ~0 rows (environ)
DELETE FROM `creation`;

-- Listage de la structure de table weddingplanner_test. destination
CREATE TABLE IF NOT EXISTS `destination` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.destination : ~0 rows (environ)
DELETE FROM `destination`;

-- Listage de la structure de table weddingplanner_test. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table weddingplanner_test.doctrine_migration_versions : ~0 rows (environ)
DELETE FROM `doctrine_migration_versions`;

-- Listage de la structure de table weddingplanner_test. job
CREATE TABLE IF NOT EXISTS `job` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.job : ~0 rows (environ)
DELETE FROM `job`;

-- Listage de la structure de table weddingplanner_test. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.messenger_messages : ~0 rows (environ)
DELETE FROM `messenger_messages`;

-- Listage de la structure de table weddingplanner_test. note
CREATE TABLE IF NOT EXISTS `note` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `project_id` int NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_post` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CFBDFA14A76ED395` (`user_id`),
  KEY `IDX_CFBDFA14166D1F9C` (`project_id`),
  CONSTRAINT `FK_CFBDFA14166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  CONSTRAINT `FK_CFBDFA14A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.note : ~0 rows (environ)
DELETE FROM `note`;

-- Listage de la structure de table weddingplanner_test. prestation
CREATE TABLE IF NOT EXISTS `prestation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_picture` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.prestation : ~0 rows (environ)
DELETE FROM `prestation`;

-- Listage de la structure de table weddingplanner_test. product
CREATE TABLE IF NOT EXISTS `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `batch_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_picture` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D34A04ADF39EBE7A` (`batch_id`),
  CONSTRAINT `FK_D34A04ADF39EBE7A` FOREIGN KEY (`batch_id`) REFERENCES `batch` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.product : ~0 rows (environ)
DELETE FROM `product`;

-- Listage de la structure de table weddingplanner_test. project
CREATE TABLE IF NOT EXISTS `project` (
  `id` int NOT NULL AUTO_INCREMENT,
  `destination_id` int DEFAULT NULL,
  `budget_id` int DEFAULT NULL,
  `state_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `firstname` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(170) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_event` datetime NOT NULL,
  `nb_guest` int NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_receipt` datetime DEFAULT NULL,
  `is_contacted` tinyint(1) NOT NULL,
  `final_price` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2FB3D0EE816C6140` (`destination_id`),
  KEY `IDX_2FB3D0EE36ABA6B8` (`budget_id`),
  KEY `IDX_2FB3D0EE5D83CC1` (`state_id`),
  KEY `IDX_2FB3D0EEA76ED395` (`user_id`),
  CONSTRAINT `FK_2FB3D0EE36ABA6B8` FOREIGN KEY (`budget_id`) REFERENCES `budget` (`id`),
  CONSTRAINT `FK_2FB3D0EE5D83CC1` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`),
  CONSTRAINT `FK_2FB3D0EE816C6140` FOREIGN KEY (`destination_id`) REFERENCES `destination` (`id`),
  CONSTRAINT `FK_2FB3D0EEA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.project : ~0 rows (environ)
DELETE FROM `project`;

-- Listage de la structure de table weddingplanner_test. project_prestation
CREATE TABLE IF NOT EXISTS `project_prestation` (
  `project_id` int NOT NULL,
  `prestation_id` int NOT NULL,
  PRIMARY KEY (`project_id`,`prestation_id`),
  KEY `IDX_496EF49F166D1F9C` (`project_id`),
  KEY `IDX_496EF49F9E45C554` (`prestation_id`),
  CONSTRAINT `FK_496EF49F166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_496EF49F9E45C554` FOREIGN KEY (`prestation_id`) REFERENCES `prestation` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.project_prestation : ~0 rows (environ)
DELETE FROM `project_prestation`;

-- Listage de la structure de table weddingplanner_test. quotation
CREATE TABLE IF NOT EXISTS `quotation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `quotation_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_creation` date NOT NULL,
  `is_accepted` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_474A8DB9166D1F9C` (`project_id`),
  CONSTRAINT `FK_474A8DB9166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.quotation : ~0 rows (environ)
DELETE FROM `quotation`;

-- Listage de la structure de table weddingplanner_test. reservation
CREATE TABLE IF NOT EXISTS `reservation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `reference_order` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_order` datetime DEFAULT NULL,
  `total_price` double NOT NULL,
  `date_picking` date NOT NULL,
  `is_prepared` tinyint(1) DEFAULT NULL,
  `is_picked` tinyint(1) DEFAULT NULL,
  `firstname` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_42C84955A76ED395` (`user_id`),
  CONSTRAINT `FK_42C84955A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.reservation : ~0 rows (environ)
DELETE FROM `reservation`;

-- Listage de la structure de table weddingplanner_test. reset_password_request
CREATE TABLE IF NOT EXISTS `reset_password_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_7CE748AA76ED395` (`user_id`),
  CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.reset_password_request : ~0 rows (environ)
DELETE FROM `reset_password_request`;

-- Listage de la structure de table weddingplanner_test. state
CREATE TABLE IF NOT EXISTS `state` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.state : ~0 rows (environ)
DELETE FROM `state`;

-- Listage de la structure de table weddingplanner_test. testimony
CREATE TABLE IF NOT EXISTS `testimony` (
  `id` int NOT NULL AUTO_INCREMENT,
  `couple_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_published` tinyint(1) DEFAULT NULL,
  `date_receipt` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.testimony : ~0 rows (environ)
DELETE FROM `testimony`;

-- Listage de la structure de table weddingplanner_test. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(170) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pseudo` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `google_user` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.user : ~0 rows (environ)
DELETE FROM `user`;

-- Listage de la structure de table weddingplanner_test. worker
CREATE TABLE IF NOT EXISTS `worker` (
  `id` int NOT NULL AUTO_INCREMENT,
  `job_id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_picture` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9FB2BF62BE04EA9` (`job_id`),
  CONSTRAINT `FK_9FB2BF62BE04EA9` FOREIGN KEY (`job_id`) REFERENCES `job` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner_test.worker : ~0 rows (environ)
DELETE FROM `worker`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
