-- ==========================================
-- Project: Speakify
-- File: /backend/sql/sentences/insert_sentence_translation.sql
-- Description: Inserts a new translated sentence, links it to an original 
--              sentence as a translation pair, and records the source.
-- ==========================================

-- INPUT:
--   :SENTENCE_TEXT_2       → Text of the translated sentence
--   :LANGUAGE_ID_2         → Language ID of the translated sentence
--   :SENTENCE_ID_1         → ID of the original sentence
--   :TRANSLATION_VERSION   → Version info for this translation (e.g. 1)
--   :SOURCE_ID             → ID of the translation source

-- OUTPUT:
--   Creates entries in: `sentences`, `translation_pairs`, `translation_pair_sources`


-- Step 1: Insert the translated sentence and store the ID
SET @sentence_id_2 = NULL;

INSERT INTO `sentences` (`sentence_text`, `language_id`) 
VALUES (:SENTENCE_TEXT_2, :LANGUAGE_ID_2);

SET @sentence_id_2 = LAST_INSERT_ID();

-- Step 2: Create the translation pair
SET @pair_id = NULL;

INSERT INTO `translation_pairs` (`sentence_id_1`, `sentence_id_2`, `translation_version`, `source_id`) 
VALUES (:SENTENCE_ID_1, @sentence_id_2, :TRANSLATION_VERSION, :SOURCE_ID);

SET @pair_id = LAST_INSERT_ID();

-- Step 3: Link the source to the translation pair
INSERT INTO `translation_pair_sources` (`pair_id`, `source_id`) 
VALUES (@pair_id, :SOURCE_ID);
