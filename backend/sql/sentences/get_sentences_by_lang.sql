-- ==========================================
-- Project: Speakify
-- File: /backend/sql/sentences/get_sentences_by_lang.sql
-- Description: Returns grouped translation pairs for each original sentence 
--              in a given language (used for playback).
-- ==========================================

-- INPUT: :LANG_ID (int) — the original language ID
-- OUTPUT: Original + Translated sentence data with language info

SELECT 
    tp.pair_id,
    s1.sentence_id AS original_sentence_id,
    s1.sentence_text AS original_sentence,
    l1.language_id AS original_language_id,
    l1.language_name AS original_language,
    s2.sentence_id AS translated_sentence_id,
    s2.sentence_text AS translated_sentence,
    l2.language_id AS translated_language_id,
    l2.language_name AS translated_language
FROM translation_pairs tp
JOIN sentences s1 ON tp.sentence_id_1 = s1.sentence_id
JOIN languages l1 ON s1.language_id = l1.language_id
JOIN sentences s2 ON tp.sentence_id_2 = s2.sentence_id
JOIN languages l2 ON s2.language_id = l2.language_id
WHERE s1.language_id = :LANG_ID
ORDER BY tp.pair_id, l2.language_name;
