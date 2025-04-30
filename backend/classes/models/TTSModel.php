<?php
// =============================================================================
// 📁 File: /backend/classes/models/TTSModel.php
// 📦 Project: Speakify
// 📌 Description: Data access layer for TTS-related operations
// =============================================================================

class TTSModel {
    private $db;

    public function __construct(array $options = []) 
    {
        $this->db = $options['db'] ?? null;
    
        if (!$this->db instanceof Database) 
        {
            throw new Exception(static::class . " requires a valid 'db' instance.");
        }
    }

    public static function getMissingAudioTask(): ?array 
    {
        return $this->db->file('sql/tts/generate_missing_audio.sql')
            ->result(['fetch' => 'assoc'])[0] ?? null;
    }

    public static function getSentenceText(int $sentenceId): ?string
    {
        return $this->db->file('sql/tts/get_sentence.sql')
            ->replace(':SID', $sentenceId, 'i')
            ->result(['fetch' => 'assoc'])[0]['sentence_text'] ?? null;
    }

    public static function getVoiceMetadata(string $voice, int $providerId): ?array 
    {
        return $this->db->file('sql/tts/get_voice_metadata.sql')
            ->replace(':VOICE', $voice, 's')
            ->replace(':PROVIDER_ID', $providerId, 'i')
            ->result(['fetch' => 'assoc'])[0] ?? null;
    }

    public static function checkIfAudioExists(int $sentenceId, int $langId, int $providerId, string $voice): ?array 
    {
        return $this->db->file('sql/tts/check_audio_exists.sql')
            ->replace(':SID', $sentenceId, 'i')
            ->replace(':LID', $langId, 'i')
            ->replace(':PID', $providerId, 'i')
            ->replace(':VOICE', $voice, 's')
            ->result(['fetch' => 'assoc'])[0] ?? null;
    }

    public function insertAudio(int $sentenceId, int $langId, int $providerId, string $voice, string $path, string $hash): void 
    {
        $this->db->file('sql/tts/insert_audio.sql')
            ->replace(':SID', $sentenceId, 'i')
            ->replace(':LID', $langId, 'i')
            ->replace(':PID', $providerId, 'i')
            ->replace(':VOICE', $voice, 's')
            ->replace(':PATH', $path, 's')
            ->replace(':HASH', $hash, 's')
            ->result();
    }
    
    public function getAudioForSentence(int $sentenceId, int $langId): ?array 
    {
        return $this->db->file('/tts/get_audio_for_sentence.sql')
            ->replace(':SID', $sentenceId, 'i')
            ->replace(':LID', $langId, 'i')
            ->result(['fetch' => 'assoc'])[0] ?? null;
    }
    
}
