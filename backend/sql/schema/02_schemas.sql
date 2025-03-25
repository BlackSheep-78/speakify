-- =============================================================================
-- File: schemas.sql
-- Description:
--   Defines the 'schemas' table used to store custom playback schemas per user.
--   Each schema contains a JSON sequence that controls playback logic (language
--   order and repetition), and is tied to the user who created it.
--
-- Usage:
--   Used by the frontend to control the playback sequence per session.
--   Displayed in the Schema Editor and selected in the player UI.
-- =============================================================================

CREATE TABLE `schemas` (
  id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique schema ID',
  user_id INT NOT NULL COMMENT 'Owner of this schema (foreign key to users.id)',
  name VARCHAR(100) NOT NULL COMMENT 'Name of the schema',
  description TEXT COMMENT 'Optional description of what this schema does',
  sequence JSON NOT NULL COMMENT 'Playback sequence array with lang & repeat info',
  speed FLOAT DEFAULT 1.0 COMMENT 'Optional: playback speed multiplier',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation timestamp',
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last modification date',

  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
