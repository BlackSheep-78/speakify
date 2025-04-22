SELECT 
    p.name AS provider,
    l.language_code AS lang_tag
FROM tts_voices v
JOIN tts_providers p ON p.provider_id = v.provider_id
JOIN languages l ON l.language_id = v.language_id
WHERE v.name = :VOICE AND v.provider_id = :PROVIDER_ID
LIMIT 1
