-- Step 1: Insert the French translation and store the sentence_id into a variable
SET @sentence_id_2 = NULL;

INSERT INTO `sentences` (`sentence_text`, `language_id`) 
VALUES ('{sentence_text_2}', {language_id_2});

SET @sentence_id_2 = LAST_INSERT_ID();

-- Step 2: Insert the translation pair using the stored sentence_id
SET @pair_id = NULL;

INSERT INTO `translation_pairs` (`sentence_id_1`, `sentence_id_2`, `translation_version`, `source_id`) 
VALUES ({sentence_id_1}, @sentence_id_2, {translation_version}, {source_id});

SET @pair_id = LAST_INSERT_ID();

-- Step 3: Insert the translation pair source reference
INSERT INTO `translation_pair_sources` (`pair_id`, `source_id`) 
VALUES (@pair_id, {source_id});