-- =============================================================================
-- 📄 File: /backend/sql/schema/smart_lists.sql
-- 🎯 Purpose: Stores high-level smart list definitions (Word of the Day, Contextual, etc.)
-- 🔗 Used by: smart_list_items
-- 🧠 Notes:
--    - Can be user-generated or system-generated
--    - Type may guide how the list is built (OpenAI, manual, etc.)
-- =============================================================================

CREATE TABLE `smart_lists` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `type` ENUM('word_of_day', 'keyword', 'contextual', 'conjugation', 'manual') DEFAULT 'manual',
  `owner_id` INT DEFAULT NULL COMMENT 'NULL if system-generated',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`owner_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
