-- =============================================================================
-- 📄 File: /backend/sql/schema/tts_audio.sql
-- 🎯 Purpose: Store generated TTS audio assets linked to sentences, languages, and providers
-- 🔗 Foreign keys: sentences (FK), languages (FK), tts_providers (FK)
-- 🧠 Notes:
--    - Unique per (sentence_id, language_id, provider_id)
--    - Uses audio_hash for deduplication
--    - Stored path is relative to /backend/storage/
-- =============================================================================

CREATE TABLE `tts_audio` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `sentence_id` INT NOT NULL,
  `language_id` INT NOT NULL,
  `provider_id` INT NOT NULL,
  `voice` VARCHAR(100) DEFAULT NULL,
  `audio_path` VARCHAR(512) NOT NULL,
  `audio_hash` VARCHAR(64) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sentence_voice_unique` (`sentence_id`, `language_id`, `provider_id`, `voice`),
  KEY `audio_hash` (`audio_hash`),
  CONSTRAINT `ts_audio_ibfk_1` FOREIGN KEY (`sentence_id`) REFERENCES `sentences` (`sentence_id`) ON DELETE CASCADE,
  CONSTRAINT `ts_audio_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`language_id`) ON DELETE CASCADE,
  CONSTRAINT `ts_audio_ibfk_3` FOREIGN KEY (`provider_id`) REFERENCES `tts_providers` (`provider_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

