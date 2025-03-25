
-- Table: sentences_start_sample
CREATE TABLE IF NOT EXISTS `sentences_start_sample` (
  `sentence_id` int(11) NOT NULL AUTO_INCREMENT,
  `sentence_text` text NOT NULL,
  `language_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`sentence_id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
