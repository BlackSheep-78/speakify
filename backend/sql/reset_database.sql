SET foreign_key_checks = 0;

TRUNCATE TABLE `sentences`;
TRUNCATE TABLE `translation_pairs`;
TRUNCATE TABLE `translation_pair_sources`;

SET foreign_key_checks = 1;

INSERT INTO sentences (sentence_text, language_id, created_at, updated_at)
SELECT sentence_text, language_id, created_at, updated_at
FROM sentences_start_sample;