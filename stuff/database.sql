-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           10.4.32-MariaDB - mariadb.org binary distribution
-- SE du serveur:                Win64
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


-- Listage de la structure de la base pour translate
CREATE DATABASE IF NOT EXISTS `translate` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `translate`;

-- Listage de la structure de table translate. languages
CREATE TABLE IF NOT EXISTS `languages` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_name` varchar(100) NOT NULL,
  `language_code` varchar(10) NOT NULL,
  `language_active` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`language_id`),
  UNIQUE KEY `language_code` (`language_code`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table translate. sentences
CREATE TABLE IF NOT EXISTS `sentences` (
  `sentence_id` int(11) NOT NULL AUTO_INCREMENT,
  `sentence_text` text NOT NULL,
  `language_id` int(11) NOT NULL,
  `pair_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`sentence_id`),
  KEY `language_id` (`language_id`),
  KEY `idx_pair_id` (`pair_id`),
  CONSTRAINT `sentences_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `languages` (`language_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table translate. sources
CREATE TABLE IF NOT EXISTS `sources` (
  `source_id` int(11) NOT NULL AUTO_INCREMENT,
  `source_name` varchar(255) NOT NULL,
  `source_description` text DEFAULT NULL,
  `source_url` varchar(2083) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`source_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table translate. translation_pairs
CREATE TABLE IF NOT EXISTS `translation_pairs` (
  `pair_id` int(11) NOT NULL AUTO_INCREMENT,
  `sentence_id_1` int(11) NOT NULL,
  `sentence_id_2` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`pair_id`),
  KEY `sentence_id_1` (`sentence_id_1`),
  KEY `sentence_id_2` (`sentence_id_2`),
  CONSTRAINT `translation_pairs_ibfk_1` FOREIGN KEY (`sentence_id_1`) REFERENCES `sentences` (`sentence_id`) ON DELETE CASCADE,
  CONSTRAINT `translation_pairs_ibfk_2` FOREIGN KEY (`sentence_id_2`) REFERENCES `sentences` (`sentence_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table translate. translation_pair_sources
CREATE TABLE IF NOT EXISTS `translation_pair_sources` (
  `pair_id` int(11) NOT NULL,
  `source_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`pair_id`,`source_id`) USING BTREE,
  KEY `pair_id_idx` (`pair_id`) USING BTREE,
  KEY `source_id_idx` (`source_id`) USING BTREE,
  CONSTRAINT `translation_pair_sources_ibfk_1` FOREIGN KEY (`pair_id`) REFERENCES `translation_pairs` (`pair_id`) ON DELETE CASCADE,
  CONSTRAINT `translation_pair_sources_ibfk_2` FOREIGN KEY (`source_id`) REFERENCES `sources` (`source_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Les données exportées n'étaient pas sélectionnées.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
