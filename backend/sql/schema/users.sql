-- =============================================================================
-- File: users.sql
-- Description:
--   Defines the structure for the 'users' table in the Speakify database.
--   This table stores user account information such as email, password hash,
--   name, status, and session tracking data.
--
-- Usage:
--   Create the table during initial database setup.
--
-- Example:
--   INSERT INTO users (email, password_hash, name) VALUES (...);
-- =============================================================================

CREATE TABLE `users` (
  id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique identifier for the user',
  email VARCHAR(255) NOT NULL UNIQUE COMMENT 'User email (used for login)',
  password_hash VARCHAR(255) NOT NULL COMMENT 'Hashed password for authentication',
  name VARCHAR(100) COMMENT 'Display name or full name',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Account creation date',
  last_login DATETIME COMMENT 'Timestamp of the last login session',
  is_active BOOLEAN DEFAULT TRUE COMMENT 'Account status (active or not)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
