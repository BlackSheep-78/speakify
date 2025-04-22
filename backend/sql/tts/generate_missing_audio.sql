SELECT 
    s.sentence_id,
    s.language_id,
    v.provider_id,
    v.name AS voice
FROM sentences s
JOIN tts_voices v ON v.language_id = s.language_id
JOIN tts_providers p ON p.provider_id = v.provider_id
LEFT JOIN tts_audio a 
    ON a.sentence_id = s.sentence_id
    AND a.language_id = v.language_id
    AND a.provider_id = v.provider_id
    AND a.voice = v.name
WHERE 
    v.active = 1 AND p.active = 1
    AND a.id IS NULL
ORDER BY RAND()
LIMIT 1