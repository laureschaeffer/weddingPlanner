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

-- Listage de la structure de table weddingplanner. appointment
CREATE TABLE IF NOT EXISTS `appointment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime DEFAULT NULL,
  `title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FE38F844A76ED395` (`user_id`),
  CONSTRAINT `FK_FE38F844A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.appointment : ~12 rows (environ)
DELETE FROM `appointment`;
INSERT INTO `appointment` (`id`, `user_id`, `date_start`, `date_end`, `title`) VALUES
	(1, 4, '2024-09-24 15:00:00', '2024-09-24 16:00:00', 'conversation projet'),
	(3, 2, '2024-09-13 10:00:00', '2024-09-13 12:30:00', 'nouvel evenement'),
	(11, 2, '2024-09-13 14:00:00', '2024-09-13 15:00:00', 'contact'),
	(14, 1, '2024-09-10 15:00:00', '2024-09-10 16:00:00', 'nouvel evenement'),
	(15, 1, '2024-09-12 12:30:00', '2024-09-12 13:30:00', 'nouvel evenement'),
	(19, 2, '2024-09-12 08:30:00', '2024-09-12 10:00:00', 'test'),
	(20, 2, '2024-09-12 11:00:00', '2024-09-12 12:00:00', 'test direct'),
	(23, 2, '2024-10-11 11:00:00', '2024-09-14 12:00:00', 'confirmation projet'),
	(27, 1, '2024-10-10 10:30:00', '2024-09-28 13:00:00', 'discussion prestataires'),
	(28, 1, '2024-10-09 09:30:00', '2024-09-27 11:00:00', 'confirmation projet'),
	(29, 1, '2024-10-10 16:00:00', '2024-10-10 17:30:00', 'établissement code couleur'),
	(33, 2, '2024-10-11 12:30:00', '2024-09-27 13:30:00', 'conversation projet');

-- Listage de la structure de table weddingplanner. batch
CREATE TABLE IF NOT EXISTS `batch` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.batch : ~4 rows (environ)
DELETE FROM `batch`;
INSERT INTO `batch` (`id`, `title`, `description`) VALUES
	(1, 'Les indispensables', 'Découvrez notre collection "Les indispensables" dédiée à tous les essentiels pour un mariage parfait. Des invitations élégantes aux décorations raffinées, en passant par les accessoires de cérémonie, chaque produit a été soigneusement sélectionné pour ajouter une touche de magie à votre grand jour. Préparez-vous à vivre un moment inoubliable avec des articles qui répondent à tous vos besoins et bien plus encore.'),
	(2, 'Cadeaux pour les mariés', 'Offrez un souvenir mémorable avec notre collection "Cadeaux pour les mariés". Cette sélection unique comprend des articles personnalisés, des souvenirs intemporels et des objets de luxe qui sauront toucher le cœur des nouveaux mariés. Que ce soit pour remercier vos hôtes ou célébrer votre amour, trouvez le cadeau parfait qui restera gravé dans les mémoires.'),
	(3, 'Produits éco-responsable', 'Pour un mariage respectueux de l\'environnement, explorez notre collection "Produits éco-responsables". Chaque article est conçu avec des matériaux durables et des pratiques éthiques, sans compromettre le style ou l\'élégance. Des décorations biodégradables aux cadeaux écologiques, faites le choix d\'un mariage vert et élégant avec des produits qui protègent notre planète.'),
	(4, 'Box-cadeau', 'Surprenez vos proches avec notre collection "Box-cadeau", des coffrets soigneusement préparés pour offrir des moments de bonheur et de découverte. Chaque box est remplie de produits sélectionnés avec soin, allant des douceurs gourmandes aux accessoires de bien-être, parfaits pour choyer les mariés ou remercier les invités. Faites plaisir à ceux que vous aimez avec une box-cadeau personnalisée et pleine de surprises.');

-- Listage de la structure de table weddingplanner. bill
CREATE TABLE IF NOT EXISTS `bill` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quotation_id` int NOT NULL,
  `bill_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_creation` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_7A2119E3B4EA4E60` (`quotation_id`),
  CONSTRAINT `FK_7A2119E3B4EA4E60` FOREIGN KEY (`quotation_id`) REFERENCES `quotation` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.bill : ~2 rows (environ)
DELETE FROM `bill`;
INSERT INTO `bill` (`id`, `quotation_id`, `bill_number`, `date_creation`) VALUES
	(1, 2, 'FACT_172355934655', '2024-08-13'),
	(3, 4, 'FACT_172406086148', '2024-08-19');

-- Listage de la structure de table weddingplanner. booking
CREATE TABLE IF NOT EXISTS `booking` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `reservation_id` int NOT NULL,
  `quantite` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E00CEDDE4584665A` (`product_id`),
  KEY `IDX_E00CEDDEB83297E7` (`reservation_id`),
  CONSTRAINT `FK_E00CEDDE4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_E00CEDDEB83297E7` FOREIGN KEY (`reservation_id`) REFERENCES `reservation` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.booking : ~7 rows (environ)
DELETE FROM `booking`;
INSERT INTO `booking` (`id`, `product_id`, `reservation_id`, `quantite`) VALUES
	(18, 2, 20, 1),
	(19, 3, 19, 1),
	(20, 2, 22, 2),
	(22, 1, 19, 2),
	(23, 2, 19, 1),
	(24, 3, 24, 1),
	(25, 2, 24, 2),
	(26, 1, 25, 2);

-- Listage de la structure de table weddingplanner. budget
CREATE TABLE IF NOT EXISTS `budget` (
  `id` int NOT NULL AUTO_INCREMENT,
  `min_price` int NOT NULL,
  `max_price` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.budget : ~4 rows (environ)
DELETE FROM `budget`;
INSERT INTO `budget` (`id`, `min_price`, `max_price`) VALUES
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
DELETE FROM `category`;
INSERT INTO `category` (`id`, `title`) VALUES
	(1, 'Mariage'),
	(2, 'Décoration');

-- Listage de la structure de table weddingplanner. comment
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `user_id` int NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_post` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9474526C166D1F9C` (`project_id`),
  KEY `IDX_9474526CA76ED395` (`user_id`),
  CONSTRAINT `FK_9474526C166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.comment : ~10 rows (environ)
DELETE FROM `comment`;
INSERT INTO `comment` (`id`, `project_id`, `user_id`, `content`, `date_post`) VALUES
	(1, 1, 2, 'Inspiration: film année 80', '2024-06-05 12:16:36'),
	(2, 1, 2, 'Budget abordé: plutôt 10 000€ maximum!', '2024-06-05 12:17:03'),
	(4, 1, 3, 'test', '2024-06-06 12:46:21'),
	(5, 1, 2, 'test pour Kévin', '2024-07-29 11:41:10'),
	(6, 3, 2, 'En lien avec', '2024-07-31 13:50:51'),
	(8, 4, 7, 'Commentaire ajouté en bdd', '2024-08-19 11:36:36'),
	(9, 8, 2, 'personne à contacter le 20/09!', '2024-09-17 08:51:24'),
	(10, 8, 2, 'nouveau commentaire', '2024-09-17 15:37:52');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.creation : ~4 rows (environ)
DELETE FROM `creation`;
INSERT INTO `creation` (`id`, `category_id`, `title`, `description`, `pictures`) VALUES
	(1, 1, 'Mariage de luxe', 'Nos mariages de luxe incarnent l\'élégance et le raffinement. Chaque détail est soigneusement orchestré pour créer une expérience somptueuse et inoubliable, alliant des lieux d\'exception, des décorations exquises et un service impeccable.', '[{"alt": "Mariage de luxe1", "url": "img/creation/mariage/pexels-emma-bauso-1183828-2253842.webp"}, {"alt": "Mariage de luxe2", "url": "img/creation/mariage/pexels-punchbrandstock-1244627.webp"}]'),
	(2, 2, 'Fleurs', 'Nos compositions florales subliment chaque mariage. De bouquets délicats aux installations grandioses, nous utilisons les plus belles fleurs de saison pour créer des arrangements sur mesure qui reflètent la personnalité des mariés et l\'ambiance désirée.', '[{"alt": "Fleurs de table", "url": "img/creation/decoration/flowers-7948554_1280.webp"}, {"alt": "Fleurs de décoration", "url": "img/creation/decoration/pexels-panditwiguna-2970287.webp"}, {"alt": "Bouquets", "url": "img/creation/decoration/pexels-trungnguyenphotog-2959192.webp"}, {"alt": "Bouquets 2", "url": "img/creation/decoration/pexels-zaher-ataba-997482-1940583.webp"}]'),
	(3, 2, 'Objets', 'Notre collection d\'objets décoratifs ajoute une touche unique à chaque célébration. Des centres de table aux accessoires personnalisés, chaque pièce est choisie avec soin pour enrichir l\'atmosphère et créer des souvenirs précieux.', '[{"alt": "Coussin pour alliance", "url": "img/creation/decoration/pexels-fidel-2814808.webp"}, {"alt": "Rubans dorés", "url": "img/creation/decoration/pixabay_ribbon-552166_960_720.webp"}]'),
	(4, 1, 'Mariage vintage', 'Nos mariages intimes célèbrent l\'amour dans sa forme la plus pure et personnelle. Nous créons des moments chaleureux et authentiques pour les couples souhaitant partager leur union avec leurs proches dans un cadre plus restreint. Chaque détail est pensé pour refléter l\'histoire unique des mariés, offrant une expérience profondément émouvante et mémorable dans une atmosphère empreinte de convivialité et d\'élégance discrète.', '[{"alt": "Mariage vintage 1", "url": "img/creation/mariage/bride-8182890_1280.webp"}, {"alt": "Mariage vintage 2", "url": "img/creation/mariage/pexels-panditwiguna-2788488.webp"}, {"alt": "Mariage vintage 3", "url": "img/creation/mariage/vintage-van-4288994_1280.webp"}]');

-- Listage de la structure de table weddingplanner. destination
CREATE TABLE IF NOT EXISTS `destination` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.destination : ~4 rows (environ)
DELETE FROM `destination`;
INSERT INTO `destination` (`id`, `name`) VALUES
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
DELETE FROM `job`;
INSERT INTO `job` (`id`, `title`) VALUES
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
DELETE FROM `messenger_messages`;
INSERT INTO `messenger_messages` (`id`, `body`, `headers`, `queue_name`, `created_at`, `available_at`, `delivered_at`) VALUES
	(1, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":5:{i:0;s:41:\\"registration/confirmation_email.html.twig\\";i:1;N;i:2;a:3:{s:9:\\"signedUrl\\";s:167:\\"http://127.0.0.1:8000/verify/email?expires=1716796386&signature=CWfgR%2BPGWIgcGQmQ0kvVyd2d9iHrGNFrsetuS63U3pg%3D&token=hdNJBQobB6LAa3u9BMknydUNsYOIWhQzq795%2FetNAHQ%3D\\";s:19:\\"expiresAtMessageKey\\";s:26:\\"%count% hour|%count% hours\\";s:20:\\"expiresAtMessageData\\";a:1:{s:7:\\"%count%\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:36:\\"laure.schaeffer@ceremonie-couture.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:21:\\"Ceremonie Couture Bot\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:14:\\"person@test.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Please Confirm your Email\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}i:4;N;}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2024-05-27 06:53:06', '2024-05-27 06:53:06', NULL),
	(2, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":5:{i:0;s:41:\\"registration/confirmation_email.html.twig\\";i:1;N;i:2;a:3:{s:9:\\"signedUrl\\";s:165:\\"http://127.0.0.1:8000/verify/email?expires=1716890979&signature=N4044HsjLTq452YHehUnkB2gmpzrmhUPV4RoMtVIl%2FA%3D&token=TueVXFDuj0uK5oTdFbwPXO9NKvUCYNFdXBD2mMNLxwI%3D\\";s:19:\\"expiresAtMessageKey\\";s:26:\\"%count% hour|%count% hours\\";s:20:\\"expiresAtMessageData\\";a:1:{s:7:\\"%count%\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:36:\\"laure.schaeffer@ceremonie-couture.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:21:\\"Ceremonie Couture Bot\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:15:\\"test@exemple.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Please Confirm your Email\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}i:4;N;}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2024-05-28 09:09:39', '2024-05-28 09:09:39', NULL),
	(3, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":5:{i:0;s:41:\\"registration/confirmation_email.html.twig\\";i:1;N;i:2;a:3:{s:9:\\"signedUrl\\";s:169:\\"http://127.0.0.1:8000/verify/email?expires=1716891102&signature=vV84ZdtU3LWpeTN%2FsJm%2Fi0sq13sCvXMJvZjsLEHJWvE%3D&token=iovTp1cFYz%2F06qguBvWdkQmIw3xIL2jA8YOZlia2Aug%3D\\";s:19:\\"expiresAtMessageKey\\";s:26:\\"%count% hour|%count% hours\\";s:20:\\"expiresAtMessageData\\";a:1:{s:7:\\"%count%\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:36:\\"laure.schaeffer@ceremonie-couture.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:21:\\"Ceremonie Couture Bot\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:18:\\"person2@exemple.fr\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Please Confirm your Email\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}i:4;N;}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2024-05-28 09:11:42', '2024-05-28 09:11:42', NULL);

-- Listage de la structure de table weddingplanner. note
CREATE TABLE IF NOT EXISTS `note` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `user_id` int NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_post` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CFBDFA14A76ED395` (`user_id`),
  KEY `IDX_CFBDFA14166D1F9C` (`project_id`),
  CONSTRAINT `FK_CFBDFA14166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_CFBDFA14A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.note : ~3 rows (environ)
DELETE FROM `note`;
INSERT INTO `note` (`id`, `project_id`, `user_id`, `content`, `date_post`) VALUES
	(1, 8, 2, 'nouvelle note', '2024-09-17 15:36:18');

-- Listage de la structure de table weddingplanner. prestation
CREATE TABLE IF NOT EXISTS `prestation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_picture` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.prestation : ~3 rows (environ)
DELETE FROM `prestation`;
INSERT INTO `prestation` (`id`, `title`, `description`, `url_picture`, `alt_picture`, `price`) VALUES
	(1, 'Emeraude expérience', 'Laissez-nous prendre en charge chaque détail de votre mariage avec notre service d\'organisation complète. De la conception initiale à l\'exécution finale, nous nous occupons de tout : gestion du budget, sélection des fournisseurs, planification de la cérémonie et de la réception, coordination le jour J, et bien plus encore. Vous pourrez vous détendre et profiter de chaque instant, sachant que votre mariage est entre les mains d\'experts passionnés.', 'img/prestation/pexels-solliefoto-313707.webp', 'formule emeraude expérience', 50000),
	(2, 'Coordination du jour J', 'Pour les couples qui ont déjà tout planifié mais qui souhaitent une assistance professionnelle le jour de leur mariage, notre service de coordination du jour J est idéal. Nous nous assurerons que tout se déroule sans accroc, en gérant la logistique et en supervisant les fournisseurs. Vous pourrez vous concentrer sur vos moments de bonheur et laisser notre équipe s\'occuper du reste.', 'img/prestation/pexels-jeremy-wong-382920-1082024.webp', 'formule coordination du jour J', 2000),
	(3, 'Un mariage intime', 'Pour ceux qui souhaitent une célébration plus petite et intime, notre service d\'organisation de mariages intimes est parfait. Nous nous spécialisons dans la création de mariages chaleureux et personnels, en mettant l\'accent sur des expériences significatives pour vous et vos proches. Que ce soit une cérémonie en petit comité dans un jardin ou un dîner élégant dans un lieu exclusif, nous ferons en sorte que chaque détail soit parfait.', 'img/prestation/pexels-photo-1161372.webp', 'formule un mariage intime', 5000);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.product : ~4 rows (environ)
DELETE FROM `product`;
INSERT INTO `product` (`id`, `batch_id`, `name`, `price`, `description`, `url_picture`, `alt_picture`) VALUES
	(1, 1, 'Lettre pour carton d\'invitation', 20, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Est sunt, maiores ex magnam incidunt reiciendis explicabo vero laudantium, officia quae itaque laborum fugit rerum dicta quasi asperiores sapiente tenetur necessitatibus', 'img/commerce/invitation_card_with_a_ribbon.jpg', 'lettre pour carton d\'invitation'),
	(2, 4, 'Box soin ', 50, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Est sunt, maiores ex magnam incidunt reiciendis explicabo vero laudantium, officia quae itaque laborum fugit rerum dicta quasi asperiores sapiente tenetur necessitatibus', 'img/commerce/pexels-tima-miroshnichenko-7879833.jpg', 'box cadeau, soin pour les mariés'),
	(3, 2, 'Crème pour les mains', 15, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Est sunt, maiores ex magnam incidunt reiciendis explicabo vero laudantium, officia quae itaque laborum fugit rerum dicta quasi asperiores sapiente tenetur necessitatibus', 'img/commerce/pexels-karolina-grabowska-4465121.jpg', 'crème pour les mains'),
	(4, 1, 'Marque place', 10, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Est sunt, maiores ex magnam incidunt reiciendis explicabo vero laudantium, officia quae itaque laborum fugit rerum dicta quasi asperiores sapiente tenetur necessitatibus', 'img/commerce/invitation_card_with_a_ribbon.jpg', 'marque place');

-- Listage de la structure de table weddingplanner. project
CREATE TABLE IF NOT EXISTS `project` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `destination_id` int DEFAULT NULL,
  `budget_id` int DEFAULT NULL,
  `state_id` int DEFAULT NULL,
  `firstname` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(170) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_event` datetime NOT NULL,
  `nb_guest` int NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.project : ~8 rows (environ)
DELETE FROM `project`;
INSERT INTO `project` (`id`, `user_id`, `destination_id`, `budget_id`, `state_id`, `firstname`, `surname`, `email`, `telephone`, `date_event`, `nb_guest`, `description`, `date_receipt`, `is_contacted`, `final_price`) VALUES
	(1, NULL, 1, 1, 4, '9c04bf3942bfa9aaea6bfdfa35e3798e138dabed065e41388237f277', '394d61e52f9d85d456d245c974dd8fa9dd494c485140f45862686934', '42834e1ca1bf978b8d2ffa346c8ecdcbac6566b43dc4cd5672205d8d', '0e3f86e3e69988b18983f88c524e614004dc92c45cf5b0173c88a594', '2026-05-22 15:58:43', 100, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Est sunt, maiores ex magnam incidunt reiciendis explicabo vero laudantium, officia quae itaque laborum fugit rerum dicta quasi ', '2024-05-22 15:59:20', 1, 7000),
	(3, 7, 2, 3, 3, 'Maria', 'Nomdef', NULL, '0611223344', '2025-07-30 10:00:00', 200, 'projet', '2024-07-31 11:23:53', 1, 40000),
	(4, 7, 1, 4, 3, 'Dylan', 'JRC', NULL, '0611223344', '2025-09-14 11:00:00', 1000, 'J\'aimerai discuter de mon projet', '2024-08-13 16:52:05', 1, 75000),
	(5, 7, 2, 3, 3, 'Maria', 'Nomdef', NULL, '0611223344', '2025-07-30 10:00:00', 200, 'projet', '2024-01-30 11:23:53', 1, 40000),
	(6, 7, 1, 4, 3, 'Dylan', 'JRC', NULL, '0611223344', '2025-09-14 11:00:00', 1000, 'J\'aimerai discuter de mon projet', '2024-02-13 16:52:05', 1, 75000),
	(7, 4, 1, 4, 3, 'Dylan', 'JRC', NULL, '0611223344', '2025-09-14 11:00:00', 1000, 'J\'aimerai discuter de mon projet', '2024-02-13 16:52:05', 1, 15000),
	(8, 2, 1, 1, 2, 'Cynthia', 'Xxx', NULL, '0611223344', '2024-09-11 11:00:00', 10, 'J\'aimerai discuter de mon projet', '2024-09-10 14:14:51', 0, 5000),
	(9, 7, 4, 1, 1, 'Léa', 'Muller', NULL, '0611223344', '2024-12-09 09:14:39', 200, 'Bonjour, j\'aimerai discuter de mon projet en décembre', '2024-10-09 09:14:48', 1, 200);

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

-- Listage des données de la table weddingplanner.project_prestation : ~6 rows (environ)
DELETE FROM `project_prestation`;
INSERT INTO `project_prestation` (`project_id`, `prestation_id`) VALUES
	(1, 1),
	(3, 1),
	(4, 1),
	(8, 2);

-- Listage de la structure de table weddingplanner. quotation
CREATE TABLE IF NOT EXISTS `quotation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `quotation_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_creation` date NOT NULL,
  `is_accepted` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_474A8DB9166D1F9C` (`project_id`),
  CONSTRAINT `FK_474A8DB9166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.quotation : ~4 rows (environ)
DELETE FROM `quotation`;
INSERT INTO `quotation` (`id`, `project_id`, `quotation_number`, `date_creation`, `is_accepted`) VALUES
	(2, 3, '10', '2024-08-02', 1),
	(4, 4, 'DEV_172406008210', '2024-05-19', 1),
	(7, 8, 'DEV_172830560696', '2024-10-07', NULL);

-- Listage de la structure de table weddingplanner. reservation
CREATE TABLE IF NOT EXISTS `reservation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `reference_order` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_order` datetime DEFAULT NULL,
  `total_price` double NOT NULL,
  `is_prepared` tinyint(1) DEFAULT NULL,
  `is_picked` tinyint(1) DEFAULT NULL,
  `firstname` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_42C84955A76ED395` (`user_id`),
  CONSTRAINT `FK_42C84955A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.reservation : ~5 rows (environ)
DELETE FROM `reservation`;
INSERT INTO `reservation` (`id`, `user_id`, `reference_order`, `date_order`, `total_price`, `is_prepared`, `is_picked`, `firstname`, `surname`, `telephone`) VALUES
	(19, 3, '66616880b4d72', '2024-06-06 07:42:56', 50, 1, 0, 'Laure', 'Nomdefamille', '0611223344'),
	(20, 3, '6661689eec632', '2024-06-06 07:43:26', 50, 0, 0, 'Laure', 'Xxx', '0611223344'),
	(22, 1, '172467680532', '2024-08-26 12:53:25', 100, 0, 0, 'Thibault', 'Jaeger', '0611223344'),
	(24, 2, '172657952320', '2024-09-17 13:25:22', 15, 1, 1, 'Didier', 'Muller', '0611223344'),
	(25, 3, '172854631018', '2024-10-10 07:45:10', 40, 0, 0, 'Laure', 'Sch', '0611223344');

-- Listage de la structure de table weddingplanner. reset_password_request
CREATE TABLE IF NOT EXISTS `reset_password_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `selector` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_7CE748AA76ED395` (`user_id`),
  CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.reset_password_request : ~0 rows (environ)
DELETE FROM `reset_password_request`;

-- Listage de la structure de table weddingplanner. state
CREATE TABLE IF NOT EXISTS `state` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.state : ~4 rows (environ)
DELETE FROM `state`;
INSERT INTO `state` (`id`, `name`) VALUES
	(1, 'En cours'),
	(2, 'En attente'),
	(3, 'Accepté'),
	(4, 'Refusé');

-- Listage de la structure de table weddingplanner. testimony
CREATE TABLE IF NOT EXISTS `testimony` (
  `id` int NOT NULL AUTO_INCREMENT,
  `couple_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_published` tinyint(1) DEFAULT NULL,
  `date_receipt` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.testimony : ~4 rows (environ)
DELETE FROM `testimony`;
INSERT INTO `testimony` (`id`, `couple_name`, `description`, `is_published`, `date_receipt`) VALUES
	(1, 'Mr & Mme Nom', 'Nous ne pouvons que chanter les louanges de Cérémonie Couture pour avoir fait de notre mariage un jour absolument parfait. Dès notre première rencontre avec Clara, notre wedding planner, nous avons su que nous étions entre de bonnes mains. Son professionnalisme, sa créativité et son attention aux moindres détails nous ont tout de suite mis en confiance. Clara a su écouter nos souhaits et nos préoccupations, tout en nous guidant avec des suggestions brillantes qui ont vraiment transformé notre vision en réalité. Chaque aspect du mariage, de la décoration florale aux choix musicaux, a été orchestré avec une telle précision que nous n\'avions rien à faire d\'autre que de profiter de notre journée spéciale. Nous recommandons vivement leurs services à tous ceux qui souhaitent un mariage parfait et sans stress. Merci infiniment pour tout !', 1, '2024-05-20'),
	(2, 'Mr & Mme Nomdefamille', 'lorem ipsum', 0, '2024-06-15'),
	(3, 'mr et mme', 'lorem ipsum', 0, '2024-07-15'),
	(4, 'Laure et mr', 'nouveau témoignage', 0, '2024-07-31');

-- Listage de la structure de table weddingplanner. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(170) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pseudo` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `google_user` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table weddingplanner.user : ~6 rows (environ)
DELETE FROM `user`;
INSERT INTO `user` (`id`, `email`, `pseudo`, `roles`, `password`, `is_verified`, `google_user`) VALUES
	(1, 'laure@exemple.fr', 'Laure', '[]', '.', 0, 0),
	(2, 'person@test.fr', 'Person', '["ROLE_ADMIN", "ROLE_SUPERADMIN"]', '$2y$13$g4WWLnAUJ1MkCfGH1vETU.ct00JFSxbrSkFb7sRvY.npMGTfhAboW', 0, 0),
	(3, 'test@exemple.fr', 'Test', '["ROLE_ADMIN", "ROLE_SUPERADMIN"]', '$2y$13$hVqd4r/XZDXckZpXR7HICuRx57OI5/5D4nBT6NczRkFCm6IobmZWm', 0, 0),
	(4, 'e5adc1c4d19f1e7508f1a7fa484575e1ffc6a0aa4428af', 'e5adc1c4d19f1e7508f1a7fa484575e1ffc6a0aa4428af9c03e2be48', '["ROLE_SUPPRIME"]', '$2y$13$A16IcbMl8dQ5caZGlGfK1eiciuQlkPBfsI62PBmCJh4hbdTTSrPa.', 0, 0),
	(7, 'laure.15013@gmail.com', 'laure.15013', '["ROLE_ADMIN"]', 'b1a8919eece6d1c4228b50f72834ca74', 1, 1),
	(8, '35e3798e138dabed065e41388237f277', '9c04bf3942bfa9aaea6bfdfa35e3798e138dabed065e41388237f277', '["ROLE_SUPPRIME"]', '$2y$13$B/mKoDcxur5eeDCSN5nRXu3qajQmcEfPb5c7/fpiGMKgP1OoVT0EK', 0, 0),
	(9, 'cynthia@exemple.fr', 'Cynthia', '[]', '$2y$13$BufM7BMP5c03m/umk/HRwua/KSm4p5SkHoU1WMO.sMK7psmGIbrHi', 1, 0);

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
DELETE FROM `worker`;
INSERT INTO `worker` (`id`, `job_id`, `name`, `description`, `url_picture`, `alt_picture`) VALUES
	(1, 1, 'Jeanne', 'Directrice de Création, passionnée par l\'univers du mariage et l\'esthétique. Avec plus de 10 ans d\'expérience dans le design et la planification d\'événements, elle apporte une touche unique et sophistiquée à chacune de nos collections. Jeanne est diplômée de l\'École des Beaux-Arts et a travaillé avec des marques prestigieuses avant de rejoindre notre équipe. Son talent et son souci du détail font d\'elle une force créative incontournable pour notre entreprise.', 'img/equipe/equipe1.jpg', 'photo de notre organisatrice'),
	(2, 2, 'Sophie', 'Sophie est chargée de la gestion de notre présence en ligne et de l\'interaction avec notre communauté. Diplômée en communication digitale et avec 5 ans d\'expérience dans les réseaux sociaux, Sophie crée du contenu engageant et gère nos campagnes publicitaires avec brio. Elle est passionnée par les relations humaines et adore partager l\'enthousiasme de nos clients pour nos produits.', 'img/equipe/pexels-vinicius-wiesehofer-289347-1130626.jpg', 'photo de notre chargée de communication'),
	(3, 3, 'Clara', 'Clara est dédiée à transformer les rêves de mariage en réalité. Avec plus de 12 ans d\'expérience dans l\'organisation de mariages, Clara a orchestré plus de 200 célébrations uniques, chacune imprégnée de sa passion pour le détail et de son amour pour l\'excellence.\r\nElle a obtenu son diplôme en gestion d\'événements à l\'Institut Supérieur de Gestion à Paris, où elle a développé ses compétences en planification et coordination. Dotée d\'un œil artistique et d\'une grande capacité d\'écoute, elle excelle à comprendre les désirs et les besoins de ses clients. Elle est experte en design de mariage, gestion de budget, coordination avec les fournisseurs, et gestion de la logistique, garantissant que chaque aspect du jour J se déroule sans accroc. Son réseau étendu de contacts dans l\'industrie lui permet de recommander les meilleurs prestataires, des photographes talentueux aux fleuristes créatifs, assurant ainsi une qualité supérieure pour chaque événement.', 'img/equipe/connor-wilkins-u68jZr7ky0I-unsplash.jpg', 'photo de notre wedding planner'),
	(4, 4, 'Florian', 'Lucas est notre Conseiller Clientèle dévoué, toujours prêt à aider nos clients à trouver les articles parfaits pour leur mariage. Fort de 7 ans d\'expérience dans le service client, Lucas excelle dans l\'écoute et la résolution des problèmes. Il est diplômé en relations publiques et s\'assure que chaque client bénéficie d\'une expérience d\'achat exceptionnelle et personnalisée. Sa gentillesse et son professionnalisme sont grandement appréciés par tous ceux qui font appel à lui.', 'img/equipe/vince-fleming-j3lf-Jn6deo-unsplash.jpg', 'photo de notre conseiller clientèle');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
