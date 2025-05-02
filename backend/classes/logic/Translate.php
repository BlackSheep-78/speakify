<?php


// =============================================================================
// Project: Speakify
// File: /backend/classes/logic/Translate.php
// Description: Handles sentence translation logic using external APIs.
// Note: Retrieves pending items, translates, and stores results.
// =============================================================================
 
class Translate
{
    private TranslationModel $translationModel;
    private GoogleTranslateApi $googleApi;

    public function __construct(array $options = []) 
    {
        $db = $options['db'] ?? null;

        if (!$db instanceof Database) {
            throw new Exception(static::class . " requires a valid 'db' instance.");
        }

        $this->translationModel = new TranslationModel(['db' => $db]);
        $this->googleApi = new GoogleTranslateApi();
    }
    
    public function getRandomPairOfLanguages(): array
    {
        return $this->translationModel->getRandomLanguagePair();
    }

    public function getOneForTranslation(): array
    {
        return $this->translationModel->getPendingTranslation();
    }

    public function connectToGoogleToTranslate(): array
    {
        $data = $this->getOneForTranslation();

        if (empty($data)) {
            Logger::info("Aucune donnée trouvée pour traduction.");
            return [
                'success' => false,
                'error' => 'No sentence available for translation'
            ];
        }

        $translated = $this->googleApi->translate(
            $data['sentence_text_1'],
            $data['language_code_1'],
            $data['language_code_2']
        );

        if (is_array($translated) && isset($translated['success']) && $translated['success'] === false) {
            Logger::error("La traduction a échoué.");
            return [
                'success' => false,
                'error' => $translated['error'],
                'raw' => $translated['raw'] ?? null
            ];
        }

        if (!is_string($translated)) {
            Logger::error("La traduction a échoué — format inattendu.");
            return [
                'success' => false,
                'error' => 'Translation returned unexpected data.'
            ];
        }

        $this->translationModel->saveTranslation(
            $translated,
            $data['language_id_2'],
            $data['sentence_id_1']
        );

        return [
            'success' => true,
            'original' => $data['sentence_text_1'],
            'translated' => $translated,
            'from' => $data['language_code_1'],
            'to' => $data['language_code_2']
        ];
    }
}
