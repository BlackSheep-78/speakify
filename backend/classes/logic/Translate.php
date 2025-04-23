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
    public function getRandomPairOfLanguages(): array
    {
        return Database::init()
            ->file('/translate/get_random_pair.sql')
            ->result();
    }

    public function getOneForTranslation(): array
    {
        $rows = Database::init()
            ->file('/translate/get_one_for_translation.sql')
            ->result();

        return $rows[0] ?? [];
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

        $GoogleTranslate = new GoogleTranslateApi();
        $translated = $GoogleTranslate->translate(
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

        Database::init()
            ->file("/sentences/insert_sentence_translation.sql")
            ->replace(":SENTENCE_TEXT_2", $translated, "s")
            ->replace(":LANGUAGE_ID_2", $data['language_id_2'], "i")
            ->replace(":SENTENCE_ID_1", $data['sentence_id_1'], "i")
            ->replace(":TRANSLATION_VERSION", 1, "i")
            ->replace(":SOURCE_ID", 1, "i")
            ->result();

        return [
            'success' => true,
            'original' => $data['sentence_text_1'],
            'translated' => $translated,
            'from' => $data['language_code_1'],
            'to' => $data['language_code_2']
        ];
    }
}
