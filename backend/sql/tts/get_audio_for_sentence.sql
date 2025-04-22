SELECT audio_hash
FROM tts_audio
WHERE sentence_id = :SID AND language_id = :LID
ORDER BY created_at DESC
LIMIT 1
