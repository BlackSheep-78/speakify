-- =============================================================================
-- 📄 File: /backend/sql/schema/group_members.sql
-- 🎯 Purpose: Links users to groups with roles (student, assistant, owner)
-- 🔗 Used by: groups, users
-- =============================================================================

CREATE TABLE `group_members` (
  `group_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `role` ENUM('owner', 'student', 'assistant') DEFAULT 'student',
  `joined_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`group_id`, `user_id`),
  FOREIGN KEY (`group_id`) REFERENCES `groups`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
