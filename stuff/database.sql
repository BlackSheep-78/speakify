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
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `active` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `name` varchar(100) NOT NULL,
  `code` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table translate.languages : ~99 rows (environ)
DELETE FROM `languages`;
INSERT INTO `languages` (`id`, `active`, `name`, `code`) VALUES
	(1, 0, 'Abkhazian', 'ab'),
	(2, 0, 'Afar', 'aa'),
	(3, 0, 'Afrikaans', 'af'),
	(4, 0, 'Akan', 'ak'),
	(5, 0, 'Albanian', 'sq'),
	(6, 0, 'Amharic', 'am'),
	(7, 0, 'Arabic', 'ar'),
	(8, 0, 'Armenian', 'hy'),
	(9, 0, 'Assamese', 'as'),
	(10, 0, 'Avaric', 'av'),
	(11, 0, 'Avestan', 'ae'),
	(12, 0, 'Aymara', 'ay'),
	(13, 0, 'Azerbaijani', 'az'),
	(14, 0, 'Bambara', 'bm'),
	(15, 0, 'Bashkir', 'ba'),
	(16, 0, 'Basque', 'eu'),
	(17, 0, 'Belarusian', 'be'),
	(18, 0, 'Bengali', 'bn'),
	(19, 0, 'Bihari', 'bh'),
	(20, 0, 'Bislama', 'bi'),
	(21, 0, 'Bosnian', 'bs'),
	(22, 0, 'Breton', 'br'),
	(23, 0, 'Bulgarian', 'bg'),
	(24, 0, 'Burmese', 'my'),
	(25, 0, 'Catalan', 'ca'),
	(26, 0, 'Cebuano', 'ce'),
	(27, 0, 'Chichewa', 'ny'),
	(28, 0, 'Chinese', 'zh'),
	(29, 0, 'Chuvash', 'ch'),
	(30, 0, 'Church Slavic', 'cu'),
	(31, 0, 'Cornish', 'kw'),
	(32, 0, 'Corsican', 'co'),
	(33, 0, 'Croatian', 'hr'),
	(34, 0, 'Czech', 'cs'),
	(35, 0, 'Danish', 'da'),
	(36, 0, 'Divehi', 'dv'),
	(37, 0, 'Dutch', 'nl'),
	(38, 0, 'Dzongkha', 'dz'),
	(39, 1, 'English', 'en'),
	(40, 0, 'Esperanto', 'eo'),
	(41, 0, 'Estonian', 'et'),
	(42, 0, 'Ewe', 'ee'),
	(43, 0, 'Filipino', 'tl'),
	(44, 0, 'Finnish', 'fi'),
	(45, 1, 'French', 'fr'),
	(46, 0, 'Galician', 'gl'),
	(47, 0, 'Georgian', 'ka'),
	(48, 0, 'German', 'de'),
	(49, 0, 'Greek', 'el'),
	(50, 0, 'Gujarati', 'gu'),
	(51, 0, 'Haitian Creole', 'ht'),
	(52, 0, 'Hausa', 'ha'),
	(53, 0, 'Hebrew', 'he'),
	(54, 0, 'Hindi', 'hi'),
	(55, 0, 'Hungarian', 'hu'),
	(56, 0, 'Icelandic', 'is'),
	(57, 0, 'Indonesian', 'id'),
	(58, 0, 'Irish', 'ga'),
	(59, 0, 'Italian', 'it'),
	(60, 0, 'Japanese', 'ja'),
	(61, 0, 'Javanese', 'jw'),
	(62, 0, 'Kannada', 'kn'),
	(63, 0, 'Khmer', 'km'),
	(64, 0, 'Korean', 'ko'),
	(65, 0, 'Latin', 'la'),
	(66, 0, 'Latvian', 'lv'),
	(67, 0, 'Lithuanian', 'lt'),
	(68, 0, 'Macedonian', 'mk'),
	(69, 0, 'Malayalam', 'ml'),
	(70, 0, 'Marathi', 'mr'),
	(71, 0, 'Mongolian', 'mn'),
	(72, 0, 'Nepali', 'ne'),
	(73, 0, 'Norwegian', 'no'),
	(74, 0, 'Polish', 'pl'),
	(75, 1, 'Portuguese', 'pt'),
	(76, 0, 'Punjabi', 'pa'),
	(77, 0, 'Quechua', 'qu'),
	(78, 0, 'Romanian', 'ro'),
	(79, 0, 'Russian', 'ru'),
	(80, 0, 'Serbian', 'sr'),
	(81, 0, 'Sinhalese', 'si'),
	(82, 0, 'Slovak', 'sk'),
	(83, 0, 'Slovenian', 'sl'),
	(84, 0, 'Spanish', 'es'),
	(85, 0, 'Sundanese', 'su'),
	(86, 0, 'Swahili', 'sw'),
	(87, 0, 'Swedish', 'sv'),
	(88, 0, 'Tamil', 'ta'),
	(89, 0, 'Telugu', 'te'),
	(90, 0, 'Thai', 'th'),
	(91, 0, 'Turkish', 'tr'),
	(92, 0, 'Ukrainian', 'uk'),
	(93, 0, 'Urdu', 'ur'),
	(94, 0, 'Uzbek', 'uz'),
	(95, 0, 'Vietnamese', 'vi'),
	(96, 0, 'Welsh', 'cy'),
	(97, 0, 'Xhosa', 'xh'),
	(98, 0, 'Yiddish', 'yi'),
	(99, 0, 'Zulu', 'zu');

-- Listage de la structure de table translate. sentences
DROP TABLE IF EXISTS `sentences`;
CREATE TABLE IF NOT EXISTS `sentences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `languageID` int(10) unsigned NOT NULL DEFAULT 0,
  `text` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `languageID` (`languageID`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table translate.sentences : ~100 rows (environ)
DELETE FROM `sentences`;
INSERT INTO `sentences` (`id`, `languageID`, `text`) VALUES
	(1, 39, 'The sun is shining brightly.'),
	(2, 39, 'I have a dog named Max.'),
	(3, 39, 'She likes to read books.'),
	(4, 39, 'They are going to the park.'),
	(5, 39, 'We eat breakfast every morning.'),
	(6, 39, 'I enjoy playing soccer with my friends.'),
	(7, 39, 'He is studying for his exam.'),
	(8, 39, 'The sky is clear today.'),
	(9, 39, 'My mother makes delicious food.'),
	(10, 39, 'I live in a small house.'),
	(11, 39, 'He is wearing a red shirt.'),
	(12, 39, 'She is my best friend.'),
	(13, 39, 'I want to learn how to swim.'),
	(14, 39, 'We are watching a movie tonight.'),
	(15, 39, 'The coffee is too hot.'),
	(16, 39, 'She has a new phone.'),
	(17, 39, 'I am learning to speak French.'),
	(18, 39, 'We are going on vacation next week.'),
	(19, 39, 'He is playing the guitar.'),
	(20, 39, 'The children are laughing.'),
	(21, 39, 'I drink water every day.'),
	(22, 39, 'The book is on the table.'),
	(23, 39, 'She is very kind and helpful.'),
	(24, 39, 'I am going to the store.'),
	(25, 39, 'He is eating an apple.'),
	(26, 39, 'I like to walk in the park.'),
	(27, 39, 'They are waiting for the bus.'),
	(28, 39, 'We are eating pizza for dinner.'),
	(29, 39, 'She likes to listen to music.'),
	(30, 39, 'I have two brothers and one sister.'),
	(31, 39, 'The weather is very cold today.'),
	(32, 39, 'He is a good student.'),
	(33, 39, 'I am reading a great book.'),
	(34, 39, 'She is wearing a blue dress.'),
	(35, 39, 'I can speak a little Spanish.'),
	(36, 39, 'The cake smells delicious.'),
	(37, 39, 'I am going to the gym.'),
	(38, 39, 'She is a teacher at a school.'),
	(39, 39, 'We are going to have lunch soon.'),
	(40, 39, 'He is playing video games.'),
	(41, 39, 'I like to draw pictures.'),
	(42, 39, 'The car is parked outside.'),
	(43, 39, 'She is very tired today.'),
	(44, 39, 'I am cleaning my room.'),
	(45, 39, 'He is looking at his phone.'),
	(46, 39, 'We are cooking dinner together.'),
	(47, 39, 'They are playing outside.'),
	(48, 39, 'I love watching movies.'),
	(49, 39, 'My favorite color is blue.'),
	(50, 39, 'I need to buy some clothes.'),
	(51, 39, 'The baby is sleeping.'),
	(52, 39, 'I enjoy eating ice cream.'),
	(53, 39, 'He is wearing black shoes.'),
	(54, 39, 'We are meeting at the restaurant.'),
	(55, 39, 'She is very tall.'),
	(56, 39, 'I want to travel to Japan.'),
	(57, 39, 'They are reading a newspaper.'),
	(58, 39, 'I am making coffee right now.'),
	(59, 39, 'The dog is barking loudly.'),
	(60, 39, 'She has long brown hair.'),
	(61, 39, 'I like to play chess.'),
	(62, 39, 'We are going to the beach.'),
	(63, 39, 'He is fixing his bicycle.'),
	(64, 39, 'The room is very clean.'),
	(65, 39, 'I love spending time with my family.'),
	(66, 39, 'She is very good at math.'),
	(67, 39, 'We are planning a surprise party.'),
	(68, 39, 'I have a lot of homework to do.'),
	(69, 39, 'He is wearing a green hat.'),
	(70, 39, 'The flowers in the garden are beautiful.'),
	(71, 39, 'I enjoy going for a run in the morning.'),
	(72, 39, 'She is cooking dinner in the kitchen.'),
	(73, 39, 'I have a lot of friends.'),
	(74, 39, 'They are playing basketball at school.'),
	(75, 39, 'I am watching a sports game.'),
	(76, 39, 'She is wearing a yellow jacket.'),
	(77, 39, 'I like to visit new places.'),
	(78, 39, 'He is always on time.'),
	(79, 39, 'We are learning about history.'),
	(80, 39, 'I am feeling very happy today.'),
	(81, 39, 'The restaurant is very busy.'),
	(82, 39, 'She is brushing her teeth.'),
	(83, 39, 'I like to drink tea in the evening.'),
	(84, 39, 'They are singing a song.'),
	(85, 39, 'I am learning how to bake.'),
	(86, 39, 'The children are playing with toys.'),
	(87, 39, 'I am writing a letter to my friend.'),
	(88, 39, 'We are going to visit our grandparents.'),
	(89, 39, 'He is working at his desk.'),
	(90, 39, 'he room is very bright.'),
	(91, 39, 'I enjoy taking pictures.'),
	(92, 39, 'She is wearing sunglasses.'),
	(93, 39, 'I have a headache.'),
	(94, 39, 'We are studying for the test.'),
	(95, 39, 'The movie was really good.'),
	(96, 39, 'I need to buy some groceries.'),
	(97, 39, 'He is reading a magazine.'),
	(98, 39, 'She is playing with her cat.'),
	(99, 39, 'I am taking a walk in the park.'),
	(100, 39, 'We are having a great time together.');

-- Listage de la structure de table translate. sentence_pairs
DROP TABLE IF EXISTS `sentence_pairs`;
CREATE TABLE IF NOT EXISTS `sentence_pairs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `languageIDA` int(10) unsigned DEFAULT NULL,
  `sentenceIDA` int(10) unsigned DEFAULT NULL,
  `languageIDB` int(10) unsigned DEFAULT NULL,
  `sentenceIDB` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `languageIDA` (`languageIDA`),
  KEY `sentenceIDA` (`sentenceIDA`),
  KEY `languageIDB` (`languageIDB`),
  KEY `sentenceIDB` (`sentenceIDB`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table translate.sentence_pairs : ~0 rows (environ)
DELETE FROM `sentence_pairs`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
