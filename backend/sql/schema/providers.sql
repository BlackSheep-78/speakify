-- =============================================================================
-- 📄 File: /backend/sql/schema/providers.sql
-- 🎯 Purpose: Define available TTS providers for audio generation
-- 🔗 Used by: ts_audio.provider_id (FK)
-- 🧠 Notes:
--    - Provider names must be unique (e.g. 'google', 'openai')
--    - Can be extended with API metadata, quotas, or fallback flags
-- =============================================================================

CREATE TABLE `tts_providers` (
  `provider_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL UNIQUE COMMENT 'Short provider ID (e.g. google, openai)',
  `description` TEXT COMMENT 'Optional description for UI or admin info',
  `api_url` VARCHAR(255) DEFAULT NULL COMMENT 'Optional base URL for provider API',
  `active` TINYINT(1) DEFAULT 1 COMMENT '1 = usable, 0 = disabled',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`provider_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
