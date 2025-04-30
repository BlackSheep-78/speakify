<?php

// ============================================================================
// ⚠️ DO NOT REMOVE OR MODIFY THIS HEADER
// This file handles sentence translation logic using external translation APIs.
// It retrieves a pending translation, processes it, and stores the result.
// ----------------------------------------------------------------------------
// 📁 File: /backend/classes/logic/Translate.php
// 📦 Project: Speakify
// ============================================================================

class Translate
{
    private TranslationModel $translationModel;
    private GoogleTranslateApi $googleApi;

    public function __construct() 
    {
        $this->translationModel = new TranslationModel();
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
