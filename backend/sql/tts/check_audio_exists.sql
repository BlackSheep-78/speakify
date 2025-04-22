SELECT * 
FROM tts_audio 
WHERE sentence_id = :SID 
  AND language_id = :LID 
  AND provider_id = :PID 
  AND voice = :VOICE
