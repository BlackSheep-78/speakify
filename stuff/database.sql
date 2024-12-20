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
DROP DATABASE IF EXISTS `translate`;
CREATE DATABASE IF NOT EXISTS `translate` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `translate`;

-- Listage de la structure de table translate. languages
DROP TABLE IF EXISTS `languages`;
CREATE TABLE IF NOT EXISTS `languages` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `language_name` varchar(100) NOT NULL,
  `language_code` varchar(10) NOT NULL,
  `language_active` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`language_id`),
  UNIQUE KEY `language_code` (`language_code`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table translate.languages : ~99 rows (environ)
INSERT INTO `languages` (`language_id`, `language_name`, `language_code`, `language_active`) VALUES
	(1, 'Abkhazian', 'ab', 0),
	(2, 'Afar', 'aa', 0),
	(3, 'Afrikaans', 'af', 0),
	(4, 'Akan', 'ak', 0),
	(5, 'Albanian', 'sq', 0),
	(6, 'Amharic', 'am', 0),
	(7, 'Arabic', 'ar', 0),
	(8, 'Armenian', 'hy', 0),
	(9, 'Assamese', 'as', 0),
	(10, 'Avaric', 'av', 0),
	(11, 'Avestan', 'ae', 0),
	(12, 'Aymara', 'ay', 0),
	(13, 'Azerbaijani', 'az', 0),
	(14, 'Bambara', 'bm', 0),
	(15, 'Bashkir', 'ba', 0),
	(16, 'Basque', 'eu', 0),
	(17, 'Belarusian', 'be', 0),
	(18, 'Bengali', 'bn', 0),
	(19, 'Bihari', 'bh', 0),
	(20, 'Bislama', 'bi', 0),
	(21, 'Bosnian', 'bs', 0),
	(22, 'Breton', 'br', 0),
	(23, 'Bulgarian', 'bg', 0),
	(24, 'Burmese', 'my', 0),
	(25, 'Catalan', 'ca', 0),
	(26, 'Cebuano', 'ce', 0),
	(27, 'Chichewa', 'ny', 0),
	(28, 'Chinese', 'zh', 0),
	(29, 'Chuvash', 'ch', 0),
	(30, 'Church Slavic', 'cu', 0),
	(31, 'Cornish', 'kw', 0),
	(32, 'Corsican', 'co', 0),
	(33, 'Croatian', 'hr', 0),
	(34, 'Czech', 'cs', 0),
	(35, 'Danish', 'da', 0),
	(36, 'Divehi', 'dv', 0),
	(37, 'Dutch', 'nl', 0),
	(38, 'Dzongkha', 'dz', 0),
	(39, 'English', 'en', 1),
	(40, 'Esperanto', 'eo', 0),
	(41, 'Estonian', 'et', 0),
	(42, 'Ewe', 'ee', 0),
	(43, 'Filipino', 'tl', 0),
	(44, 'Finnish', 'fi', 0),
	(45, 'French', 'fr', 1),
	(46, 'Galician', 'gl', 0),
	(47, 'Georgian', 'ka', 0),
	(48, 'German', 'de', 0),
	(49, 'Greek', 'el', 0),
	(50, 'Gujarati', 'gu', 0),
	(51, 'Haitian Creole', 'ht', 0),
	(52, 'Hausa', 'ha', 0),
	(53, 'Hebrew', 'he', 0),
	(54, 'Hindi', 'hi', 0),
	(55, 'Hungarian', 'hu', 0),
	(56, 'Icelandic', 'is', 0),
	(57, 'Indonesian', 'id', 0),
	(58, 'Irish', 'ga', 0),
	(59, 'Italian', 'it', 0),
	(60, 'Japanese', 'ja', 0),
	(61, 'Javanese', 'jw', 0),
	(62, 'Kannada', 'kn', 0),
	(63, 'Khmer', 'km', 0),
	(64, 'Korean', 'ko', 0),
	(65, 'Latin', 'la', 0),
	(66, 'Latvian', 'lv', 0),
	(67, 'Lithuanian', 'lt', 0),
	(68, 'Macedonian', 'mk', 0),
	(69, 'Malayalam', 'ml', 0),
	(70, 'Marathi', 'mr', 0),
	(71, 'Mongolian', 'mn', 0),
	(72, 'Nepali', 'ne', 0),
	(73, 'Norwegian', 'no', 0),
	(74, 'Polish', 'pl', 0),
	(75, 'Portuguese', 'pt', 1),
	(76, 'Punjabi', 'pa', 0),
	(77, 'Quechua', 'qu', 0),
	(78, 'Romanian', 'ro', 0),
	(79, 'Russian', 'ru', 0),
	(80, 'Serbian', 'sr', 0),
	(81, 'Sinhalese', 'si', 0),
	(82, 'Slovak', 'sk', 0),
	(83, 'Slovenian', 'sl', 0),
	(84, 'Spanish', 'es', 0),
	(85, 'Sundanese', 'su', 0),
	(86, 'Swahili', 'sw', 0),
	(87, 'Swedish', 'sv', 0),
	(88, 'Tamil', 'ta', 0),
	(89, 'Telugu', 'te', 0),
	(90, 'Thai', 'th', 0),
	(91, 'Turkish', 'tr', 0),
	(92, 'Ukrainian', 'uk', 0),
	(93, 'Urdu', 'ur', 0),
	(94, 'Uzbek', 'uz', 0),
	(95, 'Vietnamese', 'vi', 0),
	(96, 'Welsh', 'cy', 0),
	(97, 'Xhosa', 'xh', 0),
	(98, 'Yiddish', 'yi', 0),
	(99, 'Zulu', 'zu', 0);

-- Listage de la structure de table translate. sentences
DROP TABLE IF EXISTS `sentences`;
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

-- Listage des données de la table translate.sentences : ~100 rows (environ)
INSERT INTO `sentences` (`sentence_id`, `sentence_text`, `language_id`, `pair_id`, `created_at`, `updated_at`) VALUES
	(1, 'the sun is shining brightly.', 39, 1, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(2, 'i have a dog named max.', 39, 2, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(3, 'she likes to read books.', 39, 3, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(4, 'they are going to the park.', 39, 4, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(5, 'we eat breakfast every morning.', 39, 5, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(6, 'i enjoy playing soccer with my friends.', 39, 6, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(7, 'he is studying for his exam.', 39, 7, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(8, 'the sky is clear today.', 39, 8, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(9, 'my mother makes delicious food.', 39, 9, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(10, 'i live in a small house.', 39, 10, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(11, 'he is wearing a red shirt.', 39, 11, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(12, 'she is my best friend.', 39, 12, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(13, 'i want to learn how to swim.', 39, 13, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(14, 'we are watching a movie tonight.', 39, 14, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(15, 'the coffee is too hot.', 39, 15, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(16, 'she has a new phone.', 39, 16, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(17, 'i am learning to speak french.', 39, 17, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(18, 'we are going on vacation next week.', 39, 18, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(19, 'he is playing the guitar.', 39, 19, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(20, 'the children are laughing.', 39, 20, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(21, 'i drink water every day.', 39, 21, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(22, 'the book is on the table.', 39, 22, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(23, 'she is very kind and helpful.', 39, 23, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(24, 'i am going to the store.', 39, 24, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(25, 'he is eating an apple.', 39, 25, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(26, 'i like to walk in the park.', 39, 26, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(27, 'they are waiting for the bus.', 39, 27, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(28, 'we are eating pizza for dinner.', 39, 28, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(29, 'she likes to listen to music.', 39, 29, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(30, 'i have two brothers and one sister.', 39, 30, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(31, 'the weather is very cold today.', 39, 31, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(32, 'he is a good student.', 39, 32, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(33, 'i am reading a great book.', 39, 33, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(34, 'she is wearing a blue dress.', 39, 34, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(35, 'i can speak a little spanish.', 39, 35, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(36, 'the cake smells delicious.', 39, 36, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(37, 'i am going to the gym.', 39, 37, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(38, 'she is a teacher at a school.', 39, 38, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(39, 'we are going to have lunch soon.', 39, 39, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(40, 'he is playing video games.', 39, 40, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(41, 'i like to draw pictures.', 39, 41, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(42, 'the car is parked outside.', 39, 42, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(43, 'she is very tired today.', 39, 43, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(44, 'i am cleaning my room.', 39, 44, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(45, 'he is looking at his phone.', 39, 45, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(46, 'we are cooking dinner together.', 39, 46, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(47, 'they are playing outside.', 39, 47, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(48, 'i love watching movies.', 39, 48, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(49, 'my favorite color is blue.', 39, 49, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(50, 'i need to buy some clothes.', 39, 50, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(51, 'the baby is sleeping.', 39, 51, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(52, 'i enjoy eating ice cream.', 39, 52, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(53, 'he is wearing black shoes.', 39, 53, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(54, 'we are meeting at the restaurant.', 39, 54, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(55, 'she is very tall.', 39, 55, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(56, 'i want to travel to japan.', 39, 56, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(57, 'they are reading a newspaper.', 39, 57, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(58, 'i am making coffee right now.', 39, 58, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(59, 'the dog is barking loudly.', 39, 59, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(60, 'she has long brown hair.', 39, 60, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(61, 'i like to play chess.', 39, 61, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(62, 'we are going to the beach.', 39, 62, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(63, 'he is fixing his bicycle.', 39, 63, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(64, 'the room is very clean.', 39, 64, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(65, 'i love spending time with my family.', 39, 65, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(66, 'she is very good at math.', 39, 66, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(67, 'we are planning a surprise party.', 39, 67, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(68, 'i have a lot of homework to do.', 39, 68, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(69, 'he is wearing a green hat.', 39, 69, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(70, 'the flowers in the garden are beautiful.', 39, 70, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(71, 'i enjoy going for a run in the morning.', 39, 71, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(72, 'she is cooking dinner in the kitchen.', 39, 72, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(73, 'i have a lot of friends.', 39, 73, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(74, 'they are playing basketball at school.', 39, 74, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(75, 'i am watching a sports game.', 39, 75, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(76, 'she is wearing a yellow jacket.', 39, 76, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(77, 'i like to visit new places.', 39, 77, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(78, 'he is always on time.', 39, 78, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(79, 'we are learning about history.', 39, 79, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(80, 'i am feeling very happy today.', 39, 80, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(81, 'the restaurant is very busy.', 39, 81, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(82, 'she is brushing her teeth.', 39, 82, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(83, 'i like to drink tea in the evening.', 39, 83, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(84, 'they are singing a song.', 39, 84, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(85, 'i am learning how to bake.', 39, 85, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(86, 'the children are playing with toys.', 39, 86, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(87, 'i am writing a letter to my friend.', 39, 87, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(88, 'we are going to visit our grandparents.', 39, 88, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(89, 'he is working at his desk.', 39, 89, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(90, 'he room is very bright.', 39, 90, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(91, 'i enjoy taking pictures.', 39, 91, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(92, 'she is wearing sunglasses.', 39, 92, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(93, 'i have a headache.', 39, 93, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(94, 'we are studying for the test.', 39, 94, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(95, 'the movie was really good.', 39, 95, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(96, 'i need to buy some groceries.', 39, 96, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(97, 'he is reading a magazine.', 39, 97, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(98, 'she is playing with her cat.', 39, 98, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(99, 'i am taking a walk in the park.', 39, 99, '2024-12-09 14:52:11', '2024-12-09 14:52:11'),
	(100, 'we are having a great time together.', 39, 100, '2024-12-09 14:52:11', '2024-12-09 14:52:11');

-- Listage de la structure de table translate. translation_pairs
DROP TABLE IF EXISTS `translation_pairs`;
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

-- Listage des données de la table translate.translation_pairs : ~0 rows (environ)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
