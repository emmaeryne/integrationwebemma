-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 14 avr. 2025 à 18:55
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `hive3`
--

-- --------------------------------------------------------

--
-- Structure de la table `abonnement`
--

DROP TABLE IF EXISTS `abonnement`;
CREATE TABLE IF NOT EXISTS `abonnement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `service_id` int NOT NULL,
  `type_abonnement_id` int NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `est_actif` tinyint(1) NOT NULL,
  `prix_total` decimal(10,2) NOT NULL,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_seances_restantes` int NOT NULL,
  `auto_renouvellement` tinyint(1) NOT NULL,
  `mode_paiement` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `est_gratuit` tinyint(1) NOT NULL,
  `duree_mois` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_351268BBED5CA9E6` (`service_id`),
  KEY `IDX_351268BB813AF326` (`type_abonnement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `abonnement`
--

INSERT INTO `abonnement` (`id`, `service_id`, `type_abonnement_id`, `date_debut`, `date_fin`, `est_actif`, `prix_total`, `statut`, `nombre_seances_restantes`, `auto_renouvellement`, `mode_paiement`, `est_gratuit`, `duree_mois`) VALUES
(1, 1, 5, '2025-03-23', '2025-07-23', 1, '300.00', 'SUSPENDU', 0, 0, 'Espèces', 0, 4),
(7, 12, 2, '2025-03-24', '2025-03-28', 1, '67.00', 'ACTIF', 0, 1, 'Carte bancaire', 0, NULL),
(9, 3, 5, '2025-04-19', '2025-07-19', 1, '345.00', 'ACTIF', 12, 0, 'Carte bancaire', 0, 3),
(10, 2, 46, '2025-04-17', '2025-04-30', 1, '378.00', 'INACTIF', 12, 1, 'Carte bancaire', 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `badge`
--

DROP TABLE IF EXISTS `badge`;
CREATE TABLE IF NOT EXISTS `badge` (
  `id` int NOT NULL AUTO_INCREMENT,
  `service_id` int NOT NULL,
  `date_attribution` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FEF0481DED5CA9E6` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cours`
--

DROP TABLE IF EXISTS `cours`;
CREATE TABLE IF NOT EXISTS `cours` (
  `id_cours` int NOT NULL AUTO_INCREMENT,
  `Nom_Cours` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Etat_Cours` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Duree` int NOT NULL,
  `id_user` int DEFAULT NULL,
  PRIMARY KEY (`id_cours`),
  KEY `IDX_FDCA8C9C6B3CA4B` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cours`
--

INSERT INTO `cours` (`id_cours`, `Nom_Cours`, `Etat_Cours`, `Duree`, `id_user`) VALUES
(11, 'zth', 'zth', 20, 2),
(12, 'ezgn', 'zth', 20, 2),
(13, 'tezzt', 'trhzr', 2, 2),
(20, 'dsQ', 'actif', 25, 2),
(21, 'hgfgf', 'jhg;jh', 25, 2),
(22, 'gggggg', 'actif', 20, 2);

-- --------------------------------------------------------

--
-- Structure de la table `cours_participant`
--

DROP TABLE IF EXISTS `cours_participant`;
CREATE TABLE IF NOT EXISTS `cours_participant` (
  `id` int NOT NULL,
  `id_participant` int DEFAULT NULL,
  `id_cours` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_95280B8FCF8DA6E6` (`id_participant`),
  KEY `IDX_95280B8F134FCDAC` (`id_cours`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250322150918', '2025-03-22 15:09:20', 194),
('DoctrineMigrations\\Version20250322153045', '2025-03-22 15:30:52', 215),
('DoctrineMigrations\\Version20250322170316', '2025-03-22 17:03:23', 84),
('DoctrineMigrations\\Version20250322193145', '2025-03-22 19:31:49', 203),
('DoctrineMigrations\\Version20250322225629', '2025-03-22 22:56:54', 110),
('DoctrineMigrations\\Version20250412162805', '2025-04-12 16:39:33', 65),
('DoctrineMigrations\\Version20250412165839', '2025-04-12 17:03:32', 2),
('DoctrineMigrations\\Version20250412170340', '2025-04-12 17:05:21', 3),
('DoctrineMigrations\\Version20250412170528', '2025-04-12 17:13:34', 12),
('DoctrineMigrations\\Version20250414090029', '2025-04-14 09:00:33', 128);

-- --------------------------------------------------------

--
-- Structure de la table `favoris`
--

DROP TABLE IF EXISTS `favoris`;
CREATE TABLE IF NOT EXISTS `favoris` (
  `id` int NOT NULL AUTO_INCREMENT,
  `service_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8933C432ED5CA9E6` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `messenger_messages`
--

INSERT INTO `messenger_messages` (`id`, `body`, `headers`, `queue_name`, `created_at`, `available_at`, `delivered_at`) VALUES
(1, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:28:\\\"Symfony\\\\Component\\\\Mime\\\\Email\\\":6:{i:0;s:16:\\\"badge_email_body\\\";i:1;s:5:\\\"utf-8\\\";i:2;N;i:3;N;i:4;a:1:{i:0;O:36:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\\":4:{s:11:\\\"\\0*\\0_headers\\\";O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:0:{}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}s:10:\\\"\\0*\\0_parent\\\";a:6:{s:4:\\\"body\\\";O:32:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\\":2:{s:38:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0path\\\";s:47:\\\"C:\\\\Users\\\\MSI\\\\AppData\\\\Local\\\\Temp\\\\bad187A.tmp.png\\\";s:42:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0filename\\\";N;}s:7:\\\"charset\\\";N;s:7:\\\"subtype\\\";s:3:\\\"png\\\";s:11:\\\"disposition\\\";s:10:\\\"attachment\\\";s:4:\\\"name\\\";s:9:\\\"badge.png\\\";s:8:\\\"encoding\\\";s:6:\\\"base64\\\";}s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0filename\\\";s:9:\\\"badge.png\\\";s:47:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0mediaType\\\";s:5:\\\"image\\\";}}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:22:\\\"your-email@example.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:24:\\\"client-email@example.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:13:\\\"badge_subject\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2025-03-24 18:26:14', '2025-03-24 18:26:14', NULL),
(2, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:28:\\\"Symfony\\\\Component\\\\Mime\\\\Email\\\":6:{i:0;s:16:\\\"badge_email_body\\\";i:1;s:5:\\\"utf-8\\\";i:2;N;i:3;N;i:4;a:1:{i:0;O:36:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\\":4:{s:11:\\\"\\0*\\0_headers\\\";O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:0:{}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}s:10:\\\"\\0*\\0_parent\\\";a:6:{s:4:\\\"body\\\";O:32:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\\":2:{s:38:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0path\\\";s:47:\\\"C:\\\\Users\\\\MSI\\\\AppData\\\\Local\\\\Temp\\\\badE428.tmp.png\\\";s:42:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0filename\\\";N;}s:7:\\\"charset\\\";N;s:7:\\\"subtype\\\";s:3:\\\"png\\\";s:11:\\\"disposition\\\";s:10:\\\"attachment\\\";s:4:\\\"name\\\";s:9:\\\"badge.png\\\";s:8:\\\"encoding\\\";s:6:\\\"base64\\\";}s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0filename\\\";s:9:\\\"badge.png\\\";s:47:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0mediaType\\\";s:5:\\\"image\\\";}}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:22:\\\"your-email@example.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:24:\\\"client-email@example.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:13:\\\"badge_subject\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2025-03-24 18:27:05', '2025-03-24 18:27:05', NULL),
(3, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:28:\\\"Symfony\\\\Component\\\\Mime\\\\Email\\\":6:{i:0;s:16:\\\"badge_email_body\\\";i:1;s:5:\\\"utf-8\\\";i:2;N;i:3;N;i:4;a:1:{i:0;O:36:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\\":4:{s:11:\\\"\\0*\\0_headers\\\";O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:0:{}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}s:10:\\\"\\0*\\0_parent\\\";a:6:{s:4:\\\"body\\\";O:32:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\\":2:{s:38:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0path\\\";s:47:\\\"C:\\\\Users\\\\MSI\\\\AppData\\\\Local\\\\Temp\\\\bad13AC.tmp.png\\\";s:42:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0filename\\\";N;}s:7:\\\"charset\\\";N;s:7:\\\"subtype\\\";s:3:\\\"png\\\";s:11:\\\"disposition\\\";s:10:\\\"attachment\\\";s:4:\\\"name\\\";s:9:\\\"badge.png\\\";s:8:\\\"encoding\\\";s:6:\\\"base64\\\";}s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0filename\\\";s:9:\\\"badge.png\\\";s:47:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0mediaType\\\";s:5:\\\"image\\\";}}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:22:\\\"your-email@example.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:24:\\\"client-email@example.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:13:\\\"badge_subject\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2025-03-24 18:43:40', '2025-03-24 18:43:40', NULL),
(4, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:28:\\\"Symfony\\\\Component\\\\Mime\\\\Email\\\":6:{i:0;s:16:\\\"badge_email_body\\\";i:1;s:5:\\\"utf-8\\\";i:2;N;i:3;N;i:4;a:1:{i:0;O:36:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\\":4:{s:11:\\\"\\0*\\0_headers\\\";O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:0:{}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}s:10:\\\"\\0*\\0_parent\\\";a:6:{s:4:\\\"body\\\";O:32:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\\":2:{s:38:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0path\\\";s:47:\\\"C:\\\\Users\\\\MSI\\\\AppData\\\\Local\\\\Temp\\\\bad1A5A.tmp.png\\\";s:42:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0filename\\\";N;}s:7:\\\"charset\\\";N;s:7:\\\"subtype\\\";s:3:\\\"png\\\";s:11:\\\"disposition\\\";s:10:\\\"attachment\\\";s:4:\\\"name\\\";s:9:\\\"badge.png\\\";s:8:\\\"encoding\\\";s:6:\\\"base64\\\";}s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0filename\\\";s:9:\\\"badge.png\\\";s:47:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0mediaType\\\";s:5:\\\"image\\\";}}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:19:\\\"noreply@example.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:24:\\\"client-email@example.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:13:\\\"badge_subject\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2025-03-24 18:52:26', '2025-03-24 18:52:26', NULL),
(5, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:28:\\\"Symfony\\\\Component\\\\Mime\\\\Email\\\":6:{i:0;s:16:\\\"badge_email_body\\\";i:1;s:5:\\\"utf-8\\\";i:2;N;i:3;N;i:4;a:1:{i:0;O:36:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\\":4:{s:11:\\\"\\0*\\0_headers\\\";O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:0:{}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}s:10:\\\"\\0*\\0_parent\\\";a:6:{s:4:\\\"body\\\";O:32:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\\":2:{s:38:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0path\\\";s:47:\\\"C:\\\\Users\\\\MSI\\\\AppData\\\\Local\\\\Temp\\\\badBBE6.tmp.png\\\";s:42:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0filename\\\";N;}s:7:\\\"charset\\\";N;s:7:\\\"subtype\\\";s:3:\\\"png\\\";s:11:\\\"disposition\\\";s:10:\\\"attachment\\\";s:4:\\\"name\\\";s:9:\\\"badge.png\\\";s:8:\\\"encoding\\\";s:6:\\\"base64\\\";}s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0filename\\\";s:9:\\\"badge.png\\\";s:47:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0mediaType\\\";s:5:\\\"image\\\";}}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:22:\\\"your-email@example.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:24:\\\"client-email@example.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:13:\\\"badge_subject\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2025-03-24 18:53:07', '2025-03-24 18:53:07', NULL),
(6, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:28:\\\"Symfony\\\\Component\\\\Mime\\\\Email\\\":6:{i:0;s:16:\\\"badge_email_body\\\";i:1;s:5:\\\"utf-8\\\";i:2;N;i:3;N;i:4;a:1:{i:0;O:36:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\\":4:{s:11:\\\"\\0*\\0_headers\\\";O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:0:{}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}s:10:\\\"\\0*\\0_parent\\\";a:6:{s:4:\\\"body\\\";O:32:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\\":2:{s:38:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0path\\\";s:46:\\\"C:\\\\Users\\\\MSI\\\\AppData\\\\Local\\\\Temp\\\\bad5F2.tmp.png\\\";s:42:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0filename\\\";N;}s:7:\\\"charset\\\";N;s:7:\\\"subtype\\\";s:3:\\\"png\\\";s:11:\\\"disposition\\\";s:10:\\\"attachment\\\";s:4:\\\"name\\\";s:9:\\\"badge.png\\\";s:8:\\\"encoding\\\";s:6:\\\"base64\\\";}s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0filename\\\";s:9:\\\"badge.png\\\";s:47:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0mediaType\\\";s:5:\\\"image\\\";}}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:22:\\\"your-email@example.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:24:\\\"client-email@example.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:13:\\\"badge_subject\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2025-03-24 18:53:26', '2025-03-24 18:53:26', NULL),
(7, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:28:\\\"Symfony\\\\Component\\\\Mime\\\\Email\\\":6:{i:0;s:16:\\\"badge_email_body\\\";i:1;s:5:\\\"utf-8\\\";i:2;N;i:3;N;i:4;a:1:{i:0;O:36:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\\":4:{s:11:\\\"\\0*\\0_headers\\\";O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:0:{}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}s:10:\\\"\\0*\\0_parent\\\";a:6:{s:4:\\\"body\\\";O:32:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\\":2:{s:38:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0path\\\";s:47:\\\"C:\\\\Users\\\\MSI\\\\AppData\\\\Local\\\\Temp\\\\badA4C7.tmp.png\\\";s:42:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0filename\\\";N;}s:7:\\\"charset\\\";N;s:7:\\\"subtype\\\";s:3:\\\"png\\\";s:11:\\\"disposition\\\";s:10:\\\"attachment\\\";s:4:\\\"name\\\";s:9:\\\"badge.png\\\";s:8:\\\"encoding\\\";s:6:\\\"base64\\\";}s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0filename\\\";s:9:\\\"badge.png\\\";s:47:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0mediaType\\\";s:5:\\\"image\\\";}}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:19:\\\"noreply@example.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:24:\\\"client-email@example.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:13:\\\"badge_subject\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2025-03-24 18:54:06', '2025-03-24 18:54:06', NULL),
(8, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:28:\\\"Symfony\\\\Component\\\\Mime\\\\Email\\\":6:{i:0;s:16:\\\"badge_email_body\\\";i:1;s:5:\\\"utf-8\\\";i:2;N;i:3;N;i:4;a:1:{i:0;O:36:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\\":4:{s:11:\\\"\\0*\\0_headers\\\";O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:0:{}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}s:10:\\\"\\0*\\0_parent\\\";a:6:{s:4:\\\"body\\\";O:32:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\\":2:{s:38:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0path\\\";s:47:\\\"C:\\\\Users\\\\MSI\\\\AppData\\\\Local\\\\Temp\\\\bad5E74.tmp.png\\\";s:42:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0filename\\\";N;}s:7:\\\"charset\\\";N;s:7:\\\"subtype\\\";s:3:\\\"png\\\";s:11:\\\"disposition\\\";s:10:\\\"attachment\\\";s:4:\\\"name\\\";s:9:\\\"badge.png\\\";s:8:\\\"encoding\\\";s:6:\\\"base64\\\";}s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0filename\\\";s:9:\\\"badge.png\\\";s:47:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0mediaType\\\";s:5:\\\"image\\\";}}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:25:\\\"emna.awini.work@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:25:\\\"emna.awini.work@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:13:\\\"badge_subject\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2025-03-24 18:59:16', '2025-03-24 18:59:16', NULL),
(9, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:28:\\\"Symfony\\\\Component\\\\Mime\\\\Email\\\":6:{i:0;s:16:\\\"badge_email_body\\\";i:1;s:5:\\\"utf-8\\\";i:2;N;i:3;N;i:4;a:1:{i:0;O:36:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\\":4:{s:11:\\\"\\0*\\0_headers\\\";O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:0:{}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}s:10:\\\"\\0*\\0_parent\\\";a:6:{s:4:\\\"body\\\";O:32:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\\":2:{s:38:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0path\\\";s:47:\\\"C:\\\\Users\\\\MSI\\\\AppData\\\\Local\\\\Temp\\\\bad6443.tmp.png\\\";s:42:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0filename\\\";N;}s:7:\\\"charset\\\";N;s:7:\\\"subtype\\\";s:3:\\\"png\\\";s:11:\\\"disposition\\\";s:10:\\\"attachment\\\";s:4:\\\"name\\\";s:9:\\\"badge.png\\\";s:8:\\\"encoding\\\";s:6:\\\"base64\\\";}s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0filename\\\";s:9:\\\"badge.png\\\";s:47:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0mediaType\\\";s:5:\\\"image\\\";}}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:25:\\\"emna.awini.work@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:25:\\\"emna.awini.work@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:13:\\\"badge_subject\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2025-03-24 19:09:07', '2025-03-24 19:09:07', NULL),
(10, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:28:\\\"Symfony\\\\Component\\\\Mime\\\\Email\\\":6:{i:0;s:16:\\\"badge_email_body\\\";i:1;s:5:\\\"utf-8\\\";i:2;N;i:3;N;i:4;a:1:{i:0;O:36:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\\":4:{s:11:\\\"\\0*\\0_headers\\\";O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:0:{}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}s:10:\\\"\\0*\\0_parent\\\";a:6:{s:4:\\\"body\\\";O:32:\\\"Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\\":2:{s:38:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0path\\\";s:47:\\\"C:\\\\Users\\\\MSI\\\\AppData\\\\Local\\\\Temp\\\\bad4A51.tmp.png\\\";s:42:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\File\\0filename\\\";N;}s:7:\\\"charset\\\";N;s:7:\\\"subtype\\\";s:3:\\\"png\\\";s:11:\\\"disposition\\\";s:10:\\\"attachment\\\";s:4:\\\"name\\\";s:9:\\\"badge.png\\\";s:8:\\\"encoding\\\";s:6:\\\"base64\\\";}s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0filename\\\";s:9:\\\"badge.png\\\";s:47:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Part\\\\DataPart\\0mediaType\\\";s:5:\\\"image\\\";}}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:25:\\\"emna.awini.work@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:25:\\\"emna.awini.work@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:13:\\\"badge_subject\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2025-03-24 19:16:39', '2025-03-24 19:16:39', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `participant`
--

DROP TABLE IF EXISTS `participant`;
CREATE TABLE IF NOT EXISTS `participant` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Age` int NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `num_telephone` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `id_user` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D79F6B116B3CA4B` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `participant`
--

INSERT INTO `participant` (`id`, `nom`, `prenom`, `Age`, `adresse`, `num_telephone`, `id_user`) VALUES
(3, 'kkkk', '', 0, '', '', 8);

-- --------------------------------------------------------

--
-- Structure de la table `planning`
--

DROP TABLE IF EXISTS `planning`;
CREATE TABLE IF NOT EXISTS `planning` (
  `id_planning` int NOT NULL AUTO_INCREMENT,
  `type_activite` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_planning` date NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `cours` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  PRIMARY KEY (`id_planning`),
  KEY `IDX_D499BFF6FDCA8C9C` (`cours`),
  KEY `IDX_D499BFF66B3CA4B` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `planning`
--

INSERT INTO `planning` (`id_planning`, `type_activite`, `date_planning`, `status`, `cours`, `id_user`) VALUES
(6, 'gggg', '2026-05-11', 'actif', 11, 2);

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_abonnement_id` int NOT NULL,
  `date_reservation` datetime NOT NULL,
  `date_debut` datetime DEFAULT NULL,
  `date_fin` datetime DEFAULT NULL,
  `statut` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_42C84955813AF326` (`type_abonnement_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`id`, `type_abonnement_id`, `date_reservation`, `date_debut`, `date_fin`, `statut`) VALUES
(5, 46, '2025-03-24 19:24:40', '2025-01-27 23:24:00', '2025-03-03 20:24:00', 'actif'),
(6, 5, '2025-03-24 20:05:08', '2025-02-05 21:05:00', '2025-03-01 21:04:00', 'actif'),
(7, 50, '2025-04-10 22:30:34', '2025-04-10 23:30:00', '2025-04-01 23:30:00', 'actif'),
(8, 47, '2025-04-10 22:31:01', '2025-04-10 23:30:00', '2025-04-29 23:30:00', 'terminé'),
(9, 73, '2025-04-10 22:48:23', '2025-06-18 23:48:00', '2025-07-18 23:48:00', 'actif');

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

DROP TABLE IF EXISTS `service`;
CREATE TABLE IF NOT EXISTS `service` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `prix` decimal(10,2) NOT NULL,
  `est_actif` tinyint(1) NOT NULL,
  `capacite_max` int NOT NULL,
  `categorie` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duree_minutes` int NOT NULL,
  `niveau` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `note` decimal(3,2) DEFAULT NULL,
  `nombre_reservations` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `service`
--

INSERT INTO `service` (`id`, `nom`, `description`, `prix`, `est_actif`, `capacite_max`, `categorie`, `duree_minutes`, `niveau`, `created_at`, `updated_at`, `note`, `nombre_reservations`, `image`) VALUES
(1, 'natation', 'Service de natation adapté au niveau Intermédiaire dans la catégorie sport. Profitez d\'une expérience unique !', '299.40', 1, 12, 'sportEX', 80, 2, '2025-03-22 17:12:21', '2025-03-22 17:12:21', NULL, 0, '67deef768cdac.png'),
(2, 'EMMA', 'EMMMMMMMMMMA', '280.00', 1, 34, 'NATATION', 46, 1, '2025-03-22 17:13:00', '2025-03-22 17:13:00', NULL, 0, '67deef9d29763.png'),
(3, 'lover', 'Service de lover adapté au niveau Débutant dans la catégorie MEN. Profitez d\'une expérience unique !', '455.00', 1, 66, 'MEN', 56, 1, '2025-03-22 17:32:58', '2025-03-22 17:32:58', '0.00', 0, '67df91317b73d.jpg'),
(4, 'AYCHA144', 'Service de AYCHA adapté au niveau Avancé dans la catégorie SPORTIF. Profitez d\'une expérience unique !', '250.50', 1, 25, 'SPORTIF', 48, 3, '2025-03-22 18:37:37', '2025-03-22 18:37:37', NULL, 0, '67df0371d0c43.png'),
(7, 'GOLF', 'Service de louli adapté au niveau Intermédiaire dans la catégorie spor. Profitez d\'une expérience unique !', '23.90', 1, 89, 'sporU', 56, 1, '2025-03-23 00:32:53', '2025-03-23 00:32:53', NULL, 0, '67df57270521e.png'),
(8, 'TTYU', 'Service de TTYU adapté au niveau Débutant dans la catégorie TT. Profitez d\'une expérience unique !', '55.50', 1, 55, 'TTHJ', 55, 1, '2025-03-23 00:36:57', '2025-03-23 00:36:57', NULL, 0, '67df5a291cb78.png'),
(9, 'KKKK', 'Service de KKKK adapté au niveau Débutant dans la catégorie DDTT. Profitez d\'une expérience unique !', '666.60', 1, 88, 'DDTT', 44, 1, '2025-03-23 00:37:24', '2025-03-23 00:37:24', NULL, 0, '67df57c5788bc.jpg'),
(10, 'DDDD', 'Service de DDDD adapté au niveau Intermédiaire dans la catégorie JJJ. Profitez d\'une expérience unique !', '99.70', 1, 45, 'SPORTIF', 99, 2, '2025-03-23 00:37:56', '2025-03-23 00:37:56', NULL, 0, '67df57e57ae47.png'),
(11, 'KKHH', 'Service de KKHH adapté au niveau Avancé dans la catégorie KJLKJ. Profitez d\'une expérience unique !', '989.90', 1, 99, 'SPORTIF', 67, 3, '2025-03-23 00:38:41', '2025-03-23 00:38:41', NULL, 0, '67df910b08fd3.jpg'),
(12, 'RTYUE', 'Service de RTYUE adapté au niveau Intermédiaire dans la catégorie JFJGF. Profitez d\'une expérience unique !', '12.20', 1, 22, 'JFJGF', 78, 2, '2025-03-23 00:39:18', '2025-03-23 00:39:18', NULL, 0, '67df583777fd4.png');

-- --------------------------------------------------------

--
-- Structure de la table `type_abonnement`
--

DROP TABLE IF EXISTS `type_abonnement`;
CREATE TABLE IF NOT EXISTS `type_abonnement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `prix` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duree_en_mois` int NOT NULL,
  `is_premium` tinyint(1) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `reduction` double DEFAULT NULL,
  `prix_reduit` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `type_abonnement`
--

INSERT INTO `type_abonnement` (`id`, `nom`, `description`, `prix`, `duree_en_mois`, `is_premium`, `updated_at`, `reduction`, `prix_reduit`) VALUES
(2, 'Abonnement Starter', 'Découvrez l\'Abonnement Starter pour seulement 5€/mois ! Profitez d\'une expérience premium complète pendant 1 mois, parfaite pour les utilisateurs exigeants.', '90.00', 1, 1, '2025-03-24 15:22:55', NULL, NULL),
(5, 'Abonnement Basic', 'Découvrez l\'Abonnement Basic pour seulement 8€/mois ! Profitez d\'une expérience premium complète pendant 3 mois, parfaite pour les utilisateurs exigeants.', '8.00', 3, 1, '2025-03-22 23:07:04', NULL, NULL),
(46, 'Abonnement Light', 'Abonnement Abonnement Light à 6 €, de type premium complète, pour une durée de 2 mois, destiné à les utilisateurs exigeants.', '6', 2, 1, NULL, NULL, NULL),
(47, 'Abonnement Ultimate', 'Abonnement Abonnement Ultimate à 30 €, de type premium complète, pour une durée de 12 mois, destiné à les utilisateurs exigeants.', '30', 12, 1, NULL, NULL, NULL),
(49, 'RITA', 'hhhl', '666', 23, 1, NULL, 40, '399.60'),
(50, 'esprittn', 'Abonnement esprittn à 455 €, de type premium complète, pour une durée de 34 mois, destiné à les utilisateurs exigeants.', '455', 34, 1, NULL, 20, '364.00'),
(52, 'NomRR', 'Abonnement NomRR à Découvrez l\'Nom pour seulement Prix€/mois ! Profitez d\'une expérience premium complète pendant 0 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 56 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 56, 1, NULL, NULL, NULL),
(53, 'Abonnement Starter', 'Abonnement Abonnement Starter à Découvrez l\'Abonnement Starter pour seulement 5€/mois ! Profitez d\'une expérience premium complète pendant 1 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 5 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 5, 1, NULL, NULL, NULL),
(55, 'Abonnement Basic', 'Abonnement Abonnement Basic à Découvrez l\'Abonnement Basic pour seulement 8€/mois ! Profitez d\'une expérience premium complète pendant 3 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 8 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 8, 1, NULL, NULL, NULL),
(58, 'Abonnement Premium', 'Abonnement Abonnement Premium à Découvrez l\'Abonnement Premium pour seulement 18€/mois ! Profitez d\'une expérience premium complète pendant 6 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 18 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 18, 1, NULL, NULL, NULL),
(59, 'Abonnement Light', 'Abonnement Abonnement Light à Découvrez l\'Abonnement Light pour seulement 6€/mois ! Profitez d\'une expérience premium complète pendant 2 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 6 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 6, 1, NULL, NULL, NULL),
(60, 'Abonnement Ultimate', 'Abonnement Abonnement Ultimate à Découvrez l\'Abonnement Ultimate pour seulement 30€/mois ! Profitez d\'une expérience premium complète pendant 12 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 30 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 30, 1, NULL, NULL, NULL),
(62, 'Abonnement Gold (Copie)', 'Abonnement Abonnement Gold (Copie) à Découvrez l\'Abonnement Gold pour seulement 20€/mois ! Profitez d\'une expérience premium complète pendant 9 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 20 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 20, 1, NULL, NULL, NULL),
(63, 'NNN', 'Abonnement NNN à type_abonnement.description.default €, de type premium complète, pour une durée de 890 mois, destiné à les utilisateurs exigeants.', 'type_abonn', 890, 1, NULL, NULL, NULL),
(64, 'NomRR', 'Abonnement NomRR à Découvrez l\'Nom pour seulement Prix€/mois ! Profitez d\'une expérience premium complète pendant 0 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 56 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 56, 1, NULL, NULL, NULL),
(65, 'Abonnement Starter', 'Abonnement Abonnement Starter à Découvrez l\'Abonnement Starter pour seulement 5€/mois ! Profitez d\'une expérience premium complète pendant 1 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 5 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 5, 1, NULL, NULL, NULL),
(66, 'Abonnement Elite', 'Abonnement Abonnement Elite à Découvrez l\'Abonnement Elite pour seulement 25€/mois ! Profitez d\'une expérience premium complète pendant 12 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 25 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 25, 1, NULL, NULL, NULL),
(67, 'Abonnement Basic', 'Abonnement Abonnement Basic à Découvrez l\'Abonnement Basic pour seulement 8€/mois ! Profitez d\'une expérience premium complète pendant 3 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 8 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 8, 1, NULL, NULL, NULL),
(68, 'Abonnement Silver', 'Abonnement Abonnement Silver à Découvrez l\'Abonnement Silver pour seulement 12€/mois ! Profitez d\'une expérience premium complète pendant 4 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 12 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 12, 1, NULL, NULL, NULL),
(69, 'Abonnement Gold', 'Abonnement Abonnement Gold à Découvrez l\'Abonnement Gold pour seulement 20€/mois ! Profitez d\'une expérience premium complète pendant 9 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 20 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 20, 1, NULL, NULL, NULL),
(70, 'Abonnement Premium', 'Abonnement Abonnement Premium à Découvrez l\'Abonnement Premium pour seulement 18€/mois ! Profitez d\'une expérience premium complète pendant 6 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 18 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 18, 1, NULL, NULL, NULL),
(71, 'Abonnement Light', 'Abonnement Abonnement Light à Découvrez l\'Abonnement Light pour seulement 6€/mois ! Profitez d\'une expérience premium complète pendant 2 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 6 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 6, 1, NULL, NULL, NULL),
(72, 'Abonnement Ultimate', 'Abonnement Abonnement Ultimate à Découvrez l\'Abonnement Ultimate pour seulement 30€/mois ! Profitez d\'une expérience premium complète pendant 12 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 30 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 30, 1, NULL, NULL, NULL),
(73, 'Abonnement Flex', 'Abonnement Abonnement Flex à Découvrez l\'Abonnement Flex pour seulement 10€/mois ! Profitez d\'une expérience premium complète pendant 3 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 10 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 10, 1, NULL, NULL, NULL),
(74, 'Abonnement Gold (Copie)', 'Abonnement Abonnement Gold (Copie) à Découvrez l\'Abonnement Gold pour seulement 20€/mois ! Profitez d\'une expérience premium complète pendant 9 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 20 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 20, 1, NULL, NULL, NULL),
(75, 'NNN', 'Abonnement NNN à type_abonnement.description.default €, de type premium complète, pour une durée de 890 mois, destiné à les utilisateurs exigeants.', 'type_abonn', 890, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `is_active`, `role`) VALUES
(2, 'Motaz', 'motaz.selmi@gmail.com', 'hashed_password', 1, 'COACH'),
(4, 'nmj', 'jkqrg@sght', 'sthst', 1, 'ADMIN'),
(8, 'kkkk', 'arga@etztez', 'trhrz', 1, 'USER');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `abonnement`
--
ALTER TABLE `abonnement`
  ADD CONSTRAINT `FK_351268BB813AF326` FOREIGN KEY (`type_abonnement_id`) REFERENCES `type_abonnement` (`id`),
  ADD CONSTRAINT `FK_351268BBED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`);

--
-- Contraintes pour la table `badge`
--
ALTER TABLE `badge`
  ADD CONSTRAINT `FK_FEF0481DED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`);

--
-- Contraintes pour la table `cours`
--
ALTER TABLE `cours`
  ADD CONSTRAINT `FK_FDCA8C9C6B3CA4B` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `cours_participant`
--
ALTER TABLE `cours_participant`
  ADD CONSTRAINT `cours_participant_ibfk_1` FOREIGN KEY (`id_participant`) REFERENCES `participant` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cours_participant_ibfk_2` FOREIGN KEY (`id_cours`) REFERENCES `cours` (`id_cours`) ON DELETE CASCADE;

--
-- Contraintes pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD CONSTRAINT `FK_8933C432ED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`);

--
-- Contraintes pour la table `participant`
--
ALTER TABLE `participant`
  ADD CONSTRAINT `FK_D79F6B116B3CA4B` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `planning`
--
ALTER TABLE `planning`
  ADD CONSTRAINT `FK_D499BFF66B3CA4B` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_D499BFF6FDCA8C9C` FOREIGN KEY (`cours`) REFERENCES `cours` (`id_cours`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
