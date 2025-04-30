-- =============================================================================
-- File: backend/sql/schema/playlists.sql
-- Project: Speakify
-- Table: playlists
-- Description: Defines the playlists table, optionally linked to users and schemas
-- =============================================================================

CREATE TABLE IF NOT EXISTS playlists (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  user_id INT DEFAULT NULL,
  schema_id INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (schema_id) REFERENCES `schemas`(id) ON DELETE SET NULL
);
