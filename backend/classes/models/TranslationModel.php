<?php
// =============================================================================
// 📁 File: /backend/classes/models/TranslationModel.php
// 📦 Project: Speakify
// 📌 Description: Handles all translation-related database queries.
// =============================================================================

class TranslationModel 
{
    private $db;

    public function __construct(array $options = []) 
    {
        $this->db = $options['db'] ?? null;
    
        if (!$this->db instanceof Database) 
        {
            throw new Exception(static::class . " requires a valid 'db' instance.");
        }
    }

    public function getRandomLanguagePair(): array 
    {
        return $this->db->file('sql/translate/get_random_pair.sql')
                        ->result(['fetch' => 'assoc'])[0] ?? [];
    }

    public function getPendingTranslation(): array 
    {
        return $this->db->file('sql/translate/get_one_for_translation.sql')
                        ->result(['fetch' => 'assoc'])[0] ?? [];
    }

    public function saveTranslation(string $translated, int $language_id_2, int $sentence_id_1): void 
    {
        $this->db->file('sql/sentences/insert_sentence_translation.sql')
                 ->replace(':SENTENCE_TEXT_2', $translated, 's')
                 ->replace(':LANGUAGE_ID_2', $language_id_2, 'i')
                 ->replace(':SENTENCE_ID_1', $sentence_id_1, 'i')
                 ->replace(':TRANSLATION_VERSION', 1, 'i')
                 ->replace(':SOURCE_ID', 1, 'i')
                 ->result();
    }
}
