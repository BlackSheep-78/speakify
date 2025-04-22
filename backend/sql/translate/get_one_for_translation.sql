SELECT 
    s1.sentence_id AS sentence_id_1,
    s1.sentence_text AS sentence_text_1,
    l1.language_name AS language_name_1,
    l1.language_code AS language_code_1,
    l1.language_id AS language_id_1,
    l3.language_name AS language_name_2,
    l3.language_code AS language_code_2,
    l3.language_id AS language_id_2
FROM sentences s1
JOIN languages l1 ON s1.language_id = l1.language_id
JOIN languages l3 ON l3.language_active = 1  -- All active languages for missing translation
WHERE l1.language_active = 1  -- Only consider active languages for the original sentence
AND l3.language_id != l1.language_id  -- Ensure we are checking other languages for translation
AND NOT EXISTS (  -- Check if the translation pair doesn't exist in either direction
    SELECT 1
    FROM translation_pairs t1
    JOIN sentences s2 ON t1.sentence_id_2 = s2.sentence_id
    WHERE t1.sentence_id_1 = s1.sentence_id
    AND s2.language_id = l3.language_id  -- Look for sentences in the target language
)
AND NOT EXISTS (  -- Check reverse direction
    SELECT 1
    FROM translation_pairs t2
    JOIN sentences s3 ON t2.sentence_id_1 = s3.sentence_id
    WHERE t2.sentence_id_2 = s1.sentence_id
    AND s3.language_id = l3.language_id  -- Look for reverse translation pair
)
ORDER BY s1.sentence_id
LIMIT 1;