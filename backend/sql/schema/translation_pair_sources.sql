
-- Table: translation_pair_sources
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
