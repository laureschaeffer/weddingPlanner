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


-- Listage de la structure de la base pour weddingplanner
CREATE DATABASE IF NOT EXISTS `weddingplanner` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `weddingplanner`;

-- Listage de la structure de table weddingplanner. batch
CREATE TABLE IF NOT EXISTS `batch` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.batch : ~1 rows (environ)
INSERT IGNORE INTO `batch` (`id`, `title`, `description`) VALUES
	(1, 'Les indispensables', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Est sunt, maiores ex magnam incidunt reiciendis explicabo vero laudantium, officia quae itaque laborum fugit rerum dicta quasi asperiores sapiente tenetur necessitatibus\n\n');

-- Listage de la structure de table weddingplanner. booking
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

-- Listage des données de la table weddingplanner.booking : ~0 rows (environ)

-- Listage de la structure de table weddingplanner. budget
CREATE TABLE IF NOT EXISTS `budget` (
  `id` int NOT NULL AUTO_INCREMENT,
  `min_price` int NOT NULL,
  `max_price` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.budget : ~4 rows (environ)
INSERT IGNORE INTO `budget` (`id`, `min_price`, `max_price`) VALUES
	(1, 0, 15000),
	(2, 15000, 30000),
	(3, 30000, 50000),
	(4, 50000, 100000);

-- Listage de la structure de table weddingplanner. category
CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.category : ~2 rows (environ)
INSERT IGNORE INTO `category` (`id`, `title`) VALUES
	(1, 'Mariage'),
	(2, 'Décoration');

-- Listage de la structure de table weddingplanner. creation
CREATE TABLE IF NOT EXISTS `creation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `picture` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_57EE857412469DE2` (`category_id`),
  CONSTRAINT `FK_57EE857412469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.creation : ~1 rows (environ)
INSERT IGNORE INTO `creation` (`id`, `category_id`, `title`, `description`, `picture`) VALUES
	(1, 1, 'Mariage de luxe', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Est sunt, maiores ex magnam incidunt reiciendis explicabo vero laudantium, officia quae itaque laborum fugit rerum dicta quasi ', '{"alt": "Mariage de luxe", "url": "public/img/creation/mariage/pexels-emma-bauso-1183828-2253842.jpg"}');

-- Listage de la structure de table weddingplanner. destination
CREATE TABLE IF NOT EXISTS `destination` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.destination : ~4 rows (environ)
INSERT IGNORE INTO `destination` (`id`, `name`) VALUES
	(1, 'Strasbourg'),
	(2, 'Paris'),
	(3, 'Barcelone'),
	(4, 'Autre');

-- Listage de la structure de table weddingplanner. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table weddingplanner.doctrine_migration_versions : ~1 rows (environ)
INSERT IGNORE INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20240522131347', '2024-05-22 13:14:06', 842);

-- Listage de la structure de table weddingplanner. job
CREATE TABLE IF NOT EXISTS `job` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.job : ~1 rows (environ)
INSERT IGNORE INTO `job` (`id`, `title`) VALUES
	(1, 'Organisatrice');

-- Listage de la structure de table weddingplanner. messenger_messages
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

-- Listage des données de la table weddingplanner.messenger_messages : ~0 rows (environ)

-- Listage de la structure de table weddingplanner. prestation
CREATE TABLE IF NOT EXISTS `prestation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_picture` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.prestation : ~1 rows (environ)
INSERT IGNORE INTO `prestation` (`id`, `title`, `subtitle`, `description`, `url_picture`, `alt_picture`, `price`) VALUES
	(1, 'Emeraude expérience', 'sous-titre', 'lorem', 'public/img/creation/mariage/pexels-punchbrandstock-1244627.jpg', 'formule emeraude expérience', 50000);

-- Listage de la structure de table weddingplanner. product
CREATE TABLE IF NOT EXISTS `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `batch_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_picture` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D34A04ADF39EBE7A` (`batch_id`),
  CONSTRAINT `FK_D34A04ADF39EBE7A` FOREIGN KEY (`batch_id`) REFERENCES `batch` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.product : ~1 rows (environ)
INSERT IGNORE INTO `product` (`id`, `batch_id`, `name`, `description`, `url_picture`, `alt_picture`) VALUES
	(1, 1, 'Lettre pour carton d\'invitation', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Est sunt, maiores ex magnam incidunt reiciendis explicabo vero laudantium, officia quae itaque laborum fugit rerum dicta quasi asperiores sapiente tenetur necessitatibus\n\n', 'public/img/commerce/invitation_card_with_a_ribbon.jpg', 'lettre pour carton d\'invitation');

-- Listage de la structure de table weddingplanner. project
CREATE TABLE IF NOT EXISTS `project` (
  `id` int NOT NULL AUTO_INCREMENT,
  `destination_id` int DEFAULT NULL,
  `budget_id` int DEFAULT NULL,
  `firstname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_event` datetime NOT NULL,
  `nb_guest` int NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_receipt` datetime DEFAULT CURRENT_TIMESTAMP,
  `is_contacted` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2FB3D0EE816C6140` (`destination_id`),
  KEY `IDX_2FB3D0EE36ABA6B8` (`budget_id`),
  CONSTRAINT `FK_2FB3D0EE36ABA6B8` FOREIGN KEY (`budget_id`) REFERENCES `budget` (`id`),
  CONSTRAINT `FK_2FB3D0EE816C6140` FOREIGN KEY (`destination_id`) REFERENCES `destination` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.project : ~1 rows (environ)
INSERT IGNORE INTO `project` (`id`, `destination_id`, `budget_id`, `firstname`, `surname`, `email`, `telephone`, `date_event`, `nb_guest`, `description`, `date_receipt`, `is_contacted`) VALUES
	(1, 1, 1, 'Laure', 'Nom', 'laure@exemple.fr', '0611223344', '2026-05-22 15:58:43', 100, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Est sunt, maiores ex magnam incidunt reiciendis explicabo vero laudantium, officia quae itaque laborum fugit rerum dicta quasi ', '2024-05-22 15:59:20', 0);

-- Listage de la structure de table weddingplanner. project_prestation
CREATE TABLE IF NOT EXISTS `project_prestation` (
  `project_id` int NOT NULL,
  `prestation_id` int NOT NULL,
  PRIMARY KEY (`project_id`,`prestation_id`),
  KEY `IDX_496EF49F166D1F9C` (`project_id`),
  KEY `IDX_496EF49F9E45C554` (`prestation_id`),
  CONSTRAINT `FK_496EF49F166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_496EF49F9E45C554` FOREIGN KEY (`prestation_id`) REFERENCES `prestation` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.project_prestation : ~1 rows (environ)
INSERT IGNORE INTO `project_prestation` (`project_id`, `prestation_id`) VALUES
	(1, 1);

-- Listage de la structure de table weddingplanner. reservation
CREATE TABLE IF NOT EXISTS `reservation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `reference_order` int DEFAULT NULL,
  `date_order` datetime DEFAULT CURRENT_TIMESTAMP,
  `total_price` double NOT NULL,
  `date_picking` date NOT NULL,
  `is_prepared` tinyint(1) DEFAULT NULL,
  `is_picked` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_42C84955A76ED395` (`user_id`),
  CONSTRAINT `FK_42C84955A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.reservation : ~1 rows (environ)
INSERT IGNORE INTO `reservation` (`id`, `user_id`, `reference_order`, `date_order`, `total_price`, `date_picking`, `is_prepared`, `is_picked`) VALUES
	(1, 1, 1234, '2024-05-22 16:20:36', 20.55, '2024-05-25', 0, 0);

-- Listage de la structure de table weddingplanner. testimony
CREATE TABLE IF NOT EXISTS `testimony` (
  `id` int NOT NULL AUTO_INCREMENT,
  `couple_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_published` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.testimony : ~1 rows (environ)
INSERT IGNORE INTO `testimony` (`id`, `couple_name`, `description`, `is_published`) VALUES
	(1, 'Mr & Mme Nom', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Est sunt, maiores ex magnam incidunt reiciendis explicabo vero laudantium, officia quae itaque laborum fugit rerum dicta quasi ', 0);

-- Listage de la structure de table weddingplanner. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.user : ~1 rows (environ)
INSERT IGNORE INTO `user` (`id`, `email`, `roles`, `password`, `is_verified`) VALUES
	(1, 'laure@exemple.fr', '[]', '.', 0);

-- Listage de la structure de table weddingplanner. worker
CREATE TABLE IF NOT EXISTS `worker` (
  `id` int NOT NULL AUTO_INCREMENT,
  `job_id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_picture` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9FB2BF62BE04EA9` (`job_id`),
  CONSTRAINT `FK_9FB2BF62BE04EA9` FOREIGN KEY (`job_id`) REFERENCES `job` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.worker : ~1 rows (environ)
INSERT IGNORE INTO `worker` (`id`, `job_id`, `name`, `description`, `picture`, `alt_picture`) VALUES
	(1, 1, 'Jeanne', 'lorem', 'public/img/equipe/equipe1.jpg', 'photo de notre organisatrice');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
