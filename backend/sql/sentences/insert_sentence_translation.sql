-- FILE : /backend/sql/sentences/insert_sentence_translation.sql

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
