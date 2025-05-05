-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2025 at 03:17 PM
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
-- Database: `hive3`
--

-- --------------------------------------------------------

--
-- Table structure for table `abonnement`
--

CREATE TABLE `abonnement` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `type_abonnement_id` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `est_actif` tinyint(1) NOT NULL,
  `prix_total` decimal(10,2) NOT NULL,
  `statut` varchar(255) NOT NULL,
  `nombre_seances_restantes` int(11) NOT NULL,
  `auto_renouvellement` tinyint(1) NOT NULL,
  `mode_paiement` varchar(50) DEFAULT NULL,
  `est_gratuit` tinyint(1) NOT NULL,
  `duree_mois` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `abonnement`
--

INSERT INTO `abonnement` (`id`, `service_id`, `type_abonnement_id`, `date_debut`, `date_fin`, `est_actif`, `prix_total`, `statut`, `nombre_seances_restantes`, `auto_renouvellement`, `mode_paiement`, `est_gratuit`, `duree_mois`) VALUES
(1, 1, 5, '2025-03-23', '2025-07-23', 1, 300.00, 'SUSPENDU', 0, 0, 'Espèces', 0, 4),
(9, 3, 5, '2025-04-19', '2025-07-19', 1, 345.00, 'ACTIF', 12, 0, 'Carte bancaire', 0, 3),
(12, 9, 84, '2025-04-01', '2025-09-01', 1, 0.00, 'ACTIF', 0, 0, 'Carte bancaire', 1, 5),
(13, 8, 81, '2025-09-18', '2025-12-18', 1, 234.00, 'ACTIF', 6, 0, 'Carte bancaire', 0, 3),
(14, 12, 83, '2025-04-01', '2025-06-01', 1, 300.00, 'ACTIF', 10, 0, 'Carte bancaire', 0, 2),
(15, 3, 63, '2025-04-09', '2025-04-23', 1, 333.00, 'ACTIF', 34, 1, 'Carte bancaire', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `adresse`
--

CREATE TABLE `adresse` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `adress` varchar(255) NOT NULL,
  `postal` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `adresse`
--

INSERT INTO `adresse` (`id`, `name`, `firstname`, `lastname`, `company`, `adress`, `postal`, `city`, `country`, `phone`, `user_id`) VALUES
(1, 'Ariana soghra', 'salma', 'salma', 'XYZ', 'Ariana soghra', '2081', 'Ariana', 'TN', '98645782', NULL),
(2, 'Ariana', 'Mohamed', 'zerriaa', 'XYZ', '20 Rue des Oranges, Impasse 2', '2080', 'Ariana', 'AF', '55 345 678', NULL),
(3, 'Ennasr', 'youssef', 'Ben salah', 'XYZ', 'Rue du Lac, Bloc B, Appartement 12', '2037', 'Ennasr', 'TN', '98 765 432', NULL),
(5, 'Résidence Soukra', 'Adem', 'Trabelsi', 'XYZ', '15 Rue des Roses, Résidence Les Oliviers, Bloc C, Appartement 3', '2081', 'Ariana', 'TN', '20 123 456', NULL),
(12, 'Avenue Habib Bourguiba, Résidence Les Jardins, Apt 5', 'Ali', 'zerriaa', 'XYZ', '20 Rue des Oranges, Impasse 2', '2080', 'Ariana', 'AF', '55123456', NULL),
(65, 'qeqeqegggf', 'ggg', 'tdsqer', 'hhn5', 'hiihihi', '8090', 'tunis', 'TN', '88888888', 84),
(66, 'ee', 'eee', 'eee', 'eee', 'eeeeeeee', '3334', 'eeee', 'TN', '33333333', 76);

-- --------------------------------------------------------

--
-- Table structure for table `avis`
--

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `auteur` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `commentaire` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `avis`
--

INSERT INTO `avis` (`id`, `produit_id`, `auteur`, `commentaire`, `note`, `created_at`) VALUES
(1, 37, 'dd', 'dd', 4, '2025-04-27 17:12:07');

-- --------------------------------------------------------

--
-- Table structure for table `badge`
--

CREATE TABLE `badge` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `date_attribution` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carrier`
--

CREATE TABLE `carrier` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carrier`
--

INSERT INTO `carrier` (`id`, `name`, `description`, `price`) VALUES
(1, 'test', 'test', 1000),
(2, 'Livraison Express Tunisie', '\"Livraison rapide en 24-48h dans les grandes villes (Tunis, Sousse, Sfax). Délais prolongés pour les zones rurales. Suivi de colis disponible.\"', 750),
(3, 'Tunisia Fast Delivery', '\"Livraison garantie en 24h avec suivi GPS. Options de livraison le week-end.\"', 1200),
(4, 'EcoLivraison TN', '\"Livraison low-cost en 3-5 jours ouvrés. Pas de suivi en temps réel.\"', 500);

-- --------------------------------------------------------

--
-- Table structure for table `categorie_produit`
--

CREATE TABLE `categorie_produit` (
  `id` int(11) NOT NULL,
  `nomcategorie` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorie_produit`
--

INSERT INTO `categorie_produit` (`id`, `nomcategorie`, `image`, `description`) VALUES
(0, 'Soins Sportive', '????\0JFIF\0\0\0\0\0\0??\0?\0	\Z\Z\Z\Z\Z(  %!1!%*-...383-7(-.+\n\n\n\r0& %0-.5---------5--/---------------5/----------------??\0\0?\\\"\0??\0\0\0\0\0\0\0\0\0\0\0\0\0??\0I\0\0\0\0!1A\"Qaq2???B????Rb?', 'emma'),
(3, 'Énergétique', '????\0JFIF\0\0\0\0\0\0??\0?\0	\Z \Z\Z \Z( &\"1\"%)+./.385,7(-.+\n\n\n\r2% &.0-//+-/-----0/.-----------/----------------------??\0\0?\0?\"\0??\0\0\0\0\0\0\0\0\0\0\0\0\0\0??\0U\0\0\r		\0\0\0!1A\"Qaq2???#BR???', 'aaa'),
(4, 'Musculation', '????\0JFIF\0\0\0\0\0\0??\0?\0	(\"\Z%!1!%)+...383-7(-.+\n\n\n\r\Z-% %---.-----------------------5------/---------------??\0\0?\0?\0??\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0??\0P\0\0\0\0!1AQ\"aq2?????#BRr??', 'aa');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cours`
--

CREATE TABLE `cours` (
  `id_cours` int(11) NOT NULL,
  `Nom_Cours` varchar(255) NOT NULL,
  `Etat_Cours` varchar(50) NOT NULL,
  `Duree` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cours`
--

INSERT INTO `cours` (`id_cours`, `Nom_Cours`, `Etat_Cours`, `Duree`, `id_user`) VALUES
(2, 'DD', 'DD', 33, 75);

-- --------------------------------------------------------

--
-- Table structure for table `cours_participant`
--

CREATE TABLE `cours_participant` (
  `id` int(11) NOT NULL,
  `id_participant` int(11) DEFAULT NULL,
  `id_cours` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
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
-- Table structure for table `equipe`
--

CREATE TABLE `equipe` (
  `id_equipe` int(11) NOT NULL,
  `nom_equipe` varchar(25) NOT NULL,
  `type_equipe` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipe`
--

INSERT INTO `equipe` (`id_equipe`, `nom_equipe`, `type_equipe`) VALUES
(39, 'Gold Diggers', 'Basket Ball'),
(40, 'Dold Giggers', 'Basket Ball'),
(41, 'Tit', 'Basket Ball'),
(42, 'Ass', 'Basket Ball');

-- --------------------------------------------------------

--
-- Table structure for table `favoris`
--

CREATE TABLE `favoris` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `joueur`
--

CREATE TABLE `joueur` (
  `id_joueur` int(11) NOT NULL,
  `nom_joueur` varchar(25) NOT NULL,
  `id_equipe` int(11) DEFAULT NULL,
  `cin` int(11) NOT NULL,
  `url_photo` varchar(255) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `joueur`
--

INSERT INTO `joueur` (`id_joueur`, `nom_joueur`, `id_equipe`, `cin`, `url_photo`, `id_user`) VALUES
(20, 'Miles Larkot', NULL, 120354845, 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8cmFuZG9tJTIwcGVvcGxlfGVufDB8fDB8fHww', 1),
(21, 'Samuel Jackson', NULL, 12647985, 'https://fr.web.img5.acsta.net/pictures/15/07/27/12/24/354255.jpg', 2),
(22, 'Nick Cage', NULL, 14568521, 'https://upload.wikimedia.org/wikipedia/commons/c/c0/Nicolas_Cage_Deauville_2013.jpg', 3);

-- --------------------------------------------------------

--
-- Table structure for table `match`
--

CREATE TABLE `match` (
  `id_match` int(11) NOT NULL,
  `id_tournoi` int(11) NOT NULL,
  `id_equipe1` int(11) NOT NULL,
  `id_equipe2` int(11) NOT NULL,
  `date_match` date NOT NULL,
  `id_terrain` int(11) NOT NULL,
  `score_equipe1` int(11) NOT NULL,
  `score_equipe2` int(11) NOT NULL,
  `statut_match` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `match`
--

INSERT INTO `match` (`id_match`, `id_tournoi`, `id_equipe1`, `id_equipe2`, `date_match`, `id_terrain`, `score_equipe1`, `score_equipe2`, `statut_match`) VALUES
(41, 13, 39, 40, '2025-05-11', 10, 1, 0, 'completed'),
(42, 13, 40, 39, '2025-05-11', 10, 10, 2, 'completed'),
(43, 13, 41, 42, '2025-05-04', 10, 1, 2, 'completed'),
(44, 13, 40, 42, '2025-05-04', 10, 2, 4, 'completed'),
(45, 13, 39, 42, '2025-05-11', 10, 12, 0, 'completed'),
(46, 13, 41, 42, '2025-05-11', 10, 10, 12, 'completed'),
(47, 13, 41, 42, '2025-05-11', 10, 10, 12, 'completed'),
(48, 13, 39, 42, '2025-05-18', 10, 40, 20, 'completed'),
(49, 13, 39, 40, '2025-05-04', 10, 0, 0, 'ongoing'),
(50, 13, 39, 42, '2025-05-11', 10, 13, 12, 'ongoing');

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messenger_messages`
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
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `carrier_name` varchar(255) NOT NULL,
  `carrier_price` double NOT NULL,
  `delivery` varchar(255) NOT NULL,
  `is_paid` tinyint(1) NOT NULL,
  `reference` varchar(255) NOT NULL,
  `stripe_session_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `user_id`, `created_at`, `carrier_name`, `carrier_price`, `delivery`, `is_paid`, `reference`, `stripe_session_id`) VALUES
(1, 1, '2025-04-14 21:15:23', 'test', 1000, 'salma salma<br/>22222222<br/>salma<br/>Ariana soghra<br/>2081 Ariana<br/>TN', 0, '14042025-67fd7aebca809', '0'),
(2, 1, '2025-04-14 21:15:47', 'test', 1000, 'salma salma<br/>22222222<br/>salma<br/>Ariana soghra<br/>2081 Ariana<br/>TN', 0, '14042025-67fd7b03434ed', 'cs_test_b1hCsvgdfY7PwR7tpbPYDm3YpZac240jJQfPnqaOsGTfcpgSnMBXOidRwv'),
(3, 1, '2025-04-14 21:17:10', 'test', 1000, 'salma salma<br/>22222222<br/>salma<br/>Ariana soghra<br/>2081 Ariana<br/>TN', 0, '14042025-67fd7b564b0f2', 'cs_test_b1iqkMoxjFVupIdAYJ2SQfqK5TF4caXwnA29bPWFAD285WzH23xbsLJKoD'),
(4, 1, '2025-04-14 23:58:04', 'test', 1000, 'salma salma<br/>22222222<br/>salma<br/>Ariana soghra<br/>2081 Ariana<br/>TN', 0, '14042025-67fd84ec0f2ba', 'cs_test_b1ZsXhzREq3G6Un4p8JQp81csX15c4FANyF2wNBsfCIOqygQT3P2ZIjZeO'),
(5, 1, '2025-04-14 23:59:57', 'test', 1000, 'salma salma<br/>22222222<br/>salma<br/>Ariana soghra<br/>2081 Ariana<br/>TN', 0, '14042025-67fd855d472e0', 'cs_test_b1DSuNSzdGYDRz2knpaOCpeTa66EwiTxxdhXl2fJsbBTEvzNlf4QpxbyDO'),
(6, 1, '2025-04-15 00:44:47', 'test', 1000, 'salma salma<br/>22222222<br/>salma<br/>Ariana soghra<br/>2081 Ariana<br/>TN', 0, '15042025-67fd8fdf1afb7', '0'),
(7, 1, '2025-04-15 01:23:06', 'EcoLivraison TN', 500, 'Ahmed Ben salah<br/>55 123 456<br/>XYZ<br/>Avenue Habib Bourguiba, Résidence Les Jardins, Apt 5<br/>2092 Manzah 5<br/>TN', 1, '15042025-67fd98dadf68b', 'cs_test_b1q3keJpLvwoeaddgLoB2LwlV2uyA3YjY5UGuKweOG8JjiKgowS5FnsIcP'),
(8, 1, '2025-04-15 01:26:17', 'Tunisia Fast Delivery', 1200, 'Ahmed Ben salah<br/>55 123 456<br/>XYZ<br/>Avenue Habib Bourguiba, Résidence Les Jardins, Apt 5<br/>2092 Manzah 5<br/>TN', 0, '15042025-67fd999986e5c', 'cs_test_b1Gc1lsQUvNkZVRxWFOu0oaZ9WCdV4HysM8gFVZWWdyxJSuHbtRjAnrzfG'),
(9, 1, '2025-04-15 04:03:04', 'Tunisia Fast Delivery', 1200, 'Mohamed zerriaa<br/>55 345 678<br/>XYZ<br/>20 Rue des Oranges, Impasse 2<br/>2080 Ariana<br/>AF', 0, '15042025-67fdbe588dcbf', '0'),
(10, 1, '2025-04-15 09:28:42', 'Livraison Express Tunisie', 750, 'salma salma<br/>98645782<br/>XYZ<br/>Ariana soghra<br/>2081 Ariana<br/>TN', 1, '15042025-67fe0aaa3480e', 'cs_test_b1GoNnpfyoRxAk7yBJJfoE92nT87ynvnzdZzEmoahUUJNICoRijgSCfNvY'),
(11, 1, '2025-04-15 10:53:53', 'Livraison Express Tunisie', 750, 'Mohamed zerriaa<br/>55 345 678<br/>XYZ<br/>20 Rue des Oranges, Impasse 2<br/>2080 Ariana<br/>AF', 1, '15042025-67fe1ea127851', 'cs_test_b1ZDmkyIeDY5OEHEcOD50GQbJCz5YxPsBjjOttIQF2QuSSb5BbXlaCsNAM'),
(12, 1, '2025-04-15 10:55:00', 'Livraison Express Tunisie', 750, 'Ali zerriaa<br/>55123456<br/>XYZ<br/>20 Rue des Oranges, Impasse 2<br/>2080 Ariana<br/>AF', 1, '15042025-67fe1ee4acc92', 'cs_test_b1fWXKN95B9qXS7JTzEVBSaMV5j3nW0O18E5pY5UBJip28s0GEWL5CWQau'),
(13, 1, '2025-04-15 10:57:11', 'Tunisia Fast Delivery', 1200, 'salma salma<br/>98645782<br/>XYZ<br/>Ariana soghra<br/>2081 Ariana<br/>TN', 0, '15042025-67fe1f67e0f99', '0'),
(14, 1, '2025-04-18 00:44:25', 'Tunisia Fast Delivery', 1200, 'Adem Trabelsi<br/>20 123 456<br/>XYZ<br/>15 Rue des Roses, Résidence Les Oliviers, Bloc C, Appartement 3<br/>2081 Ariana<br/>TN', 0, '18042025-6801844978048', '0'),
(15, 1, '2025-04-18 00:44:27', 'Tunisia Fast Delivery', 1200, 'Adem Trabelsi<br/>20 123 456<br/>XYZ<br/>15 Rue des Roses, Résidence Les Oliviers, Bloc C, Appartement 3<br/>2081 Ariana<br/>TN', 1, '18042025-6801844b7b8b6', 'cs_test_b1o7jcX7Z9ErJZA3nFbx78ByuZ4hWcvfJEMUyiu6vh4jcNvzE9akVEjYVK'),
(16, 76, '2025-04-28 13:05:51', 'Tunisia Fast Delivery', 1200, 'eee eee\n33333333\neee\neeeeeeee\n3334 eeee\nTN', 0, '28042025-680f7d2f48384', '0'),
(17, 76, '2025-04-28 13:43:23', 'Tunisia Fast Delivery', 1200, 'eee eee\n33333333\neee\neeeeeeee\n3334 eeee\nTN', 1, '28042025-680f85fb99749', 'cs_test_b1Fi7370OA0kNDbBVy0NWtgbNas8PZ02sDgtkWsOpqvJ9rT8W2dhjRwccz'),
(18, 76, '2025-04-28 13:56:36', 'EcoLivraison TN', 500, 'eee eee\n33333333\neee\neeeeeeee\n3334 eeee\nTN', 0, '28042025-680f8914a7610', '0'),
(19, 76, '2025-04-28 13:57:46', 'EcoLivraison TN', 500, 'eee eee\n33333333\neee\neeeeeeee\n3334 eeee\nTN', 0, '28042025-680f895ad17a7', '0'),
(20, 76, '2025-04-28 13:58:45', 'EcoLivraison TN', 500, 'eee eee\n33333333\neee\neeeeeeee\n3334 eeee\nTN', 0, '28042025-680f8995292ff', '0'),
(21, 76, '2025-04-28 13:59:18', 'EcoLivraison TN', 500, 'eee eee\n33333333\neee\neeeeeeee\n3334 eeee\nTN', 0, '28042025-680f89b6b7d2c', '0'),
(22, 76, '2025-04-28 13:59:38', 'EcoLivraison TN', 500, 'eee eee\n33333333\neee\neeeeeeee\n3334 eeee\nTN', 0, '28042025-680f89ca8d1c5', '0'),
(23, 76, '2025-04-28 14:00:27', 'EcoLivraison TN', 500, 'eee eee\n33333333\neee\neeeeeeee\n3334 eeee\nTN', 0, '28042025-680f89fb2c40d', '0'),
(24, 76, '2025-04-28 14:00:50', 'EcoLivraison TN', 500, 'eee eee\n33333333\neee\neeeeeeee\n3334 eeee\nTN', 0, '28042025-680f8a128ef1d', '0'),
(25, 76, '2025-04-28 14:03:22', 'EcoLivraison TN', 500, 'eee eee\n33333333\neee\neeeeeeee\n3334 eeee\nTN', 0, '28042025-680f8aaac5e4a', '0'),
(26, 76, '2025-04-28 14:04:45', 'EcoLivraison TN', 500, 'eee eee\n33333333\neee\neeeeeeee\n3334 eeee\nTN', 0, '28042025-680f8afdaf807', '0'),
(27, 76, '2025-04-28 14:05:03', 'EcoLivraison TN', 500, 'eee eee\n33333333\neee\neeeeeeee\n3334 eeee\nTN', 0, '28042025-680f8b0f87736', '0'),
(28, 76, '2025-04-28 14:05:23', 'EcoLivraison TN', 500, 'eee eee\n33333333\neee\neeeeeeee\n3334 eeee\nTN', 0, '28042025-680f8b23aa491', '0'),
(29, 76, '2025-04-28 14:05:40', 'EcoLivraison TN', 500, 'eee eee\n33333333\neee\neeeeeeee\n3334 eeee\nTN', 0, '28042025-680f8b34e5624', '0'),
(30, 76, '2025-04-28 14:06:10', 'EcoLivraison TN', 500, 'eee eee\n33333333\neee\neeeeeeee\n3334 eeee\nTN', 0, '28042025-680f8b527595f', '0'),
(31, 76, '2025-04-28 14:06:26', 'EcoLivraison TN', 500, 'eee eee\n33333333\neee\neeeeeeee\n3334 eeee\nTN', 0, '28042025-680f8b625fd6b', '0');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `id` int(11) NOT NULL,
  `my_order_id` int(11) NOT NULL,
  `product` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double NOT NULL,
  `total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`id`, `my_order_id`, `product`, `quantity`, `price`, `total`) VALUES
(1, 1, 'GG', 2, 99.92, 199.84),
(2, 2, 'GG', 2, 99.92, 199.84),
(3, 3, 'GG', 1, 99.92, 99.92),
(4, 4, 'red bull', 1, 9, 9),
(5, 5, 'red bull', 1, 9, 9),
(6, 6, 'red bull', 2, 9, 18),
(7, 7, 'red bull', 2, 9, 18),
(8, 7, 'Baume musculaire', 1, 125.4, 125.4),
(9, 8, 'Baume musculaire', 1, 125.4, 125.4),
(10, 9, 'Baume musculaire', 1, 125.4, 125.4),
(11, 9, 'Motive8', 1, 345, 345),
(12, 10, 'Baume musculaire', 2, 125.4, 250.8),
(13, 11, 'Motive8', 2, 345, 690),
(14, 11, 'Protéine de riz', 2, 87, 174),
(15, 12, 'Protéine de riz', 1, 87, 87),
(16, 13, 'Protéine de riz', 1, 87, 87),
(17, 14, 'Baume musculaire', 1, 125.4, 125.4),
(18, 15, 'Baume musculaire', 1, 125.4, 125.4),
(19, 16, 'GG', 1, 99.92, 99.92),
(20, 17, 'GG', 2, 99.92, 199.84),
(21, 18, 'Motive8', 1, 345, 345),
(22, 19, 'Motive8', 1, 345, 345),
(23, 20, 'Motive8', 1, 345, 345),
(24, 21, 'Motive8', 1, 345, 345),
(25, 22, 'Motive8', 1, 345, 345),
(26, 23, 'Motive8', 1, 345, 345),
(27, 24, 'Motive8', 1, 345, 345),
(28, 25, 'Motive8', 1, 345, 345),
(29, 26, 'Motive8', 1, 345, 345),
(30, 27, 'Motive8', 1, 345, 345),
(31, 28, 'Motive8', 1, 345, 345),
(32, 29, 'Motive8', 1, 345, 345),
(33, 30, 'Motive8', 1, 345, 345),
(34, 31, 'Motive8', 1, 345, 345);

-- --------------------------------------------------------

--
-- Table structure for table `participant`
--

CREATE TABLE `participant` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `Age` int(11) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `num_telephone` varchar(15) NOT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `participant`
--

INSERT INTO `participant` (`id`, `nom`, `prenom`, `Age`, `adresse`, `num_telephone`, `id_user`) VALUES
(3, 'kkkk', '', 0, '', '', 8);

-- --------------------------------------------------------

--
-- Table structure for table `planning`
--

CREATE TABLE `planning` (
  `id_planning` int(11) NOT NULL,
  `type_activite` varchar(255) NOT NULL,
  `date_planning` date NOT NULL,
  `status` varchar(50) NOT NULL,
  `cours` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `planning`
--

INSERT INTO `planning` (`id_planning`, `type_activite`, `date_planning`, `status`, `cours`, `id_user`) VALUES
(2, 'EEE', '2025-04-16', 'RRR', 2, 75);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produit`
--

CREATE TABLE `produit` (
  `id` int(11) NOT NULL,
  `nom_produit` varchar(255) NOT NULL,
  `categorie` int(11) NOT NULL,
  `prix` double NOT NULL,
  `Stock_Dispo` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `fournisseur` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produit`
--

INSERT INTO `produit` (`id`, `nom_produit`, `categorie`, `prix`, `Stock_Dispo`, `date`, `fournisseur`) VALUES
(37, 'GG', 3, 99.92, 10, '2024-02-26 00:00:00', 'TestFournisseur'),
(38, 'red bull', 0, 9, 20, '2025-01-28 00:00:00', 'Energisante Import Export'),
(39, 'Baume musculaire', 0, 125.4, 45, '2025-01-29 00:00:00', 'Fournisseur généraliste en Tunisie'),
(40, 'Protéine Whey', 0, 54, 54, '2025-01-29 00:00:00', 'Fournisseur généraliste en Tunisie'),
(41, 'Motive8', 0, 345, 40, '2025-01-29 00:00:00', 'Fournisseur généraliste en Tunisie'),
(42, 'Mélange de protéines végétale', 0, 40, 50, '2025-01-28 00:00:00', 'Fournisseur généraliste en Tunisie'),
(43, 'FXX', 0, 40, 40, '2025-01-27 00:00:00', 'Fournisseur généraliste en Tunisie'),
(44, 'Protéine de riz', 0, 87, 100, '2025-01-27 00:00:00', 'Fournisseur généraliste en Tunisie'),
(46, 'proteine', 0, 100, 180, '2025-02-05 00:00:00', 'Fournisseur généraliste en Tunisie'),
(50, 'xtend energy', 0, 40, 5, '2025-02-24 00:00:00', 'Aecor.tn - Nutrition Sportive & Home Fitness en Tunisie');

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `type_abonnement_id` int(11) NOT NULL,
  `date_reservation` datetime NOT NULL,
  `date_debut` datetime DEFAULT NULL,
  `date_fin` datetime DEFAULT NULL,
  `statut` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `type_abonnement_id`, `date_reservation`, `date_debut`, `date_fin`, `statut`, `user_id`) VALUES
(27, 77, '2025-04-23 21:31:42', '2025-04-03 22:31:00', '2025-05-02 03:36:00', 'en cours', 0),
(30, 102, '2025-04-27 12:50:25', '2025-04-16 13:50:00', '2025-04-30 13:50:00', 'en attente', 0),
(24, 50, '2025-04-23 18:58:52', '2025-04-08 19:58:00', '2025-05-11 19:03:00', 'en cours', 0),
(32, 82, '2025-04-15 09:52:12', '2025-04-01 10:52:00', '2025-05-04 10:52:00', 'en attente', 0),
(41, 105, '2025-04-27 23:01:28', '2025-04-08 00:01:00', '2025-05-03 00:01:00', 'en attente', 0),
(37, 105, '2025-04-27 22:19:43', '2025-03-31 23:19:00', '2025-05-11 23:19:00', 'en attente', 0),
(36, 81, '2025-04-27 22:19:16', '2025-04-12 23:17:00', '2025-05-03 23:17:00', 'en attente', 0),
(38, 104, '2025-04-27 22:25:07', '2025-03-31 23:25:00', '2025-04-29 23:24:00', 'en attente', 0),
(42, 50, '2025-04-27 23:20:06', '2025-03-31 00:18:00', '2025-05-04 00:18:00', 'en attente', 78);

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` longtext DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `est_actif` tinyint(1) NOT NULL,
  `capacite_max` int(11) NOT NULL,
  `categorie` varchar(50) NOT NULL,
  `duree_minutes` int(11) NOT NULL,
  `niveau` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `note` decimal(3,2) DEFAULT NULL,
  `nombre_reservations` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `salle` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`id`, `nom`, `description`, `prix`, `est_actif`, `capacite_max`, `categorie`, `duree_minutes`, `niveau`, `created_at`, `updated_at`, `note`, `nombre_reservations`, `image`, `salle`) VALUES
(1, 'natation', 'Service de natation adapté au niveau Intermédiaire dans la catégorie sport. Profitez d\'une expérience unique !', 299.40, 1, 12, 'sportEX', 80, 2, '2025-03-22 17:12:21', '2025-03-22 17:12:21', NULL, 0, '67deef768cdac.png', 'fermé'),
(2, 'EMMA', 'EMMMMMMMMMMA', 280.00, 1, 34, 'NATATION', 46, 1, '2025-03-22 17:13:00', '2025-03-22 17:13:00', NULL, 0, '67deef9d29763.png', 'fermé'),
(3, 'lover', 'Service de lover adapté au niveau Débutant dans la catégorie MEN. Profitez d\'une expérience unique !', 455.00, 1, 66, 'MEN', 56, 1, '2025-03-22 17:32:58', '2025-03-22 17:32:58', 0.00, 0, '67df91317b73d.jpg', ''),
(4, 'AYCHA144', 'Service de AYCHA adapté au niveau Avancé dans la catégorie SPORTIF. Profitez d\'une expérience unique !', 250.50, 1, 27, 'SPORTIF', 48, 3, '2025-03-22 18:37:37', '2025-03-22 18:37:37', NULL, 0, '67df0371d0c43.png', 'ouvert'),
(7, 'GOLF', 'Service de louli adapté au niveau Intermédiaire dans la catégorie spor. Profitez d\'une expérience unique !', 23.90, 1, 57, 'sporU', 56, 1, '2025-03-23 00:32:53', '2025-03-23 00:32:53', NULL, 0, '67df57270521e.png', 'fermé'),
(8, 'cardio', 'Service de TTYU adapté au niveau Débutant dans la catégorie TT. Profitez d\'une expérience unique !', 55.50, 1, 55, 'TTHJ', 55, 1, '2025-03-23 00:36:57', '2025-03-23 00:36:57', NULL, 0, '67fd69a73737c.jpg', ''),
(9, 'Body Pump', 'Service de KKKK adapté au niveau Débutant dans la catégorie DDTT. Profitez d\'une expérience unique !', 666.60, 1, 60, 'DDTT', 44, 1, '2025-03-23 00:37:24', '2025-03-23 00:37:24', NULL, 0, '67fd694258184.jpg', 'fermé'),
(10, 'massage', 'Service de DDDD adapté au niveau Intermédiaire dans la catégorie JJJ. Profitez d\'une expérience unique !', 99.70, 1, 45, 'SPORTIF', 99, 2, '2025-03-23 00:37:56', '2025-03-23 00:37:56', NULL, 0, '67fd69f9b1e27.jpg', ''),
(11, 'Zumba', 'Service de KKHH adapté au niveau Avancé dans la catégorie KJLKJ. Profitez d\'une expérience unique !', 989.90, 1, 99, 'SPORTIF', 67, 3, '2025-03-23 00:38:41', '2025-03-23 00:38:41', NULL, 0, '67fd69687dcf7.jpg', ''),
(12, 'Pilates', 'Service de RTYUE adapté au niveau Intermédiaire dans la catégorie JFJGF. Profitez d\'une expérience unique !', 12.20, 1, 22, 'JFJGF', 78, 2, '2025-03-23 00:39:18', '2025-03-23 00:39:18', NULL, 0, '67df583777fd4.png', 'fermé'),
(20, 'ballet', 'Service de ballet adapté au niveau Intermédiaire dans la catégorie SPORTFINE. Profitez d\'une expérience unique !', 223.90, 1, 30, 'SPORTFINE', 130, 2, '2025-04-22 10:45:42', '2025-04-22 10:45:42', NULL, 0, NULL, 'Fermée'),
(21, 'HHJU', 'Service de HHJU adapté au niveau Débutant dans la catégorie EMME. Profitez d\'une expérience unique !', 123.00, 1, 56, 'EMME', 59, 1, '2025-04-23 14:44:56', '2025-04-23 14:44:56', NULL, 0, NULL, 'ouvert'),
(22, 'IMMENE', 'Service de IMMENE adapté au niveau Débutant dans la catégorie LOVE. Profitez d\'une expérience unique !', 1234.00, 1, 44, 'LOVE', 44, 1, '2025-04-23 14:45:43', '2025-04-23 14:45:43', NULL, 0, '6808fd18696f4.jpg', 'ouvert'),
(23, 'yyyyy', 'Service de yyyyy adapté au niveau Débutant dans la catégorie hhhh. Profitez d\'une expérience unique !', 128.00, 1, 44, 'hhhh', 44, 1, '2025-04-23 15:03:40', '2025-04-23 15:03:40', NULL, 0, NULL, 'ouvert'),
(24, 'zoubinette', 'Service de zoubinette adapté au niveau Débutant dans la catégorie zoubinette. Profitez d\'une expérience unique !', 123.30, 1, 33, 'zoubinette', 33, 1, '2025-04-23 15:34:47', '2025-04-23 15:34:47', NULL, 0, '6809089ab7257.png', 'fermé'),
(25, 'lovely', 'Service de lovely adapté au niveau Avancé dans la catégorie lovely. Profitez d\'une expérience unique !', 444.00, 1, 46, 'lovely', 52, 3, '2025-04-23 16:52:22', '2025-04-23 16:52:22', NULL, 0, NULL, 'ouvert'),
(26, 'lolita', 'Service de lolita adapté au niveau Intermédiaire dans la catégorie ddd. Profitez d\'une expérience unique !', 222.00, 1, 22, 'ddd', 222, 2, '2025-04-23 18:47:44', '2025-04-23 18:47:44', NULL, 0, '680935d19ee9f.png', 'ouvert');

-- --------------------------------------------------------

--
-- Table structure for table `statistiques_equipe`
--

CREATE TABLE `statistiques_equipe` (
  `id` int(11) NOT NULL,
  `id_equipe` int(11) NOT NULL,
  `matchs_joues` int(11) NOT NULL DEFAULT 0,
  `victoires` int(11) NOT NULL DEFAULT 0,
  `defaites` int(11) NOT NULL DEFAULT 0,
  `nuls` int(11) NOT NULL DEFAULT 0,
  `buts_marques` int(11) NOT NULL DEFAULT 0,
  `buts_encaisses` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `statistiques_equipe`
--

INSERT INTO `statistiques_equipe` (`id`, `id_equipe`, `matchs_joues`, `victoires`, `defaites`, `nuls`, `buts_marques`, `buts_encaisses`) VALUES
(26, 39, 2, 1, 1, 0, 40, 20),
(27, 40, 1, 1, 0, 0, 0, 0),
(28, 41, 1, 0, 1, 0, 0, 0),
(29, 42, 2, 1, 1, 0, 20, 40);

-- --------------------------------------------------------

--
-- Table structure for table `terrain`
--

CREATE TABLE `terrain` (
  `id_terrain` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `terrain`
--

INSERT INTO `terrain` (`id_terrain`) VALUES
(10),
(11),
(13);

-- --------------------------------------------------------

--
-- Table structure for table `tournoi`
--

CREATE TABLE `tournoi` (
  `id_tournoi` int(11) NOT NULL,
  `nom_tournoi` varchar(20) NOT NULL,
  `type_tournoi` varchar(20) NOT NULL,
  `date_tournoi` date NOT NULL,
  `description_tournoi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournoi`
--

INSERT INTO `tournoi` (`id_tournoi`, `nom_tournoi`, `type_tournoi`, `date_tournoi`, `description_tournoi`) VALUES
(13, 'Nuts and Balls', 'Basket Ball', '2025-05-04', 'Deez');

-- --------------------------------------------------------

--
-- Table structure for table `type_abonnement`
--

CREATE TABLE `type_abonnement` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `prix` varchar(10) NOT NULL,
  `duree_en_mois` int(11) NOT NULL,
  `is_premium` tinyint(1) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `reduction` double DEFAULT NULL,
  `prix_reduit` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `type_abonnement`
--

INSERT INTO `type_abonnement` (`id`, `nom`, `description`, `prix`, `duree_en_mois`, `is_premium`, `updated_at`, `reduction`, `prix_reduit`) VALUES
(5, 'Abonnement Basic', 'Découvrez l\'Abonnement Basic pour seulement 8€/mois ! Profitez d\'une expérience premium complète pendant 3 mois, parfaite pour les utilisateurs exigeants.', '8.00', 3, 1, '2025-03-22 23:07:04', NULL, NULL),
(50, 'esprittn', 'Abonnement esprittn à 455 €, de type premium complète, pour une durée de 34 mois, destiné à les utilisateurs exigeants.', '455', 34, 1, NULL, 20, '364.00'),
(63, 'NNN', 'Abonnement NNN à type_abonnement.description.default €, de type premium complète, pour une durée de 890 mois, destiné à les utilisateurs exigeants.', 'type_abonn', 890, 1, NULL, NULL, NULL),
(64, 'NomRR', 'Abonnement NomRR à Découvrez l\'Nom pour seulement Prix€/mois ! Profitez d\'une expérience premium complète pendant 0 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 56 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 56, 1, NULL, NULL, NULL),
(75, 'NNN', 'Abonnement NNN à type_abonnement.description.default €, de type premium complète, pour une durée de 890 mois, destiné à les utilisateurs exigeants.', 'type_abonn', 890, 1, NULL, NULL, NULL),
(77, 'NomRR', 'Abonnement NomRR à Découvrez l\'Nom pour seulement Prix€/mois ! Profitez d\'une expérience premium complète pendant 0 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 56 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 56, 1, NULL, NULL, NULL),
(78, 'Abonnement Starter', 'Abonnement Abonnement Starter à Découvrez l\'Abonnement Starter pour seulement 5€/mois ! Profitez d\'une expérience premium complète pendant 1 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 5 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 5, 1, NULL, NULL, NULL),
(79, 'Abonnement Elite', 'Abonnement Abonnement Elite à Découvrez l\'Abonnement Elite pour seulement 25€/mois ! Profitez d\'une expérience premium complète pendant 12 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 25 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 25, 1, NULL, NULL, NULL),
(80, 'Abonnement Basic', 'Abonnement Abonnement Basic à Découvrez l\'Abonnement Basic pour seulement 8€/mois ! Profitez d\'une expérience premium complète pendant 3 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 8 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 8, 1, NULL, NULL, NULL),
(81, 'Abonnement Silver', 'Abonnement Abonnement Silver à Découvrez l\'Abonnement Silver pour seulement 12€/mois ! Profitez d\'une expérience premium complète pendant 4 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 12 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 12, 1, NULL, NULL, NULL),
(82, 'Abonnement Gold', 'Abonnement Abonnement Gold à Découvrez l\'Abonnement Gold pour seulement 20€/mois ! Profitez d\'une expérience premium complète pendant 9 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 20 mois, destiné à les utilisateurs exigeants.', '455', 20, 1, '2025-04-14 20:21:41', 40, '273.00'),
(83, 'Abonnement Premium', 'Abonnement Abonnement Premium à Découvrez l\'Abonnement Premium pour seulement 18€/mois ! Profitez d\'une expérience premium complète pendant 6 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 18 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 18, 1, NULL, NULL, NULL),
(84, 'Abonnement Light', 'Abonnement Abonnement Light à Découvrez l\'Abonnement Light pour seulement 6€/mois ! Profitez d\'une expérience premium complète pendant 2 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 6 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 6, 1, NULL, NULL, NULL),
(85, 'Abonnement Ultimate', 'Abonnement Abonnement Ultimate à Découvrez l\'Abonnement Ultimate pour seulement 30€/mois ! Profitez d\'une expérience premium complète pendant 12 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 30 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 30, 1, NULL, NULL, NULL),
(86, 'Abonnement Flex', 'Abonnement Abonnement Flex à Découvrez l\'Abonnement Flex pour seulement 10€/mois ! Profitez d\'une expérience premium complète pendant 3 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 10 mois, destiné à les utilisateurs exigeants.', 'Découvrez ', 10, 1, NULL, NULL, NULL),
(87, 'Abonnement Gold (Copie)', 'Abonnement Abonnement Gold (Copie) à Découvrez l\'Abonnement Gold pour seulement 20€/mois ! Profitez d\'une expérience premium complète pendant 9 mois, parfaite pour les utilisateurs exigeants. €, de type premium complète, pour une durée de 20 mois, destiné à les utilisateurs exigeants.', '568', 20, 1, '2025-04-14 20:13:14', NULL, NULL),
(88, 'NNN', 'Abonnement NNN à type_abonnement.description.default €, de type premium complète, pour une durée de 890 mois, destiné à les utilisateurs exigeants.', 'type_abonn', 890, 1, NULL, NULL, NULL),
(89, 'Nom', 'Abonnement Nom à Prix €, de type premium complète, pour une durée de 0 mois, destiné à les utilisateurs exigeants.', 'Prix', 0, 1, NULL, NULL, NULL),
(90, 'Abonnement Starter', 'Abonnement Abonnement Starter à 5 €, de type premium complète, pour une durée de 1 mois, destiné à les utilisateurs exigeants.', '5', 1, 1, NULL, NULL, NULL),
(91, 'Abonnement Pro', 'Abonnement Abonnement Pro à 15 €, de type premium complète, pour une durée de 6 mois, destiné à les utilisateurs exigeants.', '15', 6, 1, NULL, NULL, NULL),
(92, 'Abonnement Elite', 'Abonnement Abonnement Elite à 25 €, de type premium complète, pour une durée de 12 mois, destiné à les utilisateurs exigeants.', '25', 12, 1, NULL, NULL, NULL),
(93, 'Abonnement Basic', 'Abonnement Abonnement Basic à 8 €, de type premium complète, pour une durée de 3 mois, destiné à les utilisateurs exigeants.', '8', 3, 1, NULL, NULL, NULL),
(94, 'Abonnement Silver', 'Abonnement Abonnement Silver à 12 €, de type premium complète, pour une durée de 4 mois, destiné à les utilisateurs exigeants.', '12', 4, 1, NULL, NULL, NULL),
(95, 'Abonnement Gold', 'Abonnement Abonnement Gold à 20 €, de type premium complète, pour une durée de 9 mois, destiné à les utilisateurs exigeants.', '20', 9, 1, NULL, NULL, NULL),
(96, 'Abonnement Premium', 'Abonnement Abonnement Premium à 18 €, de type premium complète, pour une durée de 6 mois, destiné à les utilisateurs exigeants.', '18', 6, 1, NULL, NULL, NULL),
(100, 'RE', 'Abonnement RE à 677 €, de type premium complète, pour une durée de 56 mois, destiné à les utilisateurs exigeants.', '677', 56, 1, NULL, 20, '541.60'),
(101, 'YYYEMMA', 'Abonnement YYYEMMA à 300 €, de type essentielle, pour une durée de 7 mois, destiné à un usage quotidien.', '600.0', 7, 0, NULL, 50, '300.00'),
(102, 'tem', 'Abonnement tem à 675 €, de type premium complète, pour une durée de 3 mois, destiné à les utilisateurs exigeants.', '675', 3, 1, NULL, 10, '607.50'),
(103, 'ELLE', 'Abonnement ELLE à 199 €, de type premium complète, pour une durée de 4 mois, destiné à les utilisateurs exigeants.', '344', 4, 1, NULL, 20, '275.20'),
(104, 'EM009', 'Abonnement EM009 à 666 €, de type premium complète, pour une durée de 2 mois, destiné à les utilisateurs exigeants.', '666', 2, 1, NULL, 50, '333.00'),
(105, 'EMMALIVAR', 'Abonnement EMMALIVAR à 69 €, de type premium complète, pour une durée de 4 mois, destiné à les utilisateurs exigeants.', '500', 4, 1, NULL, 50, '250.00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `firstname`, `lastname`) VALUES
(1, 'salmahaouari6@gmail.com', '[]', '$2y$13$E4Pv4GynR1L0jNA17woeWeKVAEQ.YlYokh22VdCLo2FAQh9saCZei', 'salma', 'salma');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `is_active`, `role`) VALUES
(8, 'kkkk', 'arga@etztez', 'trhrz', 1, 'USER'),
(66, 'abJJ', 'a@gmail.COM', '$2y$13$T4MbcoEGVmD4wbpFeoH4Quo/v5JfO/hYzdchEqPnsDQ.keLXKygq2', 1, 'CLIENT'),
(75, 'MENEL', 'MANEL@GMAIL.COM', '$2y$13$tGCw.XVhSeCdMAGUQU5zIew.hHLgAcT0feN9e2zFg7VphNLG33FH.', 1, 'COACH'),
(76, 'emma', 'emma@gmail.com', '$2y$13$Zz5fg8I5dCsh8EZiuWP9NuJytz74hzVj5byfBNBXrDGuATQW9C.eS', 1, 'CLIENT'),
(78, 'LOTCHFI', 'LOTFI@GMAIL.COM', '$2y$13$li9so97HKy9Gj8Rw1Cww3.rSQNZksFypp12donMWSPkUG5BDI2Sju', 1, 'CLIENT'),
(79, 'lili', 'lili@gmail.com', '$2y$13$dHZC8rSrh5hUs7nu5Tc2Z.QKc.13UdzL9VZAnZrWe3kQYk5w/lRQy', 1, 'COACH'),
(80, 'ella', 'ella@gmail.com', '$2y$13$oXNHyq81PhMV7n8G1DA.Q.jphiT3Tv2lH.01ZQaCQIiblgZ6npvFS', 1, 'ROLE_COACH'),
(81, 'emme', 'emmaawini@gmail.com', '$2y$13$4tg6AuC5eaTH2ZnViHyB2OsLt8cmfqVPonvulbKrqYDUvEfR5n2y6', 1, 'ROLE_CLIENT'),
(82, 'RRrrr', 'R@GMAIL.COM', '$2y$13$mJNgyFAgXCzVrqieZESbVu5uRsD2Jrp28ffoipuskxKuR1vGzH3Vi', 1, 'ROLE_CLIENT'),
(83, 'emma', 'emma@gmail.com', '$2y$13$tGGpk8YRtBfFmwdSsb2wdeMfdBBV4kjUB4XmqwQhPf84.AsFQttLa', 1, 'CLIENT'),
(84, 'aycha', 'aycha@gmail.com', '$2y$13$iaUFZUuclkjg5DkcJPSTeudEGD5gasYqSxzGoi.cQPr7cf7umDnPe', 1, 'ROLE_ADMIN');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abonnement`
--
ALTER TABLE `abonnement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_351268BBED5CA9E6` (`service_id`),
  ADD KEY `IDX_351268BB813AF326` (`type_abonnement_id`);

--
-- Indexes for table `adresse`
--
ALTER TABLE `adresse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_avis_produit` (`produit_id`);

--
-- Indexes for table `badge`
--
ALTER TABLE `badge`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_FEF0481DED5CA9E6` (`service_id`);

--
-- Indexes for table `carrier`
--
ALTER TABLE `carrier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categorie_produit`
--
ALTER TABLE `categorie_produit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cours`
--
ALTER TABLE `cours`
  ADD PRIMARY KEY (`id_cours`),
  ADD KEY `IDX_FDCA8C9C6B3CA4B` (`id_user`);

--
-- Indexes for table `cours_participant`
--
ALTER TABLE `cours_participant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_95280B8FCF8DA6E6` (`id_participant`),
  ADD KEY `IDX_95280B8F134FCDAC` (`id_cours`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `equipe`
--
ALTER TABLE `equipe`
  ADD PRIMARY KEY (`id_equipe`);

--
-- Indexes for table `favoris`
--
ALTER TABLE `favoris`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8933C432ED5CA9E6` (`service_id`);

--
-- Indexes for table `joueur`
--
ALTER TABLE `joueur`
  ADD PRIMARY KEY (`id_joueur`),
  ADD KEY `FK_IDJE` (`id_equipe`);

--
-- Indexes for table `match`
--
ALTER TABLE `match`
  ADD PRIMARY KEY (`id_match`),
  ADD KEY `FK_IDMT` (`id_tournoi`),
  ADD KEY `FK_IDME1` (`id_equipe1`),
  ADD KEY `FK_IDME2` (`id_equipe2`),
  ADD KEY `FK_IDMTN` (`id_terrain`);

--
-- Indexes for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F5299398A76ED395` (`user_id`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_ED896F46BFCDF877` (`my_order_id`);

--
-- Indexes for table `participant`
--
ALTER TABLE `participant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D79F6B116B3CA4B` (`id_user`);

--
-- Indexes for table `planning`
--
ALTER TABLE `planning`
  ADD PRIMARY KEY (`id_planning`),
  ADD KEY `IDX_D499BFF6FDCA8C9C` (`cours`),
  ADD KEY `IDX_D499BFF66B3CA4B` (`id_user`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D34A04AD12469DE2` (`category_id`);

--
-- Indexes for table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_42C84955813AF326` (`type_abonnement_id`),
  ADD KEY `FK_DE_F` (`user_id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statistiques_equipe`
--
ALTER TABLE `statistiques_equipe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_statistiques_equipe_id_equipe` (`id_equipe`);

--
-- Indexes for table `terrain`
--
ALTER TABLE `terrain`
  ADD PRIMARY KEY (`id_terrain`);

--
-- Indexes for table `tournoi`
--
ALTER TABLE `tournoi`
  ADD PRIMARY KEY (`id_tournoi`);

--
-- Indexes for table `type_abonnement`
--
ALTER TABLE `type_abonnement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abonnement`
--
ALTER TABLE `abonnement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `adresse`
--
ALTER TABLE `adresse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `badge`
--
ALTER TABLE `badge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carrier`
--
ALTER TABLE `carrier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cours`
--
ALTER TABLE `cours`
  MODIFY `id_cours` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `cours_participant`
--
ALTER TABLE `cours_participant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `equipe`
--
ALTER TABLE `equipe`
  MODIFY `id_equipe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `favoris`
--
ALTER TABLE `favoris`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `joueur`
--
ALTER TABLE `joueur`
  MODIFY `id_joueur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `match`
--
ALTER TABLE `match`
  MODIFY `id_match` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `participant`
--
ALTER TABLE `participant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `planning`
--
ALTER TABLE `planning`
  MODIFY `id_planning` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `statistiques_equipe`
--
ALTER TABLE `statistiques_equipe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `terrain`
--
ALTER TABLE `terrain`
  MODIFY `id_terrain` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tournoi`
--
ALTER TABLE `tournoi`
  MODIFY `id_tournoi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `type_abonnement`
--
ALTER TABLE `type_abonnement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `abonnement`
--
ALTER TABLE `abonnement`
  ADD CONSTRAINT `FK_351268BB813AF326` FOREIGN KEY (`type_abonnement_id`) REFERENCES `type_abonnement` (`id`),
  ADD CONSTRAINT `FK_351268BBED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`);

--
-- Constraints for table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `fk_avis_produit` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `badge`
--
ALTER TABLE `badge`
  ADD CONSTRAINT `FK_FEF0481DED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`);

--
-- Constraints for table `cours`
--
ALTER TABLE `cours`
  ADD CONSTRAINT `FK_FDCA8C9C6B3CA4B` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cours_participant`
--
ALTER TABLE `cours_participant`
  ADD CONSTRAINT `cours_participant_ibfk_1` FOREIGN KEY (`id_participant`) REFERENCES `participant` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cours_participant_ibfk_2` FOREIGN KEY (`id_cours`) REFERENCES `cours` (`id_cours`) ON DELETE CASCADE;

--
-- Constraints for table `favoris`
--
ALTER TABLE `favoris`
  ADD CONSTRAINT `FK_8933C432ED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`);

--
-- Constraints for table `joueur`
--
ALTER TABLE `joueur`
  ADD CONSTRAINT `FK_IDJE` FOREIGN KEY (`id_equipe`) REFERENCES `equipe` (`id_equipe`);

--
-- Constraints for table `match`
--
ALTER TABLE `match`
  ADD CONSTRAINT `FK_IDME1` FOREIGN KEY (`id_equipe1`) REFERENCES `equipe` (`id_equipe`),
  ADD CONSTRAINT `FK_IDME2` FOREIGN KEY (`id_equipe2`) REFERENCES `equipe` (`id_equipe`),
  ADD CONSTRAINT `FK_IDMT` FOREIGN KEY (`id_tournoi`) REFERENCES `tournoi` (`id_tournoi`),
  ADD CONSTRAINT `FK_IDMTN` FOREIGN KEY (`id_terrain`) REFERENCES `terrain` (`id_terrain`);

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `FK_ED896F46BFCDF877` FOREIGN KEY (`my_order_id`) REFERENCES `order` (`id`);

--
-- Constraints for table `participant`
--
ALTER TABLE `participant`
  ADD CONSTRAINT `FK_D79F6B116B3CA4B` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `planning`
--
ALTER TABLE `planning`
  ADD CONSTRAINT `FK_D499BFF66B3CA4B` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_D499BFF6FDCA8C9C` FOREIGN KEY (`cours`) REFERENCES `cours` (`id_cours`) ON DELETE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04AD12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Constraints for table `statistiques_equipe`
--
ALTER TABLE `statistiques_equipe`
  ADD CONSTRAINT `FK_statistiques_equipe_id_equipe` FOREIGN KEY (`id_equipe`) REFERENCES `equipe` (`id_equipe`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
