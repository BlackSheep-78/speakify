
-- Table: translation_pairs
CREATE TABLE IF NOT EXISTS `translation_pairs` (
  `pair_id` int(11) NOT NULL AUTO_INCREMENT,
  `sentence_id_1` int(11) NOT NULL,
  `sentence_id_2` int(11) NOT NULL,
  `translation_version` int(11) NOT NULL DEFAULT 1,
  `source_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`pair_id`),
  KEY `sentence_id_1` (`sentence_id_1`),
  KEY `sentence_id_2` (`sentence_id_2`),
  KEY `source_id` (`source_id`) USING BTREE,
  CONSTRAINT `translation_pairs_ibfk_1` FOREIGN KEY (`sentence_id_1`) REFERENCES `sentences` (`sentence_id`) ON DELETE CASCADE,
  CONSTRAINT `translation_pairs_ibfk_2` FOREIGN KEY (`sentence_id_2`) REFERENCES `sentences` (`sentence_id`) ON DELETE CASCADE,
  CONSTRAINT `translation_pairs_ibfk_3` FOREIGN KEY (`source_id`) REFERENCES `sources` (`source_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
