
-- Table: sentences
CREATE TABLE IF NOT EXISTS `sentences` (
  `sentence_id` int(11) NOT NULL AUTO_INCREMENT,
  `sentence_text` text NOT NULL,
  `language_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`sentence_id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `sentences_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `languages` (`language_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=160 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
