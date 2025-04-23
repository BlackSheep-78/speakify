-- =============================================================================
-- 📄 File: /backend/sql/schema/smart_list_items.sql
-- 🎯 Purpose: Connects smart lists to translation pairs
-- 🔗 Used by: smart_lists, translation_pairs
-- =============================================================================

CREATE TABLE `smart_list_items` (
  `smart_list_id` INT NOT NULL,
  `translation_pair_id` INT NOT NULL,
  PRIMARY KEY (`smart_list_id`, `translation_pair_id`),
  FOREIGN KEY (`smart_list_id`) REFERENCES `smart_lists`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`translation_pair_id`) REFERENCES `translation_pairs`(`pair_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
