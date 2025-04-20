-- =============================================================================
-- File: sessions.sql
-- Description:
--   Defines the 'sessions' table for Speakify. This table stores active user
--   sessions with a unique token and expiration date. Used for authentication
--   and session validation in the API.
--
-- Usage:
--   Automatically created by the check_schema.php script if missing.
--
-- Example:
--   SELECT * FROM `sessions` WHERE token = 'abc123';
-- =============================================================================

CREATE TABLE `sessions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Session ID',
  `user_id` INT NOT NULL COMMENT 'Associated user (foreign key to users.id)',
  `token` VARCHAR(255) NOT NULL UNIQUE COMMENT 'Random token string for session',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Session creation time',
  `expires_at` DATETIME NOT NULL COMMENT 'Expiration time of the session',

  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
