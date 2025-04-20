-- =============================================================================
-- 📄 File: /backend/sql/schema/tts_voices.sql
-- 🎯 Purpose: Register all supported TTS voices per provider and language
-- 🔗 Foreign keys: providers (FK), languages (FK)
-- 🧠 Notes:
--    - Voices are always bound to a single language (e.g. en-US, fr-FR)
--    - You can enable/disable voices per environment
--    - Optional fields: gender, style (e.g. news, cheerful, etc.)
-- =============================================================================

CREATE TABLE `tts_voices` (
  `voice_id` INT NOT NULL AUTO_INCREMENT,
  `provider_id` INT NOT NULL COMMENT 'TTS provider (e.g. Google, Amazon)',
  `language_id` INT NOT NULL COMMENT 'Language supported by this voice',
  `name` VARCHAR(100) NOT NULL COMMENT 'Voice name (e.g. en-US-Wavenet-D)',
  `gender` ENUM('male', 'female', 'neutral') DEFAULT NULL,
  `style` VARCHAR(100) DEFAULT NULL COMMENT 'Optional style (e.g. news, cheerful)',
  `active` TINYINT(1) DEFAULT 1 COMMENT 'Enable/disable this voice',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`voice_id`),
  UNIQUE KEY `provider_voice_unique` (`provider_id`, `name`),
  CONSTRAINT `tts_voices_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `tts_providers` (`provider_id`) ON DELETE CASCADE,
  CONSTRAINT `tts_voices_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`language_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
