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
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.batch : ~4 rows (environ)
INSERT IGNORE INTO `batch` (`id`, `title`, `description`) VALUES
	(1, 'Les indispensables', 'Découvrez notre collection "Les indispensables" dédiée à tous les essentiels pour un mariage parfait. Des invitations élégantes aux décorations raffinées, en passant par les accessoires de cérémonie, chaque produit a été soigneusement sélectionné pour ajouter une touche de magie à votre grand jour. Préparez-vous à vivre un moment inoubliable avec des articles qui répondent à tous vos besoins et bien plus encore.'),
	(2, 'Cadeaux pour les mariés', 'Offrez un souvenir mémorable avec notre collection "Cadeaux pour les mariés". Cette sélection unique comprend des articles personnalisés, des souvenirs intemporels et des objets de luxe qui sauront toucher le cœur des nouveaux mariés. Que ce soit pour remercier vos hôtes ou célébrer votre amour, trouvez le cadeau parfait qui restera gravé dans les mémoires.'),
	(3, 'Produits éco-responsable', 'Pour un mariage respectueux de l\'environnement, explorez notre collection "Produits éco-responsables". Chaque article est conçu avec des matériaux durables et des pratiques éthiques, sans compromettre le style ou l\'élégance. Des décorations biodégradables aux cadeaux écologiques, faites le choix d\'un mariage vert et élégant avec des produits qui protègent notre planète.'),
	(4, 'Box-cadeau', 'Surprenez vos proches avec notre collection "Box-cadeau", des coffrets soigneusement préparés pour offrir des moments de bonheur et de découverte. Chaque box est remplie de produits sélectionnés avec soin, allant des douceurs gourmandes aux accessoires de bien-être, parfaits pour choyer les mariés ou remercier les invités. Faites plaisir à ceux que vous aimez avec une box-cadeau personnalisée et pleine de surprises.');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.booking : ~3 rows (environ)
INSERT IGNORE INTO `booking` (`id`, `product_id`, `reservation_id`, `quantite`) VALUES
	(3, 1, 6, 16),
	(4, 1, 7, 2),
	(5, 3, 7, 1);

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
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pictures` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_57EE857412469DE2` (`category_id`),
  CONSTRAINT `FK_57EE857412469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.creation : ~3 rows (environ)
INSERT IGNORE INTO `creation` (`id`, `category_id`, `title`, `description`, `pictures`) VALUES
	(1, 1, 'Mariage de luxe', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Est sunt, maiores ex magnam incidunt reiciendis explicabo vero laudantium, officia quae itaque laborum fugit rerum dicta quasi ', '{"pictures": [{"alt": "Mariage2", "url": "img/creation/mariage/pexels-emma-bauso-1183828-2253842.jpg"}, {"alt": "Mariage de luxe", "url": "img/creation/mariage/pexels-emma-bauso-1183828-2253842.jpg"}]}'),
	(2, 2, 'Fleurs', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Est sunt, maiores ex magnam incidunt reiciendis explicabo vero laudantium, officia quae itaque laborum fugit rerum dicta quasi ', '{"alt": "Fleurs", "url": "img/creation/mariage/pexels-emma-bauso-1183828-2253842.jpg"}'),
	(3, 2, 'Objets', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Est sunt, maiores ex magnam incidunt reiciendis explicabo vero laudantium, officia quae itaque laborum fugit rerum dicta quasi ', '{"alt": "Objet de décoration", "url": "img/creation/mariage/pexels-emma-bauso-1183828-2253842.jpg"}');

-- Listage de la structure de table weddingplanner. destination
CREATE TABLE IF NOT EXISTS `destination` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.destination : ~4 rows (environ)
INSERT IGNORE INTO `destination` (`id`, `name`) VALUES
	(1, 'Strasbourg'),
	(2, 'Paris'),
	(3, 'Barcelone'),
	(4, 'Autre');

-- Listage de la structure de table weddingplanner. job
CREATE TABLE IF NOT EXISTS `job` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.job : ~4 rows (environ)
INSERT IGNORE INTO `job` (`id`, `title`) VALUES
	(1, 'Organisatrice'),
	(2, 'Chargée de communication'),
	(3, 'Wedding planner'),
	(4, 'Conseiller clientèle');

-- Listage de la structure de table weddingplanner. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.messenger_messages : ~3 rows (environ)
INSERT IGNORE INTO `messenger_messages` (`id`, `body`, `headers`, `queue_name`, `created_at`, `available_at`, `delivered_at`) VALUES
	(1, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":5:{i:0;s:41:\\"registration/confirmation_email.html.twig\\";i:1;N;i:2;a:3:{s:9:\\"signedUrl\\";s:167:\\"http://127.0.0.1:8000/verify/email?expires=1716796386&signature=CWfgR%2BPGWIgcGQmQ0kvVyd2d9iHrGNFrsetuS63U3pg%3D&token=hdNJBQobB6LAa3u9BMknydUNsYOIWhQzq795%2FetNAHQ%3D\\";s:19:\\"expiresAtMessageKey\\";s:26:\\"%count% hour|%count% hours\\";s:20:\\"expiresAtMessageData\\";a:1:{s:7:\\"%count%\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:36:\\"laure.schaeffer@ceremonie-couture.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:21:\\"Ceremonie Couture Bot\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:14:\\"person@test.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Please Confirm your Email\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}i:4;N;}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2024-05-27 06:53:06', '2024-05-27 06:53:06', NULL),
	(2, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":5:{i:0;s:41:\\"registration/confirmation_email.html.twig\\";i:1;N;i:2;a:3:{s:9:\\"signedUrl\\";s:165:\\"http://127.0.0.1:8000/verify/email?expires=1716890979&signature=N4044HsjLTq452YHehUnkB2gmpzrmhUPV4RoMtVIl%2FA%3D&token=TueVXFDuj0uK5oTdFbwPXO9NKvUCYNFdXBD2mMNLxwI%3D\\";s:19:\\"expiresAtMessageKey\\";s:26:\\"%count% hour|%count% hours\\";s:20:\\"expiresAtMessageData\\";a:1:{s:7:\\"%count%\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:36:\\"laure.schaeffer@ceremonie-couture.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:21:\\"Ceremonie Couture Bot\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:15:\\"test@exemple.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Please Confirm your Email\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}i:4;N;}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2024-05-28 09:09:39', '2024-05-28 09:09:39', NULL),
	(3, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":5:{i:0;s:41:\\"registration/confirmation_email.html.twig\\";i:1;N;i:2;a:3:{s:9:\\"signedUrl\\";s:169:\\"http://127.0.0.1:8000/verify/email?expires=1716891102&signature=vV84ZdtU3LWpeTN%2FsJm%2Fi0sq13sCvXMJvZjsLEHJWvE%3D&token=iovTp1cFYz%2F06qguBvWdkQmIw3xIL2jA8YOZlia2Aug%3D\\";s:19:\\"expiresAtMessageKey\\";s:26:\\"%count% hour|%count% hours\\";s:20:\\"expiresAtMessageData\\";a:1:{s:7:\\"%count%\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:36:\\"laure.schaeffer@ceremonie-couture.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:21:\\"Ceremonie Couture Bot\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:18:\\"person2@exemple.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Please Confirm your Email\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}i:4;N;}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2024-05-28 09:11:42', '2024-05-28 09:11:42', NULL);

-- Listage de la structure de table weddingplanner. prestation
CREATE TABLE IF NOT EXISTS `prestation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_picture` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.prestation : ~3 rows (environ)
INSERT IGNORE INTO `prestation` (`id`, `title`, `subtitle`, `description`, `url_picture`, `alt_picture`, `price`) VALUES
	(1, 'Emeraude expérience', 'sous-titre', 'Laissez-nous prendre en charge chaque détail de votre mariage avec notre service d\'organisation complète. De la conception initiale à l\'exécution finale, nous nous occupons de tout : gestion du budget, sélection des fournisseurs, planification de la cérémonie et de la réception, coordination le jour J, et bien plus encore. Vous pourrez vous détendre et profiter de chaque instant, sachant que votre mariage est entre les mains d\'experts passionnés.', 'img/creation/mariage/pexels-punchbrandstock-1244627.jpg', 'formule emeraude expérience', 50000),
	(2, 'Coordination du jour J', 'sous-titre', 'Pour les couples qui ont déjà tout planifié mais qui souhaitent une assistance professionnelle le jour de leur mariage, notre service de coordination du jour J est idéal. Nous nous assurerons que tout se déroule sans accroc, en gérant la logistique et en supervisant les fournisseurs. Vous pourrez vous concentrer sur vos moments de bonheur et laisser notre équipe s\'occuper du reste.', 'img/stocksnap_A7S8U2WAMN.jpg', 'formule coordination du jour J', 10000),
	(3, 'Un mariage intime', 'sous-titre', 'Pour ceux qui souhaitent une célébration plus petite et intime, notre service d\'organisation de mariages intimes est parfait. Nous nous spécialisons dans la création de mariages chaleureux et personnels, en mettant l\'accent sur des expériences significatives pour vous et vos proches. Que ce soit une cérémonie en petit comité dans un jardin ou un dîner élégant dans un lieu exclusif, nous ferons en sorte que chaque détail soit parfait.', 'img/pexels-trungnguyenphotog-2959192.jpg', 'formule un mariage intime', 20000);

-- Listage de la structure de table weddingplanner. product
CREATE TABLE IF NOT EXISTS `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `batch_id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` float NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_picture` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D34A04ADF39EBE7A` (`batch_id`),
  CONSTRAINT `FK_D34A04ADF39EBE7A` FOREIGN KEY (`batch_id`) REFERENCES `batch` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.product : ~3 rows (environ)
INSERT IGNORE INTO `product` (`id`, `batch_id`, `name`, `price`, `description`, `url_picture`, `alt_picture`) VALUES
	(1, 1, 'Lettre pour carton d\'invitation', 20, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Est sunt, maiores ex magnam incidunt reiciendis explicabo vero laudantium, officia quae itaque laborum fugit rerum dicta quasi asperiores sapiente tenetur necessitatibus', 'img/commerce/invitation_card_with_a_ribbon.jpg', 'lettre pour carton d\'invitation'),
	(2, 4, 'Box soin ', 50, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Est sunt, maiores ex magnam incidunt reiciendis explicabo vero laudantium, officia quae itaque laborum fugit rerum dicta quasi asperiores sapiente tenetur necessitatibus', 'img/commerce/pexels-tima-miroshnichenko-7879833.jpg', 'box cadeau, soin pour les mariés'),
	(3, 2, 'Crème pour les mains', 15, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Est sunt, maiores ex magnam incidunt reiciendis explicabo vero laudantium, officia quae itaque laborum fugit rerum dicta quasi asperiores sapiente tenetur necessitatibus', 'img/commerce/pexels-karolina-grabowska-4465121.jpg', 'crème pour les mains');

-- Listage de la structure de table weddingplanner. project
CREATE TABLE IF NOT EXISTS `project` (
  `id` int NOT NULL AUTO_INCREMENT,
  `destination_id` int DEFAULT NULL,
  `budget_id` int DEFAULT NULL,
  `firstname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_event` datetime NOT NULL,
  `nb_guest` int NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_receipt` datetime DEFAULT NULL,
  `is_contacted` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2FB3D0EE816C6140` (`destination_id`),
  KEY `IDX_2FB3D0EE36ABA6B8` (`budget_id`),
  CONSTRAINT `FK_2FB3D0EE36ABA6B8` FOREIGN KEY (`budget_id`) REFERENCES `budget` (`id`),
  CONSTRAINT `FK_2FB3D0EE816C6140` FOREIGN KEY (`destination_id`) REFERENCES `destination` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.project : ~0 rows (environ)
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

-- Listage des données de la table weddingplanner.project_prestation : ~0 rows (environ)
INSERT IGNORE INTO `project_prestation` (`project_id`, `prestation_id`) VALUES
	(1, 1);

-- Listage de la structure de table weddingplanner. reservation
CREATE TABLE IF NOT EXISTS `reservation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `reference_order` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_order` datetime DEFAULT NULL,
  `total_price` double NOT NULL,
  `date_picking` date NOT NULL,
  `is_prepared` tinyint(1) DEFAULT NULL,
  `is_picked` tinyint(1) DEFAULT NULL,
  `firstname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_42C84955A76ED395` (`user_id`),
  CONSTRAINT `FK_42C84955A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.reservation : ~2 rows (environ)
INSERT IGNORE INTO `reservation` (`id`, `user_id`, `reference_order`, `date_order`, `total_price`, `date_picking`, `is_prepared`, `is_picked`, `firstname`, `surname`, `telephone`) VALUES
	(6, 4, '665741a090ed6', '2024-05-29 14:54:24', 320, '2025-01-15', 0, 0, 'Person', 'Name', '0611223344'),
	(7, 4, '66588c9902b5d', '2024-05-30 14:26:33', 55, '2024-05-31', 1, 0, 'Person', 'Name', '0611223344');

-- Listage de la structure de table weddingplanner. testimony
CREATE TABLE IF NOT EXISTS `testimony` (
  `id` int NOT NULL AUTO_INCREMENT,
  `couple_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_published` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.testimony : ~0 rows (environ)
INSERT IGNORE INTO `testimony` (`id`, `couple_name`, `description`, `is_published`) VALUES
	(1, 'Mr & Mme Nom', 'Nous ne pouvons que chanter les louanges de Cérémonie Couture pour avoir fait de notre mariage un jour absolument parfait. Dès notre première rencontre avec Clara, notre wedding planner, nous avons su que nous étions entre de bonnes mains. Son professionnalisme, sa créativité et son attention aux moindres détails nous ont tout de suite mis en confiance. Clara a su écouter nos souhaits et nos préoccupations, tout en nous guidant avec des suggestions brillantes qui ont vraiment transformé notre vision en réalité. Chaque aspect du mariage, de la décoration florale aux choix musicaux, a été orchestré avec une telle précision que nous n\'avions rien à faire d\'autre que de profiter de notre journée spéciale. Le jour J, tout s\'est déroulé sans accroc. Clara et son équipe ont géré la logistique avec une telle fluidité que nous avons pu nous concentrer entièrement sur nos moments de bonheur, entourés de nos proches. Nous avons reçu d\'innombrables compliments de la part de nos invités sur la beauté et l\'organisation impeccable de notre mariage. Nous sommes immensément reconnaissants à Clara et à toute l\'équipe de Cérémonie Couture pour avoir créé des souvenirs que nous chérirons pour toujours. Nous recommandons vivement leurs services à tous ceux qui souhaitent un mariage parfait et sans stress. Merci infiniment pour tout !', 1),
	(2, 'Mr & Mme Nomdefamille', 'lorem ipsum', 0);

-- Listage de la structure de table weddingplanner. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pseudo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.user : ~4 rows (environ)
INSERT IGNORE INTO `user` (`id`, `email`, `pseudo`, `roles`, `password`, `is_verified`) VALUES
	(1, 'laure@exemple.fr', 'Laure', '[]', '.', 0),
	(2, 'person@test.fr', 'Person', '["ROLE_ADMIN"]', '$2y$13$g4WWLnAUJ1MkCfGH1vETU.ct00JFSxbrSkFb7sRvY.npMGTfhAboW', 0),
	(3, 'test@exemple.fr', 'Test', '[]', '$2y$13$hVqd4r/XZDXckZpXR7HICuRx57OI5/5D4nBT6NczRkFCm6IobmZWm', 0),
	(4, 'person2@exemple.fr', 'Person2', '["ROLE_USER", "ROLE_ACHETEUR"]', '$2y$13$A16IcbMl8dQ5caZGlGfK1eiciuQlkPBfsI62PBmCJh4hbdTTSrPa.', 0);

-- Listage de la structure de table weddingplanner. worker
CREATE TABLE IF NOT EXISTS `worker` (
  `id` int NOT NULL AUTO_INCREMENT,
  `job_id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_picture` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9FB2BF62BE04EA9` (`job_id`),
  CONSTRAINT `FK_9FB2BF62BE04EA9` FOREIGN KEY (`job_id`) REFERENCES `job` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.worker : ~4 rows (environ)
INSERT IGNORE INTO `worker` (`id`, `job_id`, `name`, `description`, `url_picture`, `alt_picture`) VALUES
	(1, 1, 'Jeanne', 'Directrice de Création, passionnée par l\'univers du mariage et l\'esthétique. Avec plus de 10 ans d\'expérience dans le design et la planification d\'événements, elle apporte une touche unique et sophistiquée à chacune de nos collections. Jeanne est diplômée de l\'École des Beaux-Arts et a travaillé avec des marques prestigieuses avant de rejoindre notre équipe. Son talent et son souci du détail font d\'elle une force créative incontournable pour notre entreprise.', 'img/equipe/equipe1.jpg', 'photo de notre organisatrice'),
	(2, 2, 'Sophie', 'Sophie est chargée de la gestion de notre présence en ligne et de l\'interaction avec notre communauté. Diplômée en communication digitale et avec 5 ans d\'expérience dans les réseaux sociaux, Sophie crée du contenu engageant et gère nos campagnes publicitaires avec brio. Elle est passionnée par les relations humaines et adore partager l\'enthousiasme de nos clients pour nos produits.', 'img/equipe/pexels-vinicius-wiesehofer-289347-1130626.jpg', 'photo de notre chargée de communication'),
	(3, 3, 'Clara', 'Clara est dédiée à transformer les rêves de mariage en réalité. Avec plus de 12 ans d\'expérience dans l\'organisation de mariages, Clara a orchestré plus de 200 célébrations uniques, chacune imprégnée de sa passion pour le détail et de son amour pour l\'excellence.\r\nElle a obtenu son diplôme en gestion d\'événements à l\'Institut Supérieur de Gestion à Paris, où elle a développé ses compétences en planification et coordination. Dotée d\'un œil artistique et d\'une grande capacité d\'écoute, elle excelle à comprendre les désirs et les besoins de ses clients. Elle est experte en design de mariage, gestion de budget, coordination avec les fournisseurs, et gestion de la logistique, garantissant que chaque aspect du jour J se déroule sans accroc. Son réseau étendu de contacts dans l\'industrie lui permet de recommander les meilleurs prestataires, des photographes talentueux aux fleuristes créatifs, assurant ainsi une qualité supérieure pour chaque événement.', 'img/equipe/connor-wilkins-u68jZr7ky0I-unsplash.jpg', 'photo de notre wedding planner'),
	(4, 4, 'Florian', 'Lucas est notre Conseiller Clientèle dévoué, toujours prêt à aider nos clients à trouver les articles parfaits pour leur mariage. Fort de 7 ans d\'expérience dans le service client, Lucas excelle dans l\'écoute et la résolution des problèmes. Il est diplômé en relations publiques et s\'assure que chaque client bénéficie d\'une expérience d\'achat exceptionnelle et personnalisée. Sa gentillesse et son professionnalisme sont grandement appréciés par tous ceux qui font appel à lui.', 'img/equipe/vince-fleming-j3lf-Jn6deo-unsplash.jpg', 'photo de notre conseiller clientèle');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
