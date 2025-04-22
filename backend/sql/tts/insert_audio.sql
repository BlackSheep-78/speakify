INSERT INTO tts_audio 
(sentence_id, language_id, provider_id, voice, audio_path, audio_hash, created_at)
VALUES (:SID, :LID, :PID, :VOICE, :PATH, :HASH, NOW())
